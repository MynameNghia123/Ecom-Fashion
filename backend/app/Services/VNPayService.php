<?php
namespace App\Services;

use App\Models\Order;

class VNPayService
{
    private string $tmnCode;
    private string $hashSecret;
    private string $paymentUrl;
    private string $returnUrl;

    public function __construct()
    {
        $this->tmnCode    = config('vnpay.tmn_code');
        $this->hashSecret = config('vnpay.hash_secret');
        $this->paymentUrl = config('vnpay.payment_url');
        $this->returnUrl  = config('vnpay.return_url');
    }

    /**
     * Tạo URL thanh toán VNPAY.
     */
    public function createPaymentUrl(Order $order, string $clientIp): string
    {
        $amount = (int)($order->final_amount * 100); // VND × 100

        // Chuẩn hóa IP (VNPAY không chấp nhận địa chỉ IPv6 ::1 của localhost)
        if ($clientIp === '::1' || !$clientIp) {
            $clientIp = '127.0.0.1';
        }

        $vnpData = [
            'vnp_Version'   => '2.1.0',
            'vnp_Command'   => 'pay',
            'vnp_TmnCode'   => $this->tmnCode,
            'vnp_Amount'    => $amount,
            'vnp_CurrCode'  => 'VND',
            'vnp_TxnRef'    => $order->order_code,
            'vnp_OrderInfo' => 'Thanh toan don hang ' . $order->order_code,
            'vnp_OrderType' => 'other',
            'vnp_Locale'    => 'vn',
            'vnp_ReturnUrl' => $this->returnUrl,
            'vnp_IpAddr'    => $clientIp,
            'vnp_CreateDate'=> now()->format('YmdHis'),
            'vnp_ExpireDate'=> now()->addMinutes(15)->format('YmdHis'),
        ];

        ksort($vnpData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($vnpData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        $hash = hash_hmac('sha512', $hashdata, $this->hashSecret);

        return $this->paymentUrl . '?' . $query . 'vnp_SecureHash=' . $hash;
    }

    /**
     * Xác minh chữ ký từ VNPAY return/IPN.
     */
    public function verifySignature(array $vnpData): bool
    {
        $secureHash = $vnpData['vnp_SecureHash'] ?? '';

        // Loại bỏ các tham số không dùng để hash
        unset($vnpData['vnp_SecureHash'], $vnpData['vnp_SecureHashType']);

        ksort($vnpData);
        $hashdata = "";
        $i = 0;
        foreach ($vnpData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        $expectedHash = hash_hmac('sha512', $hashdata, $this->hashSecret);

        return hash_equals($expectedHash, $secureHash);
    }

    /**
     * Kiểm tra giao dịch thành công (response code = "00").
     */
    public function isSuccess(array $vnpData): bool
    {
        return ($vnpData['vnp_ResponseCode'] ?? '') === '00';
    }
}
