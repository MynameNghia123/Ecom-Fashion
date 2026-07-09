<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Staff\StaffResource;
use App\Models\Staff;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Auth',
    description: 'Đăng nhập & Xác thực hệ thống Admin'
)]
class AuthController extends Controller
{
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
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required'    => 'Email không được để trống.',
            'email.email'       => 'Email không hợp lệ.',
            'password.required' => 'Mật khẩu không được để trống.',
        ]);

        $staff = Staff::where('email', $request->email)->first();

        if (!$staff || !Hash::check($request->password, $staff->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email hoặc mật khẩu không chính xác.',
            ], 401);
        }

        if (!$staff->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Tài khoản của bạn đã bị khóa.',
            ], 403);
        }

        // Tạo token mới
        $token = $staff->createToken('admin-token')->plainTextToken;

        // Cập nhật last_login_at
        $staff->update([
            'last_login_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đăng nhập thành công.',
            'token'   => $token,
            'user'    => new StaffResource($staff->load(['roles', 'permissions'])),
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
            $user->currentAccessToken()->delete();
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
            'data'    => new StaffResource($user->load(['roles', 'permissions'])),
        ]);
    }
}
