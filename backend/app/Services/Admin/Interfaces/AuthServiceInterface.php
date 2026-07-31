<?php

namespace App\Services\Admin\Interfaces;

use App\Models\Staff;

interface AuthServiceInterface
{
    /**
     * @param array $credentials
     * @return array
     */
    public function login(array $credentials): array;

    /**
     * @return void
     */
    public function logout(): void;

    /**
     * @return array
     */
    public function me(): array;

    /**
     * @return array
     */
    public function refresh(): array;

    /**
     * @param \App\Models\Staff $staff
     * @return array
     */
    public function getPermissions(\App\Models\Staff $staff): array;
}
