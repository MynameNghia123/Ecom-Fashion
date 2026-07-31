<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Services\Admin\Interfaces\AuthServiceInterface;

class CheckPermission
{
    public function __construct(
        private readonly AuthServiceInterface $authService
    ) {}

    /**
     * Kiểm tra staff có quyền tương ứng không.
     *
     * Sử dụng trong route: ->middleware('permission:products,create')
     * Nghĩa là: module=products, action=create
     */
    public function handle(Request $request, Closure $next, string $module, string $action)
    {
        $staff = Auth::guard('staff')->user();

        if (!$staff) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn chưa đăng nhập.',
            ], 401);
        }

        $permissions = $this->authService->getPermissions($staff);
        $required    = $module . ':' . $action;

        if (! in_array($required, $permissions, true)) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền thực hiện hành động này.',
            ], 403);
        }

        return $next($request);
    }
}
