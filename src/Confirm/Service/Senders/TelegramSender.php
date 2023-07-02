<?php

declare(strict_types=1);

namespace Confirm\Service\Senders;

use LogicException;
use Packages\Telegram\Telegram;
use Users\Model\User;

readonly class TelegramSender implements SenderInterface
{
    public function __construct(private Telegram $telegram)
    {

    }

    public function send(string $code, User $user): void
    {
        if (empty($user->tgId) || !is_int($user->tgId)) {
            throw new LogicException('Wrong user "%d" phone "%s"', $user->getId(), $user->tgId);
        }

        $this->telegram->sendMessage($user->tgId, $code);
    }
}