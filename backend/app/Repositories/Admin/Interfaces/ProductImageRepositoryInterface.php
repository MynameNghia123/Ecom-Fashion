<?php 
namespace App\Repositories\Admin\Interfaces;
use App\Models\ProductImage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
/**
 * ProductImage Repository Interface — extends Base, thêm các method đặc thù của ProductImage.
 * Dùng return type cụ thể (ProductImage) thay vì Model chung.
 */
interface ProductImageRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function findById(int $id): ?ProductImage;

    /**
     * {@inheritdoc}
     *
     * @param array{ product_id: int, image_path: string } $data
     */
    public function create(array $data): ProductImage;

    /**
     * {@inheritdoc}
     *
     * @param array{ product_id: int, image_path: string } $data
     */
    public function update(\Illuminate\Database\Eloquent\Model $model, array $data): ProductImage;

    /**
     * {@inheritdoc}
     */
    public function delete(\Illuminate\Database\Eloquent\Model $model): void;
}
?>