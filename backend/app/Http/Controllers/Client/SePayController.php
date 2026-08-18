<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\SePayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SePayController extends Controller
{
    public function __construct(private readonly SePayService $sePayService) {}

    /**
     * GET /api/client/sepay/info/{orderCode}
     * Trả về thông báo lỗi hoặc thông tin thanh toán QR từ Service.
     */
    public function paymentInfo(string $orderCode): JsonResponse
    {
        $result = $this->sePayService->getPaymentInfoData($orderCode);

        return response()->json($result['data'], $result['status']);
    }

    /**
     * GET /api/client/sepay/check/{orderCode}
     * Polling: kiểm tra trạng thái thanh toán từ Service.
     */
    public function checkStatus(string $orderCode): JsonResponse
    {
        $result = $this->sePayService->checkPaymentStatus($orderCode);

        return response()->json($result['data'], $result['status']);
    }

    /**
     * POST /api/sepay/webhook (public — no auth)
     * Webhook gọi từ SePay khi chuyển khoản được xác nhận.
     */
    public function webhook(Request $request): Response
    {
        $authHeader = $request->header('Authorization', '');
        
        $result = $this->sePayService->processWebhook($request->all(), $authHeader);

        // Webhook của SePay yêu cầu trả về status 200 hoặc 401
        // Nếu data là string thì trả về text/plain, nếu mảng thì trả json
        if (is_string($result['data'])) {
            return response($result['data'], $result['status']);
        }

        return response(json_encode($result['data']), $result['status'])
            ->header('Content-Type', 'application/json');
    }
}
