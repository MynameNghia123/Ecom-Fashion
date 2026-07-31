<?php
namespace App\Repositories\Admin\Interfaces;
use App\Models\ReturnRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ReturnRequestRepositoryInterface extends BaseRepositoryInterface
{
    public function paginate(array $filters): LengthAwarePaginator;
    public function findByIdWithRelations(int $id): ?ReturnRequest;
    public function getStats(): array;
}
