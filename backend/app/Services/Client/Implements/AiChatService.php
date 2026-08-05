<?php
namespace App\Services\Client\Implements;
use App\Models\Product;
use App\Repositories\Client\Interfaces\AiChatRepositoryInterface;
use App\Services\Client\Interfaces\AiChatServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiChatService implements AiChatServiceInterface
{
    public function __construct(private readonly AiChatRepositoryInterface $repo) {}

    public function chat(array $messages, ?int $productId, ?int $customerId): array
    {
        $apiKey = trim(config('services.gemini.key'));
        if (!$apiKey) {
            return [
                'success' => false,
                'message' => 'AI service chưa được cấu hình API Key.',
                'code'    => 503,
            ];
        }

        $productContext = '';
        $product = null;

        if ($productId) {
            $product = Product::with(['productVariants.attributeValues.attribute'])->find($productId);
        } else {
            $latestMsg = collect($messages)->last();
            $queryText = $latestMsg ? trim($latestMsg['content']) : '';
            
            if (strlen($queryText) > 3) {
                $product = Product::with(['productVariants.attributeValues.attribute'])
                    ->whereRaw('INSTR(?, `name`) > 0', [$queryText])
                    ->first();

                if (!$product) {
                    preg_match_all('/[a-zA-Z0-9-]{5,}/', $queryText, $matches);
                    if (!empty($matches[0])) {
                        foreach ($matches[0] as $code) {
                            $product = Product::where('name', 'like', "%{$code}%")
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

        if ($product) {
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

        $contents = collect($messages)->map(fn($msg) => [
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
                        break;
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
            return [
                'success' => false,
                'message' => 'AI service phản hồi: ' . $lastErrorMessage,
                'code'    => 502,
            ];
        }

        if ($customerId) {
            $session = $this->repo->findOrCreateSession($customerId);

            $latestUserMsg = end($messages);
            if ($latestUserMsg && $latestUserMsg['role'] === 'user') {
                $this->repo->createMessage($session->id, 'user', $latestUserMsg['content']);
            }

            $this->repo->createMessage($session->id, 'model', $reply);
        }

        return [
            'success' => true,
            'reply'   => $reply,
        ];
    }

    public function getHistory(int $customerId): array
    {
        $session = $this->repo->findSessionByCustomerId($customerId);

        if (!$session) {
            return [];
        }

        return $session->messages->map(fn($m) => [
            'role'       => $m->sender,
            'content'    => $m->message,
            'created_at' => $m->created_at
        ])->toArray();
    }

    public function syncGuestHistory(int $customerId, array $messages): void
    {
        $session = $this->repo->findOrCreateSession($customerId);

        foreach ($messages as $msg) {
            if ($msg['role'] === 'model' && str_contains($msg['content'], 'Xin chào! Tôi là Trợ lý thời trang AI')) {
                continue;
            }

            if (!$this->repo->messageExists($session->id, $msg['role'], $msg['content'])) {
                $this->repo->createMessage($session->id, $msg['role'], $msg['content']);
            }
        }
    }
}
