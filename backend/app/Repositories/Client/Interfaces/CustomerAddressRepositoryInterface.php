<?php

namespace App\Repositories\Client\Interfaces;

use App\Models\CustomerAddress;
use Illuminate\Database\Eloquent\Collection;

interface CustomerAddressRepositoryInterface
{
    public function getByCustomerId(int $customerId): Collection;

    public function findByIdAndCustomerId(int $id, int $customerId): ?CustomerAddress;

    public function countByCustomerId(int $customerId): int;

    public function resetDefaultByCustomerId(int $customerId): int;

    public function getOtherAddress(int $customerId, int $excludeId): ?CustomerAddress;

    public function getLatestAddress(int $customerId): ?CustomerAddress;

    public function create(array $data): CustomerAddress;

    public function update(CustomerAddress $address, array $data): bool;

    public function delete(CustomerAddress $address): bool;
}
