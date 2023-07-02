<?php

declare(strict_types=1);

namespace Core\Dependency;

use DomainException;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReflectionUnionType;
use Throwable;

class Container implements ContainerInterface
{
    private array $prepared = [];

    public function __construct(
        private readonly array $configs,
        private readonly array $builders = [],
        private array $aliases = []
    ) {
        $this->aliases[ContainerInterface::class] = self::class;
        $this->prepared[ContainerInterface::class] = $this;

        if (array_diff_key($this->configs, $this->builders, $this->aliases) !== $this->configs ||
            array_diff_key($this->builders, $this->configs, $this->aliases) !== $this->builders ||
            array_diff_key($this->aliases, $this->builders, $this->configs) !== $this->aliases
        ) {
            throw new InvalidArgumentException('Duplicated keys');
        }
    }

    public function get(string $name): mixed
    {
        if (!$this->has($name)) {
            if (!class_exists($name)) {
                throw new InvalidArgumentException(sprintf('DI "%s" not found', $name));
            }

            return $this->autowired($name);
        }

        if (array_key_exists($name, $this->configs)) {
            return $this->configs[$name];
        }

        if (array_key_exists($name, $this->prepared)) {
            return $this->prepared[$name];
        }

        if (array_key_exists($name, $this->aliases)) {
            return $this->get($this->aliases[$name]);
        }

        if (is_callable($this->builders[$name])) {
            return $this->prepared[$name] = $this->builders[$name]($this);
        }

        throw new InvalidArgumentException(sprintf('Cannot resolve DI "%s"', $name));
    }

    public function has(string $name): bool
    {
        return array_key_exists($name, $this->configs) ||
            array_key_exists($name, $this->aliases) ||
            array_key_exists($name, $this->builders);
    }

    private function autowired(string $name): mixed
    {
        $params = [];

        try {
            $class = new ReflectionClass($name);
        } catch (ReflectionException $exception) {
            throw new DomainException(
                sprintf('Error reflection class "%s"', $name),
                0,
                $exception
            );
        }

        foreach($class->getConstructor()?->getParameters() ?? [] as $parameter) {
            $paramType = $parameter->getType();

            try {
                $value = ($paramType instanceof ReflectionUnionType && $parameter->isDefaultValueAvailable()) ?
                    $parameter->getDefaultValue() : $this->get($paramType->getName());
            } catch (Throwable $exception) {
                throw new DomainException(
                    sprintf('Wrong param "%s" in class "%s"', $parameter->getName(), $name),
                    0,
                    $exception
                );
            }

            $params[$parameter->getName()] = $value;
        }

        return $this->prepared[$name] = new $name(...$params);
    }
}