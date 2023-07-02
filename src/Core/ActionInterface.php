<?php

declare(strict_types=1);

namespace Core;

interface ActionInterface
{
    public function __invoke(string ...$params): void;
}