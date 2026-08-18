<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\Client\Interfaces\OrderRepositoryInterface;
use Illuminate\Support\Facades\Log;

class SePayService
{
    private string $bankAccount;

    private string $bankName;

    private string $secretKey;

    public function __construct(private readonly OrderRepositoryInterface $orderRepository)
    {
        $this->bankAccount = config('services.sepay.bank_account', '');
        $this->bankName = config('services.sepay.bank_name', 'MBBank');
        $this->secretKey = config('services.sepay.secret_key', '');
    }

    /**
     * Lấy dữ liệu thanh toán cho API /sepay/info/{orderCode}
     */
    public function getPaymentInfoData(string $orderCode): array
    {
        $order = $this->orderRepository->getOrderByCode($orderCode);

        if (! $order || $order->payment_method !== 'sepay') {
            return ['status' => 404, 'data' => ['success' => false, 'message' => 'Không tìm thấy đơn hàng SePay.']];
        }

        if ($order->payment_status === 'paid') {
            return [
                'status' => 200,
                'data' => [
                    'success' => true,
                    'paid' => true,
                    'message' => 'Đơn hàng đã được thanh toán.',
                    'data' => $this->createPaymentInfo($order),
                ]
            ];
        }

        return [
            'status' => 200,
            'data' => [
                'success' => true,
                'paid' => false,
                'data' => $this->createPaymentInfo($order),
            ]
        ];
    }

    /**
     * Kiểm tra trạng thái thanh toán cho API /sepay/check/{orderCode}
     */
    public function checkPaymentStatus(string $orderCode): array
    {
        $order = $this->orderRepository->getOrderByCode($orderCode);

        if (! $order) {
            return ['status' => 404, 'data' => ['success' => false, 'message' => 'Không tìm thấy đơn hàng.']];
        }

        return [
            'status' => 200,
            'data' => [
                'success' => true,
                'paid' => $order->payment_status === 'paid',
                'payment_status' => $order->payment_status,
                'order_status' => $order->status,
            ]
        ];
    }

    /**
     * Xử lý webhook từ SePay
     */
    public function processWebhook(array $payload, string $authHeader): array
    {
        if (! $this->verifyWebhookSignature($authHeader)) {
            Log::warning('[SEPAY] Invalid webhook signature', ['auth' => $authHeader]);
            return ['status' => 401, 'data' => 'Unauthorized'];
        }

        Log::info('[SEPAY] Webhook received', $payload);

        $orderCode = $this->extractOrderCodeFromWebhook($payload);

        if (! $orderCode) {
            return ['status' => 200, 'data' => ['success' => false, 'message' => 'Cannot parse order code']];
        }

        $order = $this->orderRepository->getOrderByCode($orderCode);

        if (! $order) {
            Log::warning('[SEPAY] Order not found for webhook', ['order_code' => $orderCode]);
            return ['status' => 200, 'data' => ['success' => false, 'message' => 'Order not found']];
        }

        if ($order->payment_status !== 'paid') {
            $this->orderRepository->updateOrder($order, [
                'payment_status' => 'paid',
                'status' => 'confirmed',
            ]);

            Log::info('[SEPAY] Order marked as paid', ['order_code' => $orderCode, 'amount' => $payload['transferAmount'] ?? 0]);
        }

        return ['status' => 200, 'data' => ['success' => true, 'message' => 'OK']];
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
    private function verifyWebhookSignature(string $authHeader): bool
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
    private function extractOrderCodeFromWebhook(array $payload): ?string
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
