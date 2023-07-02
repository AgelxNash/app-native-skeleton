<?php

declare(strict_types=1);

namespace Confirm\Service\Senders;

use Core\Dependency\ContainerInterface;
use InvalidArgumentException;

readonly class Sender implements FactoryInterface
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function factory(string $method): SenderInterface
    {
        if (SenderMethod::SMS->value === $method) {
            return $this->container->get(SmsSender::class);
        }

        if (SenderMethod::EMAIL->value === $method) {
            return $this->container->get(EmailSender::class);
        }

        if (SenderMethod::TELEGRAM->value === $method) {
            return $this->container->get(TelegramSender::class);
        }

        throw new InvalidArgumentException(sprintf('Unknown send method "%s"', $method));
    }
}