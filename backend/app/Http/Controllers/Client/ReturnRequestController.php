<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\ReturnRequest\StoreReturnRequestRequest;
use App\Services\Client\Interfaces\ReturnRequestServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReturnRequestController extends Controller
{
    public function __construct(
        private readonly ReturnRequestServiceInterface $service
    ) {}

    /**
     * Lấy danh sách các yêu cầu hoàn trả của Customer đang đăng nhập.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $perPage = (int) $request->query('per_page', 10);
        $returnRequests = $this->service->getCustomerReturnRequests($user->id, $perPage);

        return response()->json([
            'success' => true,
            'data' => $returnRequests->items(),
            'meta' => [
                'current_page' => $returnRequests->currentPage(),
                'per_page' => $returnRequests->perPage(),
                'total' => $returnRequests->total(),
                'last_page' => $returnRequests->lastPage(),
            ],
        ]);
    }

    /**
     * Lấy chi tiết 1 yêu cầu hoàn trả.
     */
    public function show(Request $request, $id): JsonResponse
    {
        $user = $request->user();

        $returnRequest = $this->service->getCustomerReturnRequestDetail($user->id, $id);

        if (! $returnRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy yêu cầu hoàn trả.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $returnRequest,
        ]);
    }

    /**
     * Gửi yêu cầu hoàn trả mới.
     */
    public function store(StoreReturnRequestRequest $request): JsonResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        $images = [];
        if ($request->hasFile('evidence_images')) {
            $images = $request->file('evidence_images');
        }

        try {
            $returnRequest = $this->service->createReturnRequest(
                $user->id,
                $validated,
                $images
            );

            return response()->json([
                'success' => true,
                'data' => $returnRequest,
                'message' => 'Gửi yêu cầu hoàn trả thành công. Chúng tôi sẽ sớm xử lý.',
            ], 201);
        } catch (\Exception $e) {
            $code = $e->getCode();
            // Nếu code không phải là số hoặc không nằm trong dải HTTP status hợp lệ, dùng 500
            if (!is_numeric($code) || $code < 100 || $code > 599) {
                $code = 500;
            }
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], (int) $code);
        }
    }
}
