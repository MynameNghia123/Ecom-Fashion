<?php

namespace App\Services\Client\Interfaces;

use App\Models\Blog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BlogServiceInterface
{
    public function getActiveBlogs(array $filters, int $perPage): LengthAwarePaginator;

    public function findActiveBySlug(string $slug): ?Blog;
}
