<?php
namespace App\Services\Admin\Implements;

use App\Models\ReturnRequest;
use App\Repositories\Admin\Interfaces\ReturnRequestRepositoryInterface;
use App\Services\Admin\Interfaces\ReturnRequestServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Exception;

class ReturnRequestService implements ReturnRequestServiceInterface
{
    public function __construct(private readonly ReturnRequestRepositoryInterface $repository) {}

    public function getList(array $filters): LengthAwarePaginator {
        return $this->repository->paginate($filters);
    }

    public function getStats(): array {
        return $this->repository->getStats();
    }

    public function getDetail(int $id): ?ReturnRequest {
        return $this->repository->findByIdWithRelations($id);
    }

    public function createReturnRequest(array $data): ReturnRequest {
        $data['ticket_code'] = '#RET-' . strtoupper(Str::random(6));
        $returnRequest = $this->repository->create($data);
        $returnRequest->load(['order.customer', 'orderDetail.productVariant.attributeValues.attribute']);
        return $returnRequest;
    }

    public function updateStatus(ReturnRequest $model, array $data): ReturnRequest {
        $validTransitions = [
            'pending'  => ['approved', 'rejected'],
            'approved' => ['received'],
            'received' => ['refunded'],
        ];

        $allowed = $validTransitions[$model->status] ?? [];
        if (!in_array($data['status'], $allowed)) {
            throw new Exception("Không thể chuyển từ '{$model->status}' sang '{$data['status']}'.");
        }

        $data['processed_by_staff_id'] = auth()->id();
        $data['processed_at'] = now();

        return $this->repository->update($model, $data);
    }

    public function create(array $data): Model { return $this->createReturnRequest($data); }
    public function update(Model $model, array $data): Model { return $this->repository->update($model, $data); }
    public function delete(Model $model): void { $this->repository->delete($model); }
}
