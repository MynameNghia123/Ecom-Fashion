<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ChatSession;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiChatController extends Controller
{
    /**
     * POST /api/client/ai/chat
     * Xử lý câu hỏi chat của khách hàng (lưu DB nếu đã login)
     */
    public function chat(Request $request): JsonResponse
    {
        $request->validate([
            'messages' => 'required|array|min:1',
            'messages.*.role' => 'required|in:user,model',
            'messages.*.content' => 'required|string|max:2000',
        ]);

        $user = $request->user('sanctum');

        // 1. Gọi Gemini API
        $apiKey = trim(config('services.gemini.key'));
        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'AI service chưa được cấu hình API Key.'
            ], 503);
        }

        // Lấy ngữ cảnh sản phẩm nếu có product_id được gửi lên hoặc tìm kiếm trong câu hỏi gần nhất
        $productId = $request->input('product_id');
        $productContext = '';

        if ($productId) {
            $product = \App\Models\Product::with(['productVariants.attributeValues.attribute'])->find($productId);
        } else {
            // Tìm kiếm thông minh dựa trên nội dung câu chat cuối cùng của user
            $latestMsg = collect($request->input('messages'))->last();
            $queryText = $latestMsg ? trim($latestMsg['content']) : '';
            
            if (strlen($queryText) > 3) {
                // 1. So khớp ngược bằng SQL: Câu hỏi của user có chứa tên sản phẩm trong DB không
                $product = \App\Models\Product::with(['productVariants.attributeValues.attribute'])
                    ->whereRaw('INSTR(?, `name`) > 0', [$queryText])
                    ->first();

                // 2. Nếu chưa thấy, trích xuất mã SKU/Mã sản phẩm dạng chữ và số viết hoa (ví dụ: QST262090)
                if (!$product) {
                    preg_match_all('/[a-zA-Z0-9-]{5,}/', $queryText, $matches);
                    if (!empty($matches[0])) {
                        foreach ($matches[0] as $code) {
                            $product = \App\Models\Product::where('name', 'like', "%{$code}%")
                                ->orWhereHas('productVariants', function ($q) use ($code) {
                                    $q->where('sku', 'like', "%{$code}%");
                                })
                                ->with(['productVariants.attributeValues.attribute'])
                                ->first();
                            if ($product) break;
                        }
                    }
                }
            }
        }

        if (isset($product)) {
            $variantsInfo = [];
            foreach ($product->productVariants as $v) {
                $attrs = [];
                foreach ($v->attributeValues as $av) {
                    $attrs[] = ($av->attribute->name ?? 'Attribute') . ': ' . $av->value;
                }
                $attrStr = implode(', ', $attrs);
                $price = number_format($v->price) . ' VND';
                $salePrice = $v->sale_price ? (number_format($v->sale_price) . ' VND') : 'Không có';
                $variantsInfo[] = "- SKU: {$v->sku} | Giá gốc: {$price} | Giá sale: {$salePrice} | Kho: {$v->stock_quantity} | Biến thể ({$attrStr})";
            }
            $variantsText = implode("\n", $variantsInfo);

            $productContext = "DƯỚI ĐÂY LÀ THÔNG TIN SẢN PHẨM KHÁCH HÀNG ĐANG HỎI/XEM (Hãy trả lời chính xác theo thông tin này, không tự bịa giá hoặc size):\n" .
                "Tên sản phẩm: {$product->name}\n" .
                "Thương hiệu: {$product->brand}\n" .
                "Mô tả: {$product->description}\n" .
                "Các biến thể/Size/Màu sắc hiện có trong kho:\n" .
                $variantsText . "\n\n";
        }

        $systemInstruction = [
            'parts' => [[
                'text' => 'Bạn là Trợ lý thời trang AI của thương hiệu Ecom Fashion. ' .
                          'Nhiệm vụ của bạn là tư vấn phong cách, gợi ý trang phục, phối đồ và giải đáp về thời trang. ' .
                          'Luôn trả lời bằng tiếng Việt, ngắn gọn, thân thiện, lịch sự và chuyên nghiệp.' . "\n\n" .
                          $productContext
            ]]
        ];

        $contents = collect($request->input('messages', []))->map(fn($msg) => [
            'role' => $msg['role'] === 'model' ? 'model' : 'user',
            'parts' => [['text' => $msg['content']]]
        ])->values()->toArray();

        $payload = [
            'system_instruction' => $systemInstruction,
            'contents' => $contents,
            'generationConfig' => [
                'maxOutputTokens' => 1024,
                'temperature' => 0.7,
            ]
        ];

        // Danh sách các model Gemini khả dụng, ưu tiên các model có quota hoạt động tốt nhất
        $models = [
            "gemini-flash-latest",
            "gemini-2.5-flash-lite",
            "gemini-2.0-flash-lite",
            "gemini-pro-latest",
            "gemini-2.0-flash",
            "gemini-1.5-flash"
        ];

        $reply = null;
        $lastErrorMessage = '';

        foreach ($models as $model) {
            if ($reply) break;

            $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

            try {
                $response = Http::timeout(15)
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                        'x-goog-api-key' => $apiKey
                    ])
                    ->post($endpoint, $payload);

                if ($response->successful()) {
                    $data = $response->json();
                    $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
                    if ($reply) {
                        break; // Đã gọi thành công!
                    }
                } else {
                    $errData = $response->json();
                    $lastErrorMessage = $errData['error']['message'] ?? ('HTTP Status ' . $response->status());
                }
            } catch (\Exception $e) {
                $lastErrorMessage = $e->getMessage();
            }
        }

        if (!$reply) {
            Log::error('Gemini API call failed. Last error: ' . $lastErrorMessage);
            return response()->json([
                'success' => false,
                'message' => 'AI service phản hồi: ' . $lastErrorMessage
            ], 502);
        }

        // 2. Nếu Customer đã đăng nhập -> Lưu tin nhắn mới vào DB
        if ($user) {
            $chatSession = ChatSession::firstOrCreate(
                ['customer_id' => $user->id]
            );

            // Lấy tin nhắn câu hỏi mới nhất từ user
            $reqMessages = $request->input('messages', []);
            $latestUserMsg = end($reqMessages);
            if ($latestUserMsg && $latestUserMsg['role'] === 'user') {
                ChatMessage::create([
                    'chat_session_id' => $chatSession->id,
                    'sender' => 'user',
                    'message' => $latestUserMsg['content'],
                ]);
            }

            // Lưu câu trả lời từ AI (model)
            ChatMessage::create([
                'chat_session_id' => $chatSession->id,
                'sender' => 'model',
                'message' => $reply,
            ]);
        }

        return response()->json([
            'success' => true,
            'reply' => $reply
        ]);
    }

    /**
     * GET /api/client/ai/history
     * Lấy lịch sử chat từ DB dành cho Customer đã đăng nhập
     */
    public function history(Request $request): JsonResponse
    {
        $user = $request->user('sanctum');
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $session = ChatSession::where('customer_id', $user->id)->with('messages')->first();

        if (!$session) {
            return response()->json([
                'success' => true,
                'data' => []
            ]);
        }

        $messages = $session->messages->map(fn($m) => [
            'role' => $m->sender,
            'content' => $m->message,
            'created_at' => $m->created_at
        ]);

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    /**
     * POST /api/client/ai/sync-guest-history
     * Đồng bộ lịch sử chat từ Cookie của khách vào DB khi họ vừa Đăng nhập
     */
    public function syncGuestHistory(Request $request): JsonResponse
    {
        $user = $request->user('sanctum');
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'messages' => 'required|array',
            'messages.*.role' => 'required|in:user,model',
            'messages.*.content' => 'required|string',
        ]);

        $session = ChatSession::firstOrCreate(
            ['customer_id' => $user->id]
        );

        // Đẩy danh sách tin nhắn vào DB nếu chưa tồn tại
        foreach ($request->messages as $msg) {
            if ($msg['role'] === 'model' && str_contains($msg['content'], 'Xin chào! Tôi là Trợ lý thời trang AI')) {
                continue;
            }

            $exists = ChatMessage::where('chat_session_id', $session->id)
                ->where('sender', $msg['role'])
                ->where('message', $msg['content'])
                ->exists();

            if (!$exists) {
                ChatMessage::create([
                    'chat_session_id' => $session->id,
                    'sender' => $msg['role'],
                    'message' => $msg['content'],
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Đồng bộ lịch sử chat thành công!'
        ]);
    }
}
