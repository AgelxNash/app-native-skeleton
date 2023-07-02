<?php

declare(strict_types=1);

namespace Core;

use Core\Stream\StreamInterface;

final readonly class NotFoundController implements ActionInterface
{
    public function __construct(private StreamInterface $stream, private array $actions = []) {}

    public function __invoke(string ...$params): void
    {
        $this->stream->write('<h1>Page not found</h1>');

        foreach (ActionPrefix::ONLY_WEB->filter($this->actions) as $route) {
            $this->stream->write($route . '<br />');
        }
    }
}