<?php
namespace App\Repositories\Client\Interfaces;
use App\Models\Blog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BlogRepositoryInterface
{
    public function getActiveBlogs(array $filters, int $perPage): LengthAwarePaginator;
    public function findActiveBySlug(string $slug): ?Blog;
}
