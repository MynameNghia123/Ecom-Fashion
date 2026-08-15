<?php

namespace App\Repositories\Client\Implements;

use App\Models\Customer;
use App\Repositories\Client\Interfaces\AuthRepositoryInterface;
use Illuminate\Support\Facades\DB;

class AuthRepository implements AuthRepositoryInterface
{
    public function __construct(private readonly Customer $model) {}

    public function findByEmail(string $email): ?Customer
    {
        return $this->model->where('email', $email)->first();
    }

    public function createCustomer(array $data): Customer
    {
        return $this->model->create($data);
    }

    public function updateCustomer(Customer $customer, array $data): bool
    {
        return $customer->update($data);
    }

    public function findOtpRecord(string $email, string $otpCode): ?object
    {
        return DB::table('customer_password_otps')
            ->where('email', $email)
            ->where('otp', $otpCode)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public function markOtpAsUsed(int $otpId): bool
    {
        return DB::table('customer_password_otps')
            ->where('id', $otpId)
            ->update(['used' => true]) > 0;
    }

    public function clearExistingOtps(string $email): void
    {
        DB::table('customer_password_otps')->where('email', $email)->delete();
    }

    public function createOtpRecord(string $email, string $otp): void
    {
        DB::table('customer_password_otps')->insert([
            'email' => $email,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
