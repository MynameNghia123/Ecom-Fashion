<?php
namespace App\Services\Client\Implements;
use App\Mail\OtpMail;
use App\Models\Customer;
use App\Repositories\Client\Interfaces\AuthRepositoryInterface;
use App\Services\Client\Interfaces\AuthServiceInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class AuthService implements AuthServiceInterface
{
    public function __construct(private readonly AuthRepositoryInterface $repo) {}

    public function register(array $data): array
    {
        $customer = $this->repo->createCustomer([
            'first_name'   => $data['first_name'],
            'last_name'    => $data['last_name'],
            'email'        => $data['email'],
            'phone_number' => $data['phone_number'] ?? null,
            'password'     => $data['password'],
            'status'       => 1,
        ]);

        $token = $customer->createToken('customer_token')->plainTextToken;

        return [
            'success' => true,
            'message' => 'Customer registered successfully',
            'token'   => $token,
            'user'    => $customer,
        ];
    }

    public function login(array $data): array
    {
        $customer = $this->repo->findByEmail($data['email']);

        if (!$customer || !Hash::check($data['password'], $customer->password)) {
            return [
                'success' => false,
                'message' => 'Email hoặc mật khẩu không chính xác.',
            ];
        }

        if ($customer->status !== 1) {
            return [
                'success' => false,
                'message' => 'Tài khoản của bạn đã bị khóa.',
            ];
        }

        $token = $customer->createToken('customer_token')->plainTextToken;

        return [
            'success' => true,
            'message' => 'Customer logged in successfully',
            'token'   => $token,
            'user'    => $customer,
        ];
    }

    public function logout(Customer $customer): void
    {
        $customer->currentAccessToken()->delete();
    }

    public function forgotPassword(string $email): array
    {
        try {
            DB::statement("ALTER TABLE customer_password_otps MODIFY COLUMN otp VARCHAR(255) NOT NULL");
        } catch (\Exception $e) {
            // Already correct type — ignore
        }

        $otp = sprintf("%06d", mt_rand(1, 999999));

        try {
            $this->repo->clearExistingOtps($email);
            $this->repo->createOtpRecord($email, $otp);

            Mail::to($email)->send(new OtpMail($otp));

            return [
                'success' => true,
                'message' => 'Mã OTP đã được gửi về email của bạn.'
            ];
        } catch (Throwable $e) {
            Log::error('[forgotPassword] ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Đã xảy ra lỗi hệ thống, vui lòng thử lại sau.'
            ];
        }
    }

    public function verifyOtp(string $email, string $otpCode): array
    {
        $otpRecord = $this->repo->findOtpRecord($email, $otpCode);

        if (!$otpRecord) {
            return [
                'success' => false,
                'message' => 'Mã OTP không hợp lệ hoặc đã hết hạn.'
            ];
        }

        $this->repo->markOtpAsUsed($otpRecord->id);

        $resetToken = Str::random(64);
        Cache::put('reset_token_' . $resetToken, $email, now()->addMinutes(10));

        return [
            'success'     => true,
            'reset_token' => $resetToken
        ];
    }

    public function resetPassword(string $token, string $password): array
    {
        $email = Cache::get('reset_token_' . $token);

        if (!$email) {
            return [
                'success' => false,
                'message' => 'Token xác thực đã hết hạn hoặc không hợp lệ.'
            ];
        }

        $customer = $this->repo->findByEmail($email);
        if (!$customer) {
            return [
                'success' => false,
                'message' => 'Không tìm thấy thông tin khách hàng.'
            ];
        }

        $this->repo->updateCustomer($customer, ['password' => $password]);

        Cache::forget('reset_token_' . $token);

        return [
            'success' => true,
            'message' => 'Mật khẩu của bạn đã được cập nhật thành công.'
        ];
    }

    public function updateProfile(Customer $customer, array $data): array
    {
        $this->repo->updateCustomer($customer, [
            'first_name'   => $data['first_name'],
            'last_name'    => $data['last_name'],
            'phone_number' => $data['phone_number'] ?? null,
        ]);

        return [
            'success' => true,
            'message' => 'Cập nhật thông tin thành công.',
            'data'    => $customer,
        ];
    }

    public function changePassword(Customer $customer, array $data): array
    {
        if (!Hash::check($data['current_password'], $customer->password)) {
            return [
                'success' => false,
                'message' => 'Mật khẩu hiện tại không chính xác.',
            ];
        }

        $this->repo->updateCustomer($customer, ['password' => $data['new_password']]);

        return [
            'success' => true,
            'message' => 'Đổi mật khẩu thành công.',
        ];
    }
}
