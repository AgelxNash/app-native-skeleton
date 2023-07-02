<?php

declare(strict_types=1);

use Core\ActionInterface;
use Core\Dependency\ContainerInterface;

readonly class Kernel
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function run(string $actionName, array $params = [], string $defaultHandler = null): void
    {
        $actionList = $this->container->get(Config::ACTIONS_LIST->name);

        if(array_key_exists($actionName, $actionList)) {
            $actionHandler = $actionList[$actionName];
        } else if ($defaultHandler !== null) {
            $actionHandler = $defaultHandler;
        } else {
            throw new LogicException('Actions not defined');
        }

        if (!in_array(ActionInterface::class, class_implements($actionHandler), true)) {
            throw new DomainException(sprintf(
                'Action "%s" has not implement %s',
                $actionHandler,
                ActionInterface::class
            ));
        }

        $handler = $this->container->get($actionHandler);
        $handler(...$params);
    }
}