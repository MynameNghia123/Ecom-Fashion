<?php

namespace App\Services\Admin\Implements;

use App\Models\GoodReceiptDetail;
use App\Repositories\Admin\Interfaces\GoodReceiptDetailRepoInterface;
use App\Services\Admin\Interfaces\GoodReceiptDetailServiceInterface;
use Illuminate\Database\Eloquent\Model;

class GoodReceiptDetailService implements GoodReceiptDetailServiceInterface
{
    public function __construct(
        private readonly GoodReceiptDetailRepoInterface $repo,
    ) {}

    public function create(array $data): GoodReceiptDetail
    {
        return $this->repo->create($data);
    }

    public function update(Model $model, array $data): GoodReceiptDetail
    {
        return $this->repo->update($model, $data);
    }

    public function delete(Model $model): void
    {
        $this->repo->delete($model);
    }

    public function insertMany($data, $goods_receipt_id): void
    {
        if (empty($data)) {
            return;
        }

        $prepare = array_map(function ($detail) use ($goods_receipt_id) {
            $detail['goods_receipt_id'] = $goods_receipt_id;

            return $detail;
        }, $data);
        $this->repo->insertMany($prepare);
    }

    public function syncDetail(Model $model, array $details): void
    {
        $existingDetails = $model->goodReceiptDetail->keyBy('id');
        $keptDetail = [];

        foreach ($details as $detail) {
            $detailId = $detail['id'] ?? null;
            unset($detail['id']);

            if ($detailId && $existingDetails->has($detailId)) {
                $detailModel = $existingDetails->get($detailId);
                $this->repo->update($detailModel, $detail);
                $keptDetail[] = $detailId;
            } else {
                $detail['goods_receipt_id'] = $model->id;
                $this->repo->create($detail);
                $keptDetail[] = $detailId;
            }
        }

        foreach ($existingDetails as $existingDetail) {
            if (! in_array($existingDetail->id, $keptDetail)) {
                $this->repo->delete($existingDetail);
            }
        }

    }
}
