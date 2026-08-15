<?php

namespace App\Repositories\Client\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface ReturnRequestRepositoryInterface
{
    /**
     * Get paginated return requests for a specific customer
     */
    public function getCustomerReturnRequests(int $customerId, int $perPage = 10): LengthAwarePaginator;

    /**
     * Get a specific return request for a customer
     */
    public function getCustomerReturnRequestDetail(int $customerId, int $id): ?Model;

    /**
     * Find a return request by order detail id
     */
    public function findByOrderDetailId(int $orderDetailId): ?Model;

    /**
     * Create a new return request
     */
    public function create(array $data): Model;
}
