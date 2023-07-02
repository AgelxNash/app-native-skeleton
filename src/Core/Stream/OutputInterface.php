<?php

declare(strict_types=1);

namespace Core\Stream;

interface OutputInterface
{
    public function write(string $data): void;
}