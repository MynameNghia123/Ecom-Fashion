<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Staff\StaffResource;
use App\Services\Admin\Interfaces\AuthServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Auth\LoginRequest;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Auth',
    description: 'Đăng nhập & Xác thực hệ thống Admin'
)]
class AuthController extends Controller
{
    public function __construct(
        private readonly AuthServiceInterface $authService
    ) {}

    // ── POST /api/admin/auth/login ───────────────────────────────────────────
    #[OA\Post(
        path: '/api/admin/auth/login',
        summary: 'Đăng nhập hệ thống Admin',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email',    type: 'string', format: 'email', example: 'admin@ecomfashion.com'),
                    new OA\Property(property: 'password', type: 'string', example: 'password123'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Đăng nhập thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'token',   type: 'string', example: '1|abcdef123456...'),
                    new OA\Property(property: 'user',    type: 'object'),
                ])
            ),
            new OA\Response(response: 401, description: 'Sai email hoặc mật khẩu'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->only(['email', 'password']));

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], $result['status_code']);
        }

        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'token'   => $result['token'],
            'user'    => new StaffResource($result['user']),
        ]);
    }

    // ── POST /api/admin/auth/logout ──────────────────────────────────────────
    #[OA\Post(
        path: '/api/admin/auth/logout',
        summary: 'Đăng xuất khỏi hệ thống',
        tags: ['Auth'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Đăng xuất thành công'),
            new OA\Response(response: 401, description: 'Chưa đăng nhập'),
        ]
    )]
    public function logout(Request $request): JsonResponse
    {
        /** @var \App\Models\Staff $user */
        $user = $request->user();
        
        if ($user) {
            $this->authService->logout($user);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đăng xuất thành công.',
        ]);
    }

    // ── GET /api/admin/auth/me ───────────────────────────────────────────────
    #[OA\Get(
        path: '/api/admin/auth/me',
        summary: 'Lấy thông tin tài khoản hiện tại',
        tags: ['Auth'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Thành công'),
            new OA\Response(response: 401, description: 'Chưa đăng nhập'),
        ]
    )]
    public function me(Request $request): JsonResponse
    {
        /** @var \App\Models\Staff $user */
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data'    => new StaffResource($this->authService->me($user)),
        ]);
    }
}
