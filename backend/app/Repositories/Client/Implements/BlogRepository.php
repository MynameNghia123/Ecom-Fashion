<?php

namespace App\Repositories\Client\Implements;

use App\Models\Blog;
use App\Repositories\Client\Interfaces\BlogRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BlogRepository implements BlogRepositoryInterface
{
    public function __construct(private readonly Blog $model) {}

    public function getActiveBlogs(array $filters, int $perPage): LengthAwarePaginator
    {
        $query = $this->model->where('status', true);

        if (! empty($filters['search'])) {
            $query->where('name', 'like', '%'.$filters['search'].'%');
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    public function findActiveBySlug(string $slug): ?Blog
    {
        return $this->model->where('slug', $slug)->where('status', true)->first();
    }
}
