<?php

namespace App\Services\Client\Interfaces;

interface AiChatServiceInterface
{
    /**
     * @return array{success: bool, message?: string, reply?: string, code?: int}
     */
    public function chat(array $messages, ?int $productId, ?int $customerId): array;

    public function getHistory(int $customerId): array;

    public function syncGuestHistory(int $customerId, array $messages): void;
}
