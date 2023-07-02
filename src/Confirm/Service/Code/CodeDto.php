<?php

declare(strict_types=1);

namespace Confirm\Service\Code;

final readonly class CodeDto
{
    public function __construct(public string $key, public string $value)
    {

    }
}