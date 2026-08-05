<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Order\PlaceOrderRequest;
use App\Services\Client\Interfaces\OrderServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(private readonly OrderServiceInterface $orderService)
    {
    }

    /**
     * POST /client/orders
     */
    public function store(PlaceOrderRequest $request): JsonResponse
    {
        $customer = Auth::user();
        $validated = $request->validated();
        $clientIp = $request->ip();

        $result = $this->orderService->placeOrder($customer->id, $validated, $clientIp);

        if (!$result['success']) {
            $status = str_contains($result['message'], 'tồn kho') ? 422 : 500;
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], $status);
        }

        return response()->json($result);
    }

    /**
     * GET /client/orders — Lấy danh sách đơn hàng của khách hàng.
     */
    public function index(): JsonResponse
    {
        $customer = Auth::user();

        return response()->json([
            'success' => true,
            'data' => $this->orderService->getCustomerOrders($customer->id),
        ]);
    }

    /**
     * GET /client/orders/{code} — Xem chi tiết đơn hàng theo mã code.
     */
    public function show(string $code): JsonResponse
    {
        $customer = Auth::user();

        $order = $this->orderService->getCustomerOrderDetails($customer->id, $code);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }
}
