<?php

namespace App\Repositories\Client\Implements;

use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Repositories\Client\Interfaces\AiChatRepositoryInterface;

class AiChatRepository implements AiChatRepositoryInterface
{
    public function __construct(
        private readonly ChatSession $sessionModel,
        private readonly ChatMessage $messageModel
    ) {}

    public function findSessionByCustomerId(int $customerId): ?ChatSession
    {
        return $this->sessionModel->where('customer_id', $customerId)->with('messages')->first();
    }

    public function findOrCreateSession(int $customerId): ChatSession
    {
        return $this->sessionModel->firstOrCreate(['customer_id' => $customerId]);
    }

    public function createMessage(int $sessionId, string $sender, string $message): void
    {
        $this->messageModel->create([
            'chat_session_id' => $sessionId,
            'sender' => $sender,
            'message' => $message,
        ]);
    }

    public function messageExists(int $sessionId, string $sender, string $message): bool
    {
        return $this->messageModel->where('chat_session_id', $sessionId)
            ->where('sender', $sender)
            ->where('message', $message)
            ->exists();
    }
}
