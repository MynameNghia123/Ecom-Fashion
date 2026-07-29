<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\VNPayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VNPayController extends Controller
{
    public function __construct(private readonly VNPayService $vnpay) {}

    /**
     * GET /api/client/vnpay/return
     * 
     * Frontend gọi API này sau khi VNPAY redirect về để xác minh kết quả.
     * (Frontend nhận query params từ VNPAY, gửi lại backend để verify chữ ký)
     */
    public function verifyReturn(Request $request): JsonResponse
    {
        $vnpData = $request->query(); // Tất cả query params từ VNPAY

        // ── 1. Xác minh chữ ký ───────────────────────────────────────────
        if (!$this->vnpay->verifySignature($vnpData)) {
            return response()->json([
                'success' => false,
                'message' => 'Chữ ký không hợp lệ.',
                'code'    => 'INVALID_SIGNATURE',
            ], 400);
        }

        $orderCode = $vnpData['vnp_TxnRef'] ?? null;
        $order     = Order::where('order_code', $orderCode)->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng.',
                'code'    => 'ORDER_NOT_FOUND',
            ], 404);
        }

        // ── 2. Tránh cập nhật lại đơn đã xử lý ──────────────────────────
        if ($order->payment_status === 'paid') {
            return response()->json([
                'success'    => true,
                'message'    => 'Đơn hàng đã được thanh toán.',
                'order_code' => $order->order_code,
            ]);
        }

        // ── 3. Cập nhật trạng thái ───────────────────────────────────────
        if ($this->vnpay->isSuccess($vnpData)) {
            $order->update([
                'payment_status' => 'paid',
                'status'         => 'confirmed',
                'transaction_id' => $vnpData['vnp_TransactionNo'] ?? null,
            ]);

            return response()->json([
                'success'    => true,
                'message'    => 'Thanh toán thành công!',
                'order_code' => $order->order_code,
            ]);
        }

        // Thanh toán thất bại — huỷ đơn và hoàn tồn kho
        $order->update([
            'payment_status' => 'failed',
            'status'         => 'cancelled',
            'transaction_id' => $vnpData['vnp_TransactionNo'] ?? null,
        ]);

        // Hoàn tồn kho
        foreach ($order->details as $detail) {
            $detail->productVariant?->increment('stock_quantity', $detail->quantity);
        }

        $responseCode = $vnpData['vnp_ResponseCode'] ?? 'unknown';

        return response()->json([
            'success' => false,
            'message' => 'Thanh toán thất bại hoặc bị huỷ. Mã lỗi: ' . $responseCode,
            'code'    => 'PAYMENT_FAILED',
        ], 400);
    }

    /**
     * POST /api/client/vnpay/ipn
     * 
     * VNPAY gọi endpoint này server-to-server để xác nhận giao dịch (IPN).
     * (Endpoint này hoạt động trên production khi server có public IP)
     */
    public function ipn(Request $request): \Illuminate\Http\Response
    {
        $vnpData = $request->query();

        if (!$this->vnpay->verifySignature($vnpData)) {
            return response('{"RspCode":"97","Message":"Invalid Signature"}');
        }

        $orderCode = $vnpData['vnp_TxnRef'] ?? null;
        $order     = Order::where('order_code', $orderCode)->first();

        if (!$order) {
            return response('{"RspCode":"01","Message":"Order Not Found"}');
        }

        if ($order->payment_status === 'paid') {
            return response('{"RspCode":"02","Message":"Order Already Confirmed"}');
        }

        // Kiểm tra số tiền khớp
        $vnpAmount = (int)($vnpData['vnp_Amount'] ?? 0);
        $expected  = (int)($order->final_amount * 100);

        if ($vnpAmount !== $expected) {
            return response('{"RspCode":"04","Message":"Invalid Amount"}');
        }

        if ($this->vnpay->isSuccess($vnpData)) {
            $order->update([
                'payment_status' => 'paid',
                'status'         => 'confirmed',
                'transaction_id' => $vnpData['vnp_TransactionNo'] ?? null,
            ]);
        } else {
            $order->update([
                'payment_status' => 'failed',
                'status'         => 'cancelled',
            ]);

            foreach ($order->details as $detail) {
                $detail->productVariant?->increment('stock_quantity', $detail->quantity);
            }
        }

        return response('{"RspCode":"00","Message":"Confirm Success"}');
    }
}
