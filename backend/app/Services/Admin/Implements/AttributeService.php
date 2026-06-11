<?php

namespace App\Services\Admin\Implements;

use App\Models\Attribute;
use App\Repositories\Admin\Interfaces\AttributeRepositoryInterface;
use App\Services\Admin\Interfaces\AttributeServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class AttributeService implements AttributeServiceInterface
{
    /**
     * Service phụ thuộc vào Repository Interface (không phụ thuộc implementation).
     * → Có thể mock Repository khi unit test.
     */
    public function __construct(
        private readonly AttributeRepositoryInterface $attributeRepository
    ) {}

    /**
     * Lấy danh sách thuộc tính có filter + phân trang.
     *
     * @param array{ search?: string, per_page?: int } $filters
     */
    public function getList(array $filters): LengthAwarePaginator
    {
        return $this->attributeRepository->paginate($filters);
    }

    /**
     * Tạo thuộc tính mới.
     *
     * @param array{ name: string } $data
     */
    public function create(array $data): Attribute
    {
        return $this->attributeRepository->create($data);
    }

    /**
     * Cập nhật thuộc tính.
     *
     * @param array{ name: string } $data
     */
    public function update(Model $model, array $data): Attribute
    {
        return $this->attributeRepository->update($model, $data);
    }

    /**
     * Xóa thuộc tính.
     */
    public function delete(Model $model): void
    {
        $this->attributeRepository->delete($model);
    }
}
