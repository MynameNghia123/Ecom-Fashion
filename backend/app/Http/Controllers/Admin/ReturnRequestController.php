<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReturnRequest;
use App\Http\Requests\Admin\ReturnRequest\StoreReturnRequestRequest;
use App\Http\Requests\Admin\ReturnRequest\UpdateReturnRequestStatusRequest;
use App\Services\Admin\Interfaces\ReturnRequestServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReturnRequestController extends Controller
{
    public function __construct(
        private readonly ReturnRequestServiceInterface $service
    ) {}
    /** GET /admin/return-requests */
    public function index(Request $request): JsonResponse
    {
        $filters = [
            'status'   => $request->query('status'),
            'reason'   => $request->query('reason'),
            'search'   => $request->query('search'),
            'per_page' => (int) $request->query('per_page', 15),
        ];

        $paginator = $this->service->getList($filters);
        $items = array_map(fn($r) => $this->formatItem($r), $paginator->items());

        return response()->json([
            'success' => true,
            'data'    => $items,
            'meta'    => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
            ],
            'stats'   => $this->service->getStats(),
        ]);
    }

    /** GET /admin/return-requests/{id} */
    public function show(int $id): JsonResponse
    {
        $returnRequest = $this->service->getDetail($id);

        if (!$returnRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy yêu cầu đổi trả.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $this->formatItem($returnRequest, full: true),
        ]);
    }

    /** PATCH /admin/return-requests/{id}/status */
    public function updateStatus(UpdateReturnRequestStatusRequest $request, int $id): JsonResponse
    {
        $returnRequest = $this->service->getDetail($id);

        if (!$returnRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy yêu cầu đổi trả.',
            ], 404);
        }

        try {
            $updated = $this->service->updateStatus($returnRequest, $request->validated());

            return response()->json([
                'success' => true,
                'data'    => $this->formatItem($updated),
                'message' => 'Cập nhật trạng thái thành công.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /** POST /admin/return-requests (Tạo YC từ admin thay KH) */
    public function store(StoreReturnRequestRequest $request): JsonResponse
    {
        $returnRequest = $this->service->createReturnRequest($request->validated());

        return response()->json([
            'success' => true,
            'data'    => $this->formatItem($returnRequest),
            'message' => 'Tạo yêu cầu đổi trả thành công.',
        ], 201);
    }

    // ── Private helpers ──────────────────────────────────────────

    private function formatItem(ReturnRequest $r, bool $full = false): array
    {
        $customer = $r->order?->customer;
        $detail   = $r->orderDetail;
        $variant  = $detail?->productVariant;
        $product  = $variant?->product;

        // Trích xuất size/màu từ attribute values
        $size  = '';
        $color = '';
        if ($variant && $variant->attributeValues) {
            foreach ($variant->attributeValues as $av) {
                $attrName = strtolower($av->attribute?->name ?? '');
                if (str_contains($attrName, 'size') || str_contains($attrName, 'kích')) {
                    $size = $av->value;
                } elseif (str_contains($attrName, 'màu') || str_contains($attrName, 'color')) {
                    $color = $av->value;
                }
            }
        }

        // Thumbnail sản phẩm
        $thumbnail = '';
        if ($product) {
            $img = $product->productImages?->first();
            $raw = $img?->image_url ?? $img?->url ?? $product->thumbnail ?? '';
            $thumbnail = $raw ? (str_starts_with($raw, 'http') ? $raw : "http://localhost:8000/storage/{$raw}") : '';
        }

        $base = [
            'id'             => $r->id,
            'ticket_code'    => $r->ticket_code,
            'order_code'     => $r->order?->code ?? '',
            'customer_name'  => $customer?->full_name ?? 'N/A',
            'customer_phone' => $customer?->phone ?? '',
            'product_name'   => $product?->name ?? ($detail?->product_name ?? 'N/A'),
            'product_image'  => $thumbnail,
            'variant_size'   => $size ?: ($detail?->variant_info ?? ''),
            'variant_color'  => $color,
            'quantity'       => $r->quantity,
            'unit_price'     => $detail?->price ?? 0,
            'refund_amount'  => $r->refund_amount ?? 0,
            'reason'         => $r->reason,
            'customer_note'  => $r->customer_note ?? '',
            'status'         => $r->status,
            'admin_note'     => $r->admin_note ?? '',
            'created_at'     => $r->created_at?->format('d/m/Y H:i') ?? '',
        ];

        if ($full) {
            $base['customer_email']   = $customer?->email ?? '';
            $base['pickup_address']   = $customer?->address ?? '';
            $base['proof_images']     = $r->evidence_images ?? [];
            $base['processed_at']     = $r->processed_at?->format('d/m/Y H:i') ?? '';
        }

        return $base;
    }


}
