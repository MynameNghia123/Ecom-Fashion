<?php

namespace App\Repositories\Admin\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use App\Models\Coupon;

/**
 * Coupon Repository Interface — định nghĩa CRUD chuẩn cho mọi repository.
 * Mọi repository interface cụ thể đều extends interface này.
 */
interface CouponRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Lấy danh sách có filter + phân trang.
     *
     * @param array<string, mixed> $filters
     */
    public function paginate(array $filters): LengthAwarePaginator;

    /**
     * Tìm một bản ghi theo ID.
     */
    public function findById(int $id): ?Coupon;

    /**
     * Tạo bản ghi mới.
     *
     * @param array<string, mixed> $data
     */
    public function create(array $data): Coupon;

    /**
     * Cập nhật bản ghi và trả về dữ liệu mới nhất.
     *
     * @param array<string, mixed> $data
     */
    public function update(Model $model, array $data): Coupon;

    /**
     * Xóa bản ghi.
     */
    public function delete(Model $model): void;
    public function getAll();
    public function findByCode(string $code): ?Coupon;
}
