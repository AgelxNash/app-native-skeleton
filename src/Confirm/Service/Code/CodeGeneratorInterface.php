<?php

declare(strict_types=1);

namespace Confirm\Service\Code;

interface CodeGeneratorInterface
{
    public function pack(CodeDto $dto): string;
    public function unpack(string $secret, string $salt): CodeDto;
}