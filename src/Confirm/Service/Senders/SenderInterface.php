<?php

declare(strict_types=1);

namespace Confirm\Service\Senders;

use Users\Model\User;

interface SenderInterface
{
    public function send(string $code, User $user): void;
}