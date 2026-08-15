<?php

namespace App\Services\Client\Interfaces;

use App\Models\Customer;

interface AuthServiceInterface
{
    /**
     * @return array{success: bool, message: string, token?: string, user?: Customer}
     */
    public function register(array $data): array;

    /**
     * @return array{success: bool, message: string, token?: string, user?: Customer}
     */
    public function login(array $data): array;

    public function logout(Customer $customer): void;

    /**
     * @return array{success: bool, message: string}
     */
    public function forgotPassword(string $email): array;

    /**
     * @return array{success: bool, message: string, reset_token?: string}
     */
    public function verifyOtp(string $email, string $otpCode): array;

    /**
     * @return array{success: bool, message: string}
     */
    public function resetPassword(string $token, string $password): array;

    /**
     * @return array{success: bool, message: string, data?: Customer}
     */
    public function updateProfile(Customer $customer, array $data): array;

    /**
     * @return array{success: bool, message: string}
     */
    public function changePassword(Customer $customer, array $data): array;
}
