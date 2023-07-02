<?php

declare(strict_types=1);

namespace Confirm\Service\Code;

use LogicException;
use Throwable;

readonly class CodeGenerator implements CodeGeneratorInterface
{
    public function __construct(private string $salt)
    {

    }

    public function pack(CodeDto $dto): string
    {
        return bin2hex(base64_encode($this->getSalt($dto->key) . ':' . $dto->value));
    }

    public function unpack(string $secret, string $salt): CodeDto
    {
        try {
            [$key, $value] = explode(':', base64_decode(hex2bin($secret)));
            if ($this->getSalt($salt) !== $key) {
                throw new LogicException('Wrong salt');
            }
            return new CodeDto($key, $value);
        } catch (Throwable $exception) {
            throw new InvalidCodeException(sprintf('Invalid secret key "%s"', $secret), 0, $exception);
        }
    }

    private function getSalt(string $key): string
    {
        return md5($this->salt . $key);
    }
}