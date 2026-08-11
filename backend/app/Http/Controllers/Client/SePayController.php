<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\SePayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class SePayController extends Controller
{
    public function __construct(private readonly SePayService $sePayService) {}

    /**
     * GET /api/client/sepay/info/{orderCode}
     * Trả về thông tin thanh toán QR cho đơn hàng SePay.
     */
    public function paymentInfo(string $orderCode): JsonResponse
    {
        $order = Order::where('order_code', $orderCode)
            ->where('payment_method', 'sepay')
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy đơn hàng SePay.'], 404);
        }

        if ($order->payment_status === 'paid') {
            return response()->json([
                'success'  => true,
                'paid'     => true,
                'message'  => 'Đơn hàng đã được thanh toán.',
                'data'     => $this->sePayService->createPaymentInfo($order),
            ]);
        }

        return response()->json([
            'success' => true,
            'paid'    => false,
            'data'    => $this->sePayService->createPaymentInfo($order),
        ]);
    }

    /**
     * GET /api/client/sepay/check/{orderCode}
     * Polling: kiểm tra trạng thái thanh toán đơn hàng.
     */
    public function checkStatus(string $orderCode): JsonResponse
    {
        $order = Order::where('order_code', $orderCode)->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy đơn hàng.'], 404);
        }

        return response()->json([
            'success'        => true,
            'paid'           => $order->payment_status === 'paid',
            'payment_status' => $order->payment_status,
            'order_status'   => $order->status,
        ]);
    }

    /**
     * POST /api/sepay/webhook (public — no auth)
     * SePay gọi webhook này khi chuyển khoản được xác nhận.
     */
    public function webhook(Request $request): Response
    {
        $authHeader = $request->header('Authorization', '');

        if (!$this->sePayService->verifyWebhookSignature($authHeader)) {
            Log::warning('[SEPAY] Invalid webhook signature', ['auth' => $authHeader]);
            return response('Unauthorized', 401);
        }

        $payload = $request->all();
        Log::info('[SEPAY] Webhook received', $payload);

        $orderCode = $this->sePayService->extractOrderCodeFromWebhook($payload);

        if (!$orderCode) {
            return response(json_encode(['success' => false, 'message' => 'Cannot parse order code']), 200)
                ->header('Content-Type', 'application/json');
        }

        $order = Order::where('order_code', $orderCode)->first();

        if (!$order) {
            Log::warning('[SEPAY] Order not found for webhook', ['order_code' => $orderCode]);
            return response(json_encode(['success' => false, 'message' => 'Order not found']), 200)
                ->header('Content-Type', 'application/json');
        }

        if ($order->payment_status !== 'paid') {
            $order->update([
                'payment_status' => 'paid',
                'status'         => 'confirmed',
            ]);

            Log::info('[SEPAY] Order marked as paid', ['order_code' => $orderCode, 'amount' => $payload['transferAmount'] ?? 0]);
        }

        return response(json_encode(['success' => true, 'message' => 'OK']), 200)
            ->header('Content-Type', 'application/json');
    }
}
