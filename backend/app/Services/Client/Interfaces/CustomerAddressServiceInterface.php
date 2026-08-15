<?php

namespace App\Services\Client\Interfaces;

use App\Models\CustomerAddress;
use Illuminate\Database\Eloquent\Collection;

interface CustomerAddressServiceInterface
{
    public function getAddresses(int $customerId): Collection;

    /**
     * @return array{success: bool, message: string, data?: CustomerAddress}
     */
    public function addAddress(int $customerId, array $data): array;

    /**
     * @return array{success: bool, message: string, data?: CustomerAddress}
     */
    public function updateAddress(int $customerId, int $id, array $data): array;

    /**
     * @return array{success: bool, message: string}
     */
    public function deleteAddress(int $customerId, int $id): array;

    /**
     * @return array{success: bool, message: string, data?: CustomerAddress}
     */
    public function setDefaultAddress(int $customerId, int $id): array;
}
