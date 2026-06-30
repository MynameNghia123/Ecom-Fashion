<?php 
namespace App\Services\Admin\Interfaces;

use App\Models\GoodReceipt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface GoodReceiptServiceInterface extends BaseServiceInterface
{
    public function getList(array $filters): LengthAwarePaginator;
    public function create(array $data): GoodReceipt;
    public function update(Model $model, array $data): GoodReceipt;
    public function delete(Model $model): void;
}