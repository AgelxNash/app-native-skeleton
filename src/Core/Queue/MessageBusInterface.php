<?php

declare(strict_types=1);

namespace Core\Queue;

interface MessageBusInterface
{
    public function dispatch(MessageInterface $message): void;
}