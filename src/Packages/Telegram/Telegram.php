<?php

declare(strict_types=1);

namespace Packages\Telegram;

class Telegram
{
    public function sendMessage(int $chatId, string $message): void
    {
        var_dump(__CLASS__, __METHOD__, $chatId, $message);
    }
}