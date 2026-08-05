<?php
namespace App\Repositories\Client\Interfaces;
use App\Models\Customer;

interface AuthRepositoryInterface
{
    public function findByEmail(string $email): ?Customer;
    public function createCustomer(array $data): Customer;
    public function updateCustomer(Customer $customer, array $data): bool;
    
    // OTP / Reset Password
    public function findOtpRecord(string $email, string $otpCode): ?object;
    public function markOtpAsUsed(int $otpId): bool;
    public function clearExistingOtps(string $email): void;
    public function createOtpRecord(string $email, string $otp): void;
}
