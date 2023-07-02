<?php

declare(strict_types=1);

namespace Core\Dependency;

interface ContainerInterface
{
    public function get(string $name): mixed;
}