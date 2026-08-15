<?php

namespace App\Services\Admin\Interfaces;

use App\Models\GoodReceiptDetail;
use Illuminate\Database\Eloquent\Model;

interface GoodReceiptDetailServiceInterface
{
    public function create(array $data): GoodReceiptDetail;

    public function update(Model $model, array $data): GoodReceiptDetail;

    public function delete(Model $model): void;

    public function insertMany(array $data, int $good_receipt_id): void;

    public function syncDetail(Model $model, array $data): void;
}
