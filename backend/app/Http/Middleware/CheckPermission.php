<?php

namespace App\Http\Middleware;

use App\Models\Staff;
use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    /**
     * Map HTTP methods to actions.
     */
    protected array $actionMap = [
        'GET' => 'view',
        'POST' => 'create',
        'PUT' => 'update',
        'PATCH' => 'update',
        'DELETE' => 'delete',
    ];

    /**
     * Handle an incoming request.
     * Usage in route: middleware('permission:module')
     * Example: middleware('permission:product')
     */
    public function handle(Request $request, Closure $next, string $module)
    {
        // Require user to be authenticated (e.g. auth:sanctum or default guard)
        /** @var Staff|null $user */
        $user = $request->user();

        // If no user is logged in, you can return 401.
        // For testing when auth is not fully configured, you might bypass this temporarily,
        // but for security it should strictly return 401.
        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn chưa đăng nhập hoặc phiên đăng nhập đã hết hạn.',
            ], 401);
        }

        // Determine action based on HTTP method
        $action = $this->actionMap[$request->method()] ?? 'view';

        // Check if user has permission
        if (! $user->hasPermission($module, $action)) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền thực hiện hành động này.',
            ], 403);
        }

        return $next($request);
    }
}
