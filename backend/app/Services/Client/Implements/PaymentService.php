<?php
namespace App\Services\Client\Implements;
use App\Models\Order;
use App\Services\Client\Interfaces\PaymentServiceInterface;
use App\Services\VNPayService;

class PaymentService implements PaymentServiceInterface
{
    public function __construct(private readonly VNPayService $vnpay) {}

    public function verifyReturn(array $vnpData): array
    {
        if (!$this->vnpay->verifySignature($vnpData)) {
            return [
                'success' => false,
                'message' => 'Chữ ký không hợp lệ.',
                'code'    => 'INVALID_SIGNATURE',
            ];
        }

        $orderCode = $vnpData['vnp_TxnRef'] ?? null;
        $order     = Order::with('details.productVariant')->where('order_code', $orderCode)->first();

        if (!$order) {
            return [
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng.',
                'code'    => 'ORDER_NOT_FOUND',
            ];
        }

        if ($order->payment_status === 'paid') {
            return [
                'success'    => true,
                'message'    => 'Đơn hàng đã được thanh toán.',
                'order_code' => $order->order_code,
            ];
        }

        if ($this->vnpay->isSuccess($vnpData)) {
            $order->update([
                'payment_status' => 'paid',
                'status'         => 'confirmed',
                'transaction_id' => $vnpData['vnp_TransactionNo'] ?? null,
            ]);

            return [
                'success'    => true,
                'message'    => 'Thanh toán thành công!',
                'order_code' => $order->order_code,
            ];
        }

        $order->update([
            'payment_status' => 'failed',
            'status'         => 'cancelled',
            'transaction_id' => $vnpData['vnp_TransactionNo'] ?? null,
        ]);

        foreach ($order->details as $detail) {
            $detail->productVariant?->increment('stock_quantity', $detail->quantity);
        }

        $responseCode = $vnpData['vnp_ResponseCode'] ?? 'unknown';

        return [
            'success' => false,
            'message' => 'Thanh toán thất bại hoặc bị huỷ. Mã lỗi: ' . $responseCode,
            'code'    => 'PAYMENT_FAILED',
        ];
    }

    public function handleIpn(array $vnpData): array
    {
        if (!$this->vnpay->verifySignature($vnpData)) {
            return ['rspCode' => '97', 'message' => 'Invalid Signature'];
        }

        $orderCode = $vnpData['vnp_TxnRef'] ?? null;
        $order     = Order::with('details.productVariant')->where('order_code', $orderCode)->first();

        if (!$order) {
            return ['rspCode' => '01', 'message' => 'Order Not Found'];
        }

        if ($order->payment_status === 'paid') {
            return ['rspCode' => '02', 'message' => 'Order Already Confirmed'];
        }

        $vnpAmount = (int)($vnpData['vnp_Amount'] ?? 0);
        $expected  = (int)($order->final_amount * 100);

        if ($vnpAmount !== $expected) {
            return ['rspCode' => '04', 'message' => 'Invalid Amount'];
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

        return ['rspCode' => '00', 'message' => 'Confirm Success'];
    }
}
