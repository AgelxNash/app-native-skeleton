<?php

namespace Core;

use Generator;

enum ActionPrefix: string
{
    case ONLY_WEB = '/';
    case ONLY_CLI = '?';

    public function filter(array $routers = []): Generator
    {
        foreach ($routers as $route => $handler) {
            if (str_starts_with($route, $this->value)) {
                yield $route;
            }
        }
    }
}
