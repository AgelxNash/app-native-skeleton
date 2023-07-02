<?php

declare(strict_types=1);

namespace Core\Stream;

interface StreamInterface extends OutputInterface, InputInterface
{
    public function close(): void;
}