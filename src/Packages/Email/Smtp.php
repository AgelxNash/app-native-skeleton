<?php

declare(strict_types=1);

namespace Packages\Email;

class Smtp
{
    public function send(string $email, string $message): void
    {
        var_dump(__CLASS__, __METHOD__, $email, $message);
    }
}