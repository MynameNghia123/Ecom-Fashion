<?php

namespace App\Services\Admin\Interfaces;
use App\Models\Customer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

/**
 * Customer Service Interface — extends BaseServiceInterface, thêm các method đặc thù của Customer.
 * Dùng return type cụ thể (Customer) thay vì Model chung.
 */
interface CustomerServiceInterface extends BaseServiceInterface
{
    /**
     * Lấy danh sách có filter + phân trang.
     *
     * @param array<string, mixed> $filters
     */
    public function getList(array $filters): LengthAwarePaginator;

    /**
     * Tạo bản ghi mới.
     *
     * @param array<string, mixed> $data
     */
    public function create(array $data): Customer;

    /**
     * Cập nhật bản ghi.
     *
     * @param array<string, mixed> $data
     */
    public function update(Model $model, array $data): Customer;

    /**
     * Xóa bản ghi.
     */
    public function delete(Model $model): void;
    public function getAll();
    public function getStats(): array;
}
