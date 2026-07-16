<?php 
namespace App\Services\Admin\Interfaces;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface StaffServiceInterface extends BaseServiceInterface
{
    public function getList(array $filters): LengthAwarePaginator;
    public function create(array $data): Staff;
    public function update(Model $model, array $data): Staff;
    public function delete(Model $model): void;
}