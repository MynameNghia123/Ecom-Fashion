<?php

namespace App\Repositories\Client\Implements;

use App\Models\ReturnRequest;
use App\Repositories\Client\Interfaces\ReturnRequestRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class ReturnRequestRepository implements ReturnRequestRepositoryInterface
{
    public function getCustomerReturnRequests(int $customerId, int $perPage = 10): LengthAwarePaginator
    {
        return ReturnRequest::whereHas('order', function ($query) use ($customerId) {
            $query->where('customer_id', $customerId);
        })
            ->with(['order', 'orderDetail.productVariant.product', 'orderDetail.productVariant.attributeValues.attribute'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getCustomerReturnRequestDetail(int $customerId, int $id): ?Model
    {
        return ReturnRequest::whereHas('order', function ($query) use ($customerId) {
            $query->where('customer_id', $customerId);
        })
            ->with(['order', 'orderDetail.productVariant.product', 'orderDetail.productVariant.attributeValues.attribute'])
            ->where('id', $id)
            ->first();
    }

    public function findByOrderDetailId(int $orderDetailId): ?Model
    {
        return ReturnRequest::where('order_detail_id', $orderDetailId)->first();
    }

    public function create(array $data): Model
    {
        return ReturnRequest::create($data);
    }
}
