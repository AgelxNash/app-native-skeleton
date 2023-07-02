<?php

declare(strict_types=1);

namespace Core\Queue;

use Core\ActionInterface;
use JetBrains\PhpStorm\NoReturn;

readonly class Command implements ActionInterface
{
    public function __construct(private MessageHandlerInterface $handler)
    {
    }

    #[NoReturn] public function __invoke(string ...$params): void
    {
        $this->handler->process();
    }
}