<?php

namespace App\Services\Client\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ReturnRequestServiceInterface
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
     * Create a new return request based on user input and validate business rules
     *
     * @param int $customerId
     * @param array $data
     * @param array $images
     * @return Model
     * @throws \Exception
     */
    public function createReturnRequest(int $customerId, array $data, array $images = []): Model;
}
