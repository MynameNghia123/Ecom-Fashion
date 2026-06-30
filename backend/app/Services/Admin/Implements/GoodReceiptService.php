<?php
namespace App\Services\Admin\Implements;

use App\Services\Admin\Interfaces\GoodReceiptServiceInterface;
use App\Models\GoodReceipt;
use App\Repositories\Admin\Interfaces\GoodReceiptRepoInterface;
use App\Services\Admin\Interfaces\GoodReceiptDetailServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class GoodReceiptService implements GoodReceiptServiceInterface
{
    public function __construct(
        private readonly GoodReceiptRepoInterface $repo,
        private readonly GoodReceiptDetailServiceInterface $good_receipt_detail_service,
    ){}

    public function getList(array $filters): LengthAwarePaginator
    {
        return $this->repo->paginate($filters);
    }

    public function create(array $data): GoodReceipt
    {
        return DB::transaction(function () use ($data){
            $details = $data['good_receipt_details'] ?? [];

            unset($data['good_receipt_details']);

            $created = $this->repo->create($data);            
            if (!empty($details)){
                $this->good_receipt_detail_service->insertMany($details, $created->id);
            }

            return $created->load(['goodReceiptDetail']);
        });
    }

    public function update(Model $model, array $data): GoodReceipt
    {
        return DB::transaction(function () use($model, $data){
            $details = $data['good_receipt_details'] ?? [];

            unset($data['good_receipt_details']);

            $updated = $this->repo->update($model, $data);

            if (!empty($details)){
                $this->good_receipt_detail_service->syncDetail($model, $details);
            }

            return $updated->load(['goodReceiptDetail']);
        });
    }

    public function delete(Model $model): void
    {
        $this->repo->delete($model);
    }


}