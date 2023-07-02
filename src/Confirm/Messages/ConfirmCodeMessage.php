<?php

declare(strict_types=1);

namespace Confirm\Messages;

use Core\Queue\MessageInterface;

readonly class ConfirmCodeMessage implements MessageInterface
{
    public function __construct(public int $userId, public string $code, public string $method)
    {

    }

    public function __sleep(): array
    {
        return ['userId', 'code', 'method'];
    }
}