<?php

declare(strict_types=1);

namespace Core\Stream;

interface InputInterface
{
    public function read(): string;
}