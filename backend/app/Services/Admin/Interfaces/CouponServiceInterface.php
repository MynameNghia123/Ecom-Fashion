<?php

namespace App\Services\Admin\Interfaces;

use App\Models\Coupon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

/**
 * Base Service Interface — định nghĩa CRUD chuẩn cho mọi service.
 * Mọi service interface cụ thể đều extends interface này.
 */
interface CouponServiceInterface extends BaseServiceInterface
{
    /**
     * Lấy danh sách có filter + phân trang.
     *
     * @param  array<string, mixed>  $filters
     */
    public function getList(array $filters): LengthAwarePaginator;

    /**
     * Tạo bản ghi mới.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Coupon;

    /**
     * Cập nhật bản ghi.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Model $model, array $data): Coupon;

    /**
     * Xóa bản ghi.
     */
    public function delete(Model $model): void;

    public function getAll();
}
