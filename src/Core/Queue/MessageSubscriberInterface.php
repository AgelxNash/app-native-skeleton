<?php

declare(strict_types=1);

namespace Core\Queue;

interface MessageSubscriberInterface
{
    public function __invoke(MessageInterface $message);
}