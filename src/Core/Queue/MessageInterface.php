<?php

declare(strict_types=1);

namespace Core\Queue;

interface MessageInterface
{
    public function __sleep(): array;
}