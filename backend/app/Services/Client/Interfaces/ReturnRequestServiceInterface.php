<?php

namespace App\Services\Client\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface ReturnRequestServiceInterface
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
     * Create a new return request based on user input and validate business rules
     *
     * @throws \Exception
     */
    public function createReturnRequest(int $customerId, array $data, array $images = []): Model;
}
