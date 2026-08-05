<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Auth\RegisterRequest;
use App\Http\Requests\Client\Auth\LoginRequest;
use App\Http\Requests\Client\Auth\ForgotPasswordRequest;
use App\Http\Requests\Client\Auth\VerifyOtpRequest;
use App\Http\Requests\Client\Auth\ResetPasswordRequest;
use App\Http\Requests\Client\Auth\UpdateProfileRequest;
use App\Http\Requests\Client\Auth\ChangePasswordRequest;
use App\Services\Client\Interfaces\AuthServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private readonly AuthServiceInterface $authService) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());

        return response()->json($result, 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->validated());

        if (!$result['success']) {
            $status = str_contains($result['message'], 'bị khóa') ? 403 : 401;
            return response()->json($result, $status);
        }

        return response()->json($result, 200);
    }

    public function logout(Request $request): JsonResponse
    {
        if ($request->user()) {
            $this->authService->logout($request->user());
        }

        return response()->json([
            'success' => true,
            'message' => 'Customer logged out successfully',
        ], 200);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'user' => $request->user()
        ]);
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $result = $this->authService->forgotPassword($request->validated()['email']);

        if (!$result['success']) {
            return response()->json($result, 500);
        }

        return response()->json($result, 200);
    }

    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $result = $this->authService->verifyOtp($validated['email'], $validated['otp_code']);

        if (!$result['success']) {
            return response()->json($result, 422);
        }

        return response()->json($result, 200);
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $result = $this->authService->resetPassword($validated['token'], $validated['password']);

        if (!$result['success']) {
            $status = str_contains($result['message'], 'Không tìm thấy') ? 404 : 422;
            return response()->json($result, $status);
        }

        return response()->json($result, 200);
    }

    /**
     * PUT /client/auth/profile — Cập nhật thông tin cá nhân.
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $customer = $request->user();
        $result = $this->authService->updateProfile($customer, $request->validated());

        return response()->json($result);
    }

    /**
     * PUT /client/auth/change-password — Đổi mật khẩu.
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $customer = $request->user();
        $result = $this->authService->changePassword($customer, $request->validated());

        if (!$result['success']) {
            return response()->json($result, 400);
        }

        return response()->json($result);
    }
}
