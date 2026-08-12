<?php
namespace App\Services\Admin\Implements;

use App\Models\ReturnRequest;
use App\Repositories\Admin\Interfaces\ReturnRequestRepositoryInterface;
use App\Services\Admin\Interfaces\ReturnRequestServiceInterface;
use App\Services\Client\Interfaces\NotificationServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Exception;

class ReturnRequestService implements ReturnRequestServiceInterface
{
    public function __construct(
        private readonly ReturnRequestRepositoryInterface $repository,
        private readonly NotificationServiceInterface $notificationService
    ) {}

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

        $data['processed_by_staff_id'] = Auth::id();
        $data['processed_at'] = now();

        $updatedModel = $this->repository->update($model, $data);

        // KHÔNG CỘNG LẠI TỒN KHO vì hàng lỗi/hỏng không bán lại được
        
        if ($data['status'] === 'refunded') {
            $updatedModel->load('order');
            if ($updatedModel->order && clone $updatedModel->order) {
                 // Nếu đơn hoàn toàn, có thể set payment_status = refunded. 
                 // Tùy theo nghiệp vụ (hoàn 1 phần hay toàn phần). Ở đây set cho order.
                 $updatedModel->order->update(['payment_status' => 'refunded']);
            }
        }
        
        $updatedModel->load('order');
        if ($updatedModel->order && $updatedModel->order->customer_id) {
            $statusMap = [
                'approved' => 'Đã được duyệt',
                'rejected' => 'Đã bị từ chối',
                'received' => 'Đã nhận được hàng hoàn',
                'refunded' => 'Đã hoàn tiền',
            ];
            
            if (isset($statusMap[$data['status']])) {
                $this->notificationService->notify(
                    $updatedModel->order->customer_id,
                    'return_request_updated',
                    "Yêu cầu hoàn trả {$updatedModel->ticket_code} " . strtolower($statusMap[$data['status']]),
                    "Trạng thái yêu cầu hoàn trả {$updatedModel->ticket_code} của bạn đã được cập nhật thành: {$statusMap[$data['status']]}."
                );
            }
        }

        return $updatedModel;
    }

    public function create(array $data): Model { return $this->createReturnRequest($data); }
    public function update(Model $model, array $data): Model { return $this->repository->update($model, $data); }
    public function delete(Model $model): void { $this->repository->delete($model); }
}
