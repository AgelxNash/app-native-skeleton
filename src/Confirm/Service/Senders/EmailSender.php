<?php

declare(strict_types=1);

namespace Confirm\Service\Senders;

use LogicException;
use Packages\Email\Smtp;
use Users\Model\User;

readonly class EmailSender implements SenderInterface
{
    public function __construct(private Smtp $smtp)
    {

    }

    public function send(string $code, User $user): void
    {
        if (empty($user->email) || !filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            throw new LogicException('Wrong user "%d" email "%s"', $user->getId(), $user->email);
        }

        $this->smtp->send($user->email, $code);
    }
}