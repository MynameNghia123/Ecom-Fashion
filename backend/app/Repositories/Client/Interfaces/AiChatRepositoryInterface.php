<?php
namespace App\Repositories\Client\Interfaces;
use App\Models\ChatSession;

interface AiChatRepositoryInterface
{
    public function findSessionByCustomerId(int $customerId): ?ChatSession;
    public function findOrCreateSession(int $customerId): ChatSession;
    public function createMessage(int $sessionId, string $sender, string $message): void;
    public function messageExists(int $sessionId, string $sender, string $message): bool;
}
