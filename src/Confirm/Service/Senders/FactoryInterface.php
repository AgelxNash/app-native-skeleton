<?php

declare(strict_types=1);

namespace Confirm\Service\Senders;

interface FactoryInterface
{
    public function factory(string $method): SenderInterface;
}