<?php

declare(strict_types=1);

namespace Core\Queue;

interface MessageHandlerInterface
{
    public function process(): void;
}