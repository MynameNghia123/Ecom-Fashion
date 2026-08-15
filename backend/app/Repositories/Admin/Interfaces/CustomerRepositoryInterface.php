<?php

namespace App\Repositories\Admin\Interfaces;

use App\Models\Customer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

/**
 * Customer Repository Interface — extends BaseRepositoryInterface, thêm các method đặc thù của Customer.
 * Dùng return type cụ thể (Customer) thay vì Model chung.
 */
interface CustomerRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Lấy danh sách có filter + phân trang.
     *
     * @param  array<string, mixed>  $filters
     */
    public function paginate(array $filters): LengthAwarePaginator;

    /**
     * Tìm một bản ghi theo ID.
     */
    public function findById(int $id): ?Customer;

    /**
     * Tạo bản ghi mới.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Customer;

    /**
     * Cập nhật bản ghi và trả về dữ liệu mới nhất.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Model $model, array $data): Model;

    /**
     * Xóa bản ghi.
     */
    public function delete(Model $model): void;

    public function getAll();

    public function getStats(): array;
}
