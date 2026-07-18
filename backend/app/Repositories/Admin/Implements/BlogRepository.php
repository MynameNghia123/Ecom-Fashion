<?php

namespace App\Repositories\Admin\Implements;

use App\Models\Blog;
use App\Repositories\Admin\Interfaces\BlogRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class BlogRepository implements BlogRepositoryInterface
{
    public function __construct(
        private readonly Blog $model
    ) {}

    /**
     * Truy vấn danh sách có filter + phân trang.
     */
    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->query();

        if (!empty($filters['search'])) {
            $query->where('name', 'like', "%{$filters['search']}%");
        }

        return $query->orderBy('id', 'desc')->paginate($filters['per_page'] ?? 4);
    }

    /**
     * Tìm theo ID.
     */
    public function findById(int $id): ?Blog
    {
        return $this->model->find($id);
    }

    /**
     * INSERT bản ghi mới.
     */
    public function create(array $data): Blog
    {
        Log::info('Thông tin người dùng đang lấy:', ['id' => $data]);
        return $this->model->create($data);
    }

    /**
     * UPDATE bản ghi, trả về dữ liệu mới nhất từ DB.
     */
    public function update(Model $model, array $data): Blog
    {
        $update = $model->update($data);

        return $model->fresh();
    }

    /**
     * DELETE bản ghi.
     */
    public function delete(Model $model): void
    {
        $model->delete();
    }
}
