<?php
namespace App\Repositories\Admin\Implements;

use App\Models\ReturnRequest;
use App\Repositories\Admin\Interfaces\ReturnRequestRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class ReturnRequestRepository implements ReturnRequestRepositoryInterface
{
    public function __construct(private readonly ReturnRequest $model) {}

    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->with([
            'order.customer',
            'orderDetail.productVariant.attributeValues.attribute',
            'orderDetail.productVariant.product.productImages',
        ])->latest();

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['reason'])) {
            $query->where('reason', $filters['reason']);
        }

        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $query->where(function ($q) use ($s) {
                $q->where('ticket_code', 'like', "%{$s}%")
                  ->orWhereHas('order', fn($oq) => $oq->where('code', 'like', "%{$s}%"))
                  ->orWhereHas('order.customer', fn($cq) =>
                      $cq->where('full_name', 'like', "%{$s}%")
                         ->orWhere('phone', 'like', "%{$s}%")
                  );
            });
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }

    public function findByIdWithRelations(int $id): ?ReturnRequest
    {
        return $this->model->with([
            'order.customer',
            'orderDetail.productVariant.attributeValues.attribute',
            'orderDetail.productVariant.product.productImages',
            'processedBy',
        ])->find($id);
    }

    public function getStats(): array
    {
        $counts = $this->model->selectRaw('status, COUNT(*) as total')
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

    public function findById(int $id): ?ReturnRequest { return $this->model->find($id); }
    public function create(array $data): ReturnRequest { return $this->model->create($data); }
    public function update(Model $model, array $data): ReturnRequest { $model->update($data); return $model->fresh(); }
    public function delete(Model $model): void { $model->delete(); }
}
