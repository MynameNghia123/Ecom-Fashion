<?php

namespace App\Services\Admin\Implements;

use App\Models\Staff;
use App\Repositories\Admin\Interfaces\StaffRepoInterface;
use App\Services\Admin\Interfaces\AuthServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


class AuthService implements AuthServiceInterface
{
    protected StaffRepoInterface $staffRepo;

    public function __construct(StaffRepoInterface $staffRepo)
    {
        $this->staffRepo = $staffRepo;
    }

    /**
     * @param array $credentials
     * @return array
     * @throws ValidationException
     */
    public function login(array $credentials): array
    {
        $staff = $this->staffRepo->findActiveByEmail($credentials['email']);

        if (! $staff) {
            throw ValidationException::withMessages([
                'email' => ['Tài khoản không tồn tại hoặc đã bị vô hiệu hóa.'],
            ]);
        }

        // 2. Kiểm tra mật khẩu (attempt tạo token)
        $token = Auth::guard('staff')->attempt($credentials);

        if (! $token) {
            throw ValidationException::withMessages([
                'password' => ['Mật khẩu không chính xác.'],
            ]);
        }

        // 3. Cập nhật thời gian đăng nhập
        $this->staffRepo->update($staff, ['last_login_at' => now()]);

        return [
            'token'       => $token,
            'staff'       => $staff,
            'permissions' => $this->getPermissions($staff),
        ];
    }

    public function logout(): void
    {
        try {
            Auth::guard('staff')->logout();
        } catch (\Throwable $e) {
            // Bỏ qua lỗi token hết hạn khi logout
        }
    }

    public function me(): array
    {
        $staff = Auth::guard('staff')->user();
        return [
            'staff'       => $staff,
            'permissions' => $staff ? $this->getPermissions($staff) : [],
        ];
    }

    /**
     * Lấy danh sách quyền hạn của staff (gộp từ roles + direct permissions).
     * Format: ["products:read", "products:create", "orders:read", ...]
     */
    public function getPermissions(Staff $staff): array
    {
        $staff->loadMissing([
            'StaffRoles.Role.RolePermissions.Permission',
            'StaffPermissions.Permission',
        ]);

        $permissions = new Collection();

        // Quyền từ các Role được gán cho staff
        foreach ($staff->StaffRoles as $staffRole) {
            $role = $staffRole->Role;
            if (! $role) continue;
            foreach ($role->RolePermissions as $rp) {
                $perm = $rp->Permission;
                if ($perm) {
                    $permissions->push($perm->module . ':' . $perm->action);
                }
            }
        }

        // Quyền trực tiếp gán cho staff (override)
        foreach ($staff->StaffPermissions as $sp) {
            $perm = $sp->Permission;
            if ($perm) {
                $permissions->push($perm->module . ':' . $perm->action);
            }
        }

        return $permissions->unique()->values()->toArray();
    }

    /**
     * @return array
     * @throws JWTException
     */
    public function refresh(): array
    {
        /** @var \Tymon\JWTAuth\JWTGuard $guard */
        $guard = Auth::guard('staff');

        $newToken = $guard->refresh();
        $staff    = $guard->user();

        return [
            'token'       => $newToken,
            'staff'       => $staff,
            'permissions' => $staff ? $this->getPermissions($staff) : [],
        ];
    }
}
