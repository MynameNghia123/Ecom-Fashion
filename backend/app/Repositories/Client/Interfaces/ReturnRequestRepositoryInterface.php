<?php

namespace App\Repositories\Client\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ReturnRequestRepositoryInterface
{
    /**
     * Get paginated return requests for a specific customer
     *
     * @param int $customerId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getCustomerReturnRequests(int $customerId, int $perPage = 10): LengthAwarePaginator;

    /**
     * Get a specific return request for a customer
     *
     * @param int $customerId
     * @param int $id
     * @return Model|null
     */
    public function getCustomerReturnRequestDetail(int $customerId, int $id): ?Model;

    /**
     * Find a return request by order detail id
     *
     * @param int $orderDetailId
     * @return Model|null
     */
    public function findByOrderDetailId(int $orderDetailId): ?Model;

    /**
     * Create a new return request
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model;
}
