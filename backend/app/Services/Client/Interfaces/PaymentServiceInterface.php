<?php
namespace App\Services\Client\Interfaces;

interface PaymentServiceInterface
{
    /**
     * @return array{success: bool, message: string, code?: string, order_code?: string}
     */
    public function verifyReturn(array $vnpData): array;

    /**
     * @return array{rspCode: string, message: string}
     */
    public function handleIpn(array $vnpData): array;
}
