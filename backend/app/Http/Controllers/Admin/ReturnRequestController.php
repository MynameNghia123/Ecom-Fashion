<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReturnRequest\StoreReturnRequestRequest;
use App\Http\Requests\Admin\ReturnRequest\UpdateReturnRequestStatusRequest;
use App\Models\ReturnRequest;
use App\Services\Admin\Interfaces\ReturnRequestServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReturnRequestController extends Controller
{
    public function __construct(
        private readonly ReturnRequestServiceInterface $service
    ) {}

    /** GET /admin/return-requests */
    public function index(Request $request): JsonResponse
    {
        $filters = [
            'status' => $request->query('status'),
            'reason' => $request->query('reason'),
            'search' => $request->query('search'),
            'per_page' => (int) $request->query('per_page', 15),
        ];

        $paginator = $this->service->getList($filters);
        $items = array_map(fn ($r) => $this->formatItem($r), $paginator->items());

        return response()->json([
            'success' => true,
            'data' => $items,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
            'stats' => $this->service->getStats(),
        ]);
    }

    /** GET /admin/return-requests/{id} */
    public function show(int $id): JsonResponse
    {
        $returnRequest = $this->service->getDetail($id);

        if (! $returnRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy yêu cầu đổi trả.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatItem($returnRequest, full: true),
        ]);
    }

    /** PATCH /admin/return-requests/{id}/status */
    public function updateStatus(UpdateReturnRequestStatusRequest $request, int $id): JsonResponse
    {
        $returnRequest = $this->service->getDetail($id);

        if (! $returnRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy yêu cầu đổi trả.',
            ], 404);
        }

        try {
            $updated = $this->service->updateStatus($returnRequest, $request->validated());

            return response()->json([
                'success' => true,
                'data' => $this->formatItem($updated),
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
            'data' => $this->formatItem($returnRequest),
            'message' => 'Tạo yêu cầu đổi trả thành công.',
        ], 201);
    }

    // ── Private helpers ──────────────────────────────────────────

    private function formatItem(ReturnRequest $r, bool $full = false): array
    {
        $order = $r->order;
        $customer = $order?->customer;
        $detail = $r->orderDetail;
        $variant = $detail?->productVariant;
        $product = $variant?->product;

        // Trích xuất size/màu từ attribute values
        $size = '';
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
            $raw = $variant?->thumbnail ?? $product->thumbnail ?? $img?->image_url ?? $img?->url ?? '';
            $thumbnail = $raw ? (str_starts_with($raw, 'http') ? $raw : "http://localhost:8000/storage/{$raw}") : '';
        }

        $custName = trim(($customer?->last_name ?? '') . ' ' . ($customer?->first_name ?? ''));
        if (! $custName && $order?->shipping_name) {
            $custName = $order->shipping_name;
        }

        $unitPrice = (float) ($detail?->unit_price ?? 0);
        $qty = (int) ($r->quantity ?? 1);
        $refundAmount = (float) ($r->refund_amount ?? 0);
        if ($refundAmount <= 0 && $unitPrice > 0) {
            $refundAmount = $unitPrice * $qty;
        }

        $evidenceImages = array_map(function ($path) {
            if (str_starts_with($path, 'http')) {
                return $path;
            }
            return "http://localhost:8000/storage/{$path}";
        }, $r->evidence_images ?? []);

        $base = [
            'id' => $r->id,
            'ticket_code' => $r->ticket_code ?? ('#RET-'.str_pad($r->id, 4, '0', STR_PAD_LEFT)),
            'order_code' => $order?->order_code ?? '',
            'customer_name' => $custName ?: 'N/A',
            'customer_phone' => $customer?->phone_number ?? ($order?->shipping_phone ?? ''),
            'product_name' => $product?->name ?? 'Sản phẩm',
            'product_image' => $thumbnail,
            'variant_size' => $size,
            'variant_color' => $color,
            'quantity' => $qty,
            'unit_price' => $unitPrice,
            'refund_amount' => $refundAmount,
            'reason' => $r->reason,
            'customer_note' => $r->customer_note ?? '',
            'status' => $r->status,
            'admin_note' => $r->admin_note ?? '',
            'created_at' => $r->created_at?->format('d/m/Y H:i') ?? '',
            'proof_images' => $evidenceImages,
        ];

        if ($full) {
            $base['customer_email'] = $customer?->email ?? '';
            $base['pickup_address'] = $order?->shipping_address ?? '';
            $base['processed_at'] = $r->processed_at?->format('d/m/Y H:i') ?? '';
        }

        return $base;
    }
}
