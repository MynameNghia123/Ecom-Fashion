<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\Client\Interfaces\PaymentServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VNPayController extends Controller
{
    public function __construct(private readonly PaymentServiceInterface $paymentService) {}

    /**
     * GET /api/client/vnpay/return
     */
    public function verifyReturn(Request $request): JsonResponse
    {
        $result = $this->paymentService->verifyReturn($request->query());

        if (! $result['success']) {
            $status = isset($result['code']) && $result['code'] === 'ORDER_NOT_FOUND' ? 404 : 400;

            return response()->json($result, $status);
        }

        return response()->json($result);
    }

    /**
     * POST /api/client/vnpay/ipn
     */
    public function ipn(Request $request): Response
    {
        $result = $this->paymentService->handleIpn($request->query());

        return response(json_encode([
            'RspCode' => $result['rspCode'],
            'Message' => $result['message'],
        ]))->header('Content-Type', 'application/json');
    }
}
