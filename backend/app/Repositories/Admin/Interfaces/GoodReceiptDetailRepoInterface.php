<?php
namespace App\Repositories\Admin\Interfaces;

use App\Models\GoodReceiptDetail;
use Illuminate\Database\Eloquent\Model;

interface GoodReceiptDetailRepoInterface 
{
    public function create(array $data): GoodReceiptDetail;
    public function update(Model $model, array $data): GoodReceiptDetail;
    public function delete(Model $model): void;
    public function insertMany($data): bool;
}