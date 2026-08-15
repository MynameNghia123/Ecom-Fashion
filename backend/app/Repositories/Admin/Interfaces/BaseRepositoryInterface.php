<?php

namespace App\Repositories\Admin\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

/**
 * Base Repository Interface — định nghĩa CRUD chuẩn cho mọi repository.
 * Mọi repository interface cụ thể đều extends interface này.
 */
interface BaseRepositoryInterface
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
    public function findById(int $id): ?Model;

    /**
     * Tạo bản ghi mới.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Model;

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
}
