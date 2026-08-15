<?php

namespace App\Services\Admin\Interfaces;

use App\Models\Staff;

interface AuthServiceInterface
{
    public function login(array $credentials): array;

    public function logout(Staff $user): void;

    public function me(Staff $user): Staff;
}
