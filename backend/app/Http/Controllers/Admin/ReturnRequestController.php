<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReturnRequest;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReturnRequestController extends Controller
{
    /** GET /admin/return-requests */
    public function index(Request $request): JsonResponse
    {
        $query = ReturnRequest::with([
            'order.customer',
            'orderDetail.productVariant.attributeValues.attribute',
            'orderDetail.productVariant.product.productImages',
        ])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('reason')) {
            $query->where('reason', $request->reason);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('ticket_code', 'like', "%{$s}%")
                  ->orWhereHas('order', fn($oq) => $oq->where('code', 'like', "%{$s}%"))
                  ->orWhereHas('order.customer', fn($cq) =>
                      $cq->where('full_name', 'like', "%{$s}%")
                         ->orWhere('phone', 'like', "%{$s}%")
                  );
            });
        }

        $perPage = (int) $request->query('per_page', 15);
        $paginator = $query->paginate($perPage);

        $items = $paginator->map(fn($r) => $this->formatItem($r));

        return response()->json([
            'success' => true,
            'data'    => $items,
            'meta'    => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
            ],
            'stats'   => $this->buildStats(),
        ]);
    }

    /** GET /admin/return-requests/{id} */
    public function show(ReturnRequest $returnRequest): JsonResponse
    {
        $returnRequest->load([
            'order.customer',
            'orderDetail.productVariant.attributeValues.attribute',
            'orderDetail.productVariant.product.productImages',
            'processedBy',
        ]);

        return response()->json([
            'success' => true,
            'data'    => $this->formatItem($returnRequest, full: true),
        ]);
    }

    /** PATCH /admin/return-requests/{id}/status */
    public function updateStatus(Request $request, ReturnRequest $returnRequest): JsonResponse
    {
        $request->validate([
            'status'     => 'required|in:approved,received,refunded,rejected',
            'admin_note' => 'nullable|string|max:1000',
        ]);

        // Kiểm tra luồng hợp lệ
        $validTransitions = [
            'pending'  => ['approved', 'rejected'],
            'approved' => ['received'],
            'received' => ['refunded'],
        ];

        $allowed = $validTransitions[$returnRequest->status] ?? [];
        if (!in_array($request->status, $allowed)) {
            return response()->json([
                'success' => false,
                'message' => "Không thể chuyển từ '{$returnRequest->status}' sang '{$request->status}'.",
            ], 422);
        }

        $returnRequest->update([
            'status'                 => $request->status,
            'admin_note'             => $request->admin_note,
            'processed_by_staff_id'  => auth()->id(),
            'processed_at'           => now(),
        ]);

        return response()->json([
            'success' => true,
            'data'    => $this->formatItem($returnRequest),
            'message' => 'Cập nhật trạng thái thành công.',
        ]);
    }

    /** POST /admin/return-requests (Tạo YC từ admin thay KH) */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_id'        => 'required|exists:orders,id',
            'order_detail_id' => 'nullable|exists:order_details,id',
            'reason'          => 'required|in:defective,wrong_size,wrong_item,change_mind,other',
            'customer_note'   => 'nullable|string|max:2000',
            'quantity'        => 'required|integer|min:1',
            'refund_amount'   => 'required|numeric|min:0',
            'evidence_images' => 'nullable|array',
        ]);

        $validated['ticket_code'] = '#RET-' . strtoupper(Str::random(6));

        $returnRequest = ReturnRequest::create($validated);
        $returnRequest->load(['order.customer', 'orderDetail.productVariant.attributeValues.attribute']);

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

    private function buildStats(): array
    {
        $counts = ReturnRequest::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return [
            'total'    => array_sum($counts),
            'pending'  => $counts['pending']  ?? 0,
            'approved' => $counts['approved'] ?? 0,
            'received' => $counts['received'] ?? 0,
            'refunded' => $counts['refunded'] ?? 0,
            'rejected' => $counts['rejected'] ?? 0,
        ];
    }
}
