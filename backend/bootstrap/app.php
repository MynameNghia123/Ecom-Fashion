<?php

use App\Http\Middleware\CheckPermission;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'permission' => CheckPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Chỉ bắt lỗi khi request là API (prefix /api/*)
        $exceptions->render(function (Throwable $e, Request $request) {
            if (! $request->is('api/*')) {
                return null; // Để Laravel xử lý mặc định cho non-API
            }

            // ── 1. Validation Error (422) ──────────────────────────────────
            if ($e instanceof ValidationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu đầu vào không hợp lệ.',
                    'errors' => $e->errors(),
                ], 422);
            }

            // ── 2. Model Not Found (404) ───────────────────────────────────
            if ($e instanceof ModelNotFoundException) {
                $model = class_basename($e->getModel());

                return response()->json([
                    'success' => false,
                    'message' => "Không tìm thấy dữ liệu {$model} hoặc đã bị xóa.",
                ], 404);
            }

            // ── 3. Unauthenticated (401) ───────────────────────────────────
            if ($e instanceof AuthenticationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn chưa đăng nhập hoặc phiên đăng nhập đã hết hạn.',
                ], 401);
            }

            // ── 4. HTTP Exception (403, 404, 405, v.v.) ───────────────────
            if ($e instanceof HttpExceptionInterface) {
                $status = $e->getStatusCode();
                $message = match ($status) {
                    403 => 'Bạn không có quyền thực hiện hành động này.',
                    404 => 'Đường dẫn API không tồn tại.',
                    405 => 'Phương thức HTTP không được hỗ trợ.',
                    default => $e->getMessage() ?: 'Đã xảy ra lỗi.',
                };

                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], $status);
            }

            // ── 5. Server Error (500) ──────────────────────────────────────
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi hệ thống, vui lòng thử lại sau.',
            ], 500);
        });
    })->create();
