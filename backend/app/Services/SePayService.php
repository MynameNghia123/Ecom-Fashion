<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class SePayService
{
    private string $bankAccount;

    private string $bankName;

    private string $secretKey;

    public function __construct()
    {
        $this->bankAccount = config('services.sepay.bank_account', '');
        $this->bankName = config('services.sepay.bank_name', 'MBBank');
        $this->secretKey = config('services.sepay.secret_key', '');
    }

    /**
     * Tạo thông tin thanh toán chuyển khoản SePay cho đơn hàng.
     * Trả về mảng chứa thông tin QR code & hướng dẫn chuyển khoản.
     */
    public function createPaymentInfo(Order $order): array
    {
        // Nội dung chuyển khoản = mã đơn hàng (không có khoảng trắng)
        $transferContent = str_replace('-', '', $order->order_code);

        // VietQR deep link (MB Bank, Vietcombank, etc.)
        // Format: https://img.vietqr.io/image/{bank}-{account}-{template}.png?amount={amount}&addInfo={content}
        $bankBin = $this->getBankBin($this->bankName);
        $qrUrl = "https://img.vietqr.io/image/{$bankBin}-{$this->bankAccount}-compact2.png"
            ."?amount={$order->final_amount}"
            .'&addInfo='.urlencode($transferContent)
            .'&accountName='.urlencode('ECOM FASHION');

        return [
            'bank_name' => $this->bankName,
            'bank_account' => $this->bankAccount,
            'amount' => $order->final_amount,
            'transfer_content' => $transferContent,
            'qr_url' => $qrUrl,
            'order_code' => $order->order_code,
            'expires_at' => now()->addMinutes(30)->toIso8601String(),
        ];
    }

    /**
     * Xác thực webhook từ SePay khi thanh toán được xác nhận.
     * SePay gửi POST với header Authorization: Apikey {secret}
     */
    public function verifyWebhookSignature(string $authHeader): bool
    {
        if (empty($this->secretKey)) {
            return true; // Dev mode: skip validation
        }

        return $authHeader === 'Apikey '.$this->secretKey;
    }

    /**
     * Xử lý webhook payload từ SePay.
     * Trả về order_code được tìm thấy từ nội dung chuyển khoản.
     */
    public function extractOrderCodeFromWebhook(array $payload): ?string
    {
        // SePay gửi: content (nội dung chuyển khoản), transferAmount
        $content = $payload['content'] ?? $payload['description'] ?? '';

        // Tìm mã đơn hàng (bắt đầu bằng ORD hoặc sau dấu cách)
        if (preg_match('/ORD[A-Z0-9]+/i', strtoupper(str_replace(['-', ' '], '', $content)), $matches)) {
            // Khôi phục format ORD-XXXXXXXX
            $raw = strtoupper($matches[0]);
            // raw: ORDXXXXXXXX -> ORD-XXXXXXXX
            if (strpos($raw, '-') === false && strlen($raw) > 3) {
                return 'ORD-'.substr($raw, 3);
            }

            return $raw;
        }

        Log::warning('[SEPAY] Cannot extract order code from content', ['content' => $content, 'payload' => $payload]);

        return null;
    }

    /**
     * Lấy BIN (mã ngân hàng) cho VietQR theo tên ngân hàng.
     */
    private function getBankBin(string $bankName): string
    {
        $map = [
            'mbbank' => 'MB',
            'mb' => 'MB',
            'vietcombank' => 'VCB',
            'vcb' => 'VCB',
            'techcombank' => 'TCB',
            'tcb' => 'TCB',
            'acb' => 'ACB',
            'bidv' => 'BIDV',
            'vietinbank' => 'ICB',
            'tpbank' => 'TPB',
            'vpbank' => 'VPB',
            'agribank' => 'AGR',
        ];

        $key = strtolower(str_replace(' ', '', $bankName));

        return $map[$key] ?? strtoupper($bankName);
    }
}
