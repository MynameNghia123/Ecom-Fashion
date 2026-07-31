<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\Interfaces\AuthServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthServiceInterface $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    /**
     * POST /api/admin/auth/login
     * Đăng nhập và trả về JWT token
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $result = $this->authService->login($credentials);

        return $this->respondWithToken($result['token'], $result['staff'], $result['permissions']);
    }

    /**
     * POST /api/admin/auth/logout
     * Invalidate JWT token hiện tại
     */
    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return response()->json([
            'success' => true,
            'message' => 'Đăng xuất thành công.',
        ]);
    }

    /**
     * GET /api/admin/auth/me
     * Lấy thông tin staff đang đăng nhập từ token
     */
    public function me(): JsonResponse
    {
        $result = $this->authService->me();

        return response()->json([
            'success'     => true,
            'data'        => $this->staffData($result['staff']),
            'permissions' => $result['permissions'],
        ]);
    }

    /**
     * POST /api/admin/auth/refresh
     * Làm mới token sắp hết hạn
     */
    public function refresh(): JsonResponse
    {
        try {
            $result = $this->authService->refresh();
            return $this->respondWithToken($result['token'], $result['staff'], $result['permissions']);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Phiên đăng nhập đã hết hạn, vui lòng đăng nhập lại.',
            ], 401);
        }
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function respondWithToken(string $token, $staff, array $permissions = []): JsonResponse
    {
        return response()->json([
            'success'     => true,
            'token'       => $token,
            'token_type'  => 'bearer',
            'expires_in'  => config('jwt.ttl') * 60,
            'data'        => $this->staffData($staff),
            'permissions' => $permissions,
        ]);
    }

    private function staffData($staff): array
    {
        if (!$staff) return [];

        return [
            'id'        => $staff->id,
            'full_name' => $staff->full_name,
            'email'     => $staff->email,
            'avatar'    => $staff->avatar,
            'is_active' => $staff->is_active,
        ];
    }
}
