<?php

declare(strict_types=1);

namespace Packages\SmsCenter;

class Sender
{
    public function sms(string $phone, string $message): void
    {
        var_dump(__CLASS__, __METHOD__, $phone, $message);
    }
}