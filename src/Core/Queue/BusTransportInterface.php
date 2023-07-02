<?php

declare(strict_types=1);

namespace Core\Queue;

interface BusTransportInterface
{
    public function push(string $message): void;
    public function pop(): ?string;
}