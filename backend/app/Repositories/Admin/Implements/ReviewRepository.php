<?php
namespace App\Repositories\Admin\Implements;

use App\Models\Review;
use App\Repositories\Admin\Interfaces\ReviewRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class ReviewRepository implements ReviewRepositoryInterface
{
    public function __construct(private readonly Review $model) {}

    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->model->with([
            'customer' => function ($q) {
                $q->select('id', 'first_name', 'last_name', 'email');
            },
            'product' => function ($q) {
                $q->select('id', 'name', 'thumbnail');
            },
            'orderDetail.productVariant.attributeValues.attribute'
        ]);

        if (isset($filters['rating']) && $filters['rating'] !== '') {
            $query->where('rating', (int) $filters['rating']);
        }

        if (!empty($filters['search'])) {
            $search = '%' . $filters['search'] . '%';
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'like', $search)
                  ->orWhereHas('product', function ($pq) use ($search) {
                      $pq->where('name', 'like', $search);
                  })
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('first_name', 'like', $search)
                        ->orWhere('last_name', 'like', $search);
                  });
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate($filters['per_page'] ?? 10);
    }

    public function getStats(): array
    {
        $totalReviews = $this->model->count();
        $averageRating = $this->model->avg('rating') ?: 0;
        
        $starStats = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = $this->model->where('rating', $i)->count();
            $starStats[$i] = [
                'count' => $count,
                'percentage' => $totalReviews > 0 ? round(($count / $totalReviews) * 100) . '%' : '0%'
            ];
        }

        return [
            'total'      => $totalReviews,
            'average'    => round($averageRating, 1),
            'star_stats' => $starStats
        ];
    }

    public function findById(int $id): ?Review { return $this->model->find($id); }
    public function create(array $data): Review { return $this->model->create($data); }
    public function update(Model $model, array $data): Review { $model->update($data); return $model->fresh(); }
    public function delete(Model $model): void { $model->delete(); }
}
