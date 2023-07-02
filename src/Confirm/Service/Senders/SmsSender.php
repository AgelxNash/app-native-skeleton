<?php

declare(strict_types=1);

namespace Confirm\Service\Senders;

use LogicException;
use Packages\SmsCenter\Sender;
use Users\Model\User;

readonly class SmsSender implements SenderInterface
{
    public function __construct(private Sender $sender)
    {

    }

    public function send(string $code, User $user): void
    {
        if (empty($user->phone) || !str_starts_with($user->phone, '+')) {
            throw new LogicException('Wrong user "%d" phone "%s"', $user->getId(), $user->phone);
        }

        $this->sender->sms($user->phone, $code);
    }
}