<?php
namespace App\Services\Client\Implements;
use App\Models\Blog;
use App\Repositories\Client\Interfaces\BlogRepositoryInterface;
use App\Services\Client\Interfaces\BlogServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BlogService implements BlogServiceInterface
{
    public function __construct(private readonly BlogRepositoryInterface $repo) {}

    public function getActiveBlogs(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->repo->getActiveBlogs($filters, $perPage);
    }

    public function findActiveBySlug(string $slug): ?Blog
    {
        return $this->repo->findActiveBySlug($slug);
    }
}
