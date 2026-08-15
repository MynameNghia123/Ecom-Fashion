<?php

namespace App\Services\Admin\Implements;

use App\Models\Staff;
use App\Repositories\Admin\Interfaces\StaffRepositoryInterface;
use App\Services\Admin\Interfaces\AuthServiceInterface;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        private readonly StaffRepositoryInterface $staffRepo
    ) {}

    public function login(array $credentials): array
    {
        $staff = $this->staffRepo->findByEmail($credentials['email']);

        if (! $staff || ! Hash::check($credentials['password'], $staff->password)) {
            return [
                'success' => false,
                'message' => 'Email hoặc mật khẩu không chính xác.',
                'status_code' => 401,
            ];
        }

        if (! $staff->is_active) {
            return [
                'success' => false,
                'message' => 'Tài khoản của bạn đã bị khóa.',
                'status_code' => 403,
            ];
        }

        // Tạo token mới
        $token = $staff->createToken('admin-token')->plainTextToken;

        // Cập nhật last_login_at
        $this->staffRepo->update($staff, [
            'last_login_at' => now(),
        ]);

        $user = $this->staffRepo->findById($staff->id);
        $user->load(['roles', 'permissions']);

        return [
            'success' => true,
            'message' => 'Đăng nhập thành công.',
            'token' => $token,
            'user' => $user,
        ];
    }

    public function logout(Staff $user): void
    {
        $user->currentAccessToken()->delete();
    }

    public function me(Staff $user): Staff
    {
        $staff = $this->staffRepo->findById($user->id);
        $staff->load(['roles', 'permissions']);

        return $staff;
    }
}
