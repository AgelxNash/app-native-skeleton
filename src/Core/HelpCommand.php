<?php

declare(strict_types=1);

namespace Core;

use Core\Stream\StreamInterface;

final readonly class HelpCommand implements ActionInterface
{
    public function __construct(private StreamInterface $stream, private array $actions = []) {}
    public function __invoke(string ...$params): void
    {
        $this->stream->write(PHP_EOL . 'Available CLI commands:' . PHP_EOL);

        foreach (ActionPrefix::ONLY_CLI->filter($this->actions) as $route) {
            $this->stream->write("\t" . $route . PHP_EOL);
        }
    }
}