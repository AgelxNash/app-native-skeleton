<?php

declare(strict_types=1);

namespace Core\Orm;

abstract class Model implements ModelInterface
{
    protected int|null $id = null;
    protected array $fields = [];
    protected array $original = [];
    protected array $attributes = [];

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function isNew(): bool
    {
        return is_null($this->id);
    }

    public function __get(string $name)
    {
        return $this->attributes[$name] ?? null;
    }

    public function __set(string $name, $value): void
    {
        if (!in_array($name, $this->fields, true)) {
            return;
        }

        if (($this->attributes[$name] ?? null) !== $value) {
            $this->original[$name] = $this->attributes[$name];
        }

        $this->attributes[$name] = $value;
    }

    public function __isset(string $name): bool
    {
        return array_key_exists($name, $this->attributes);
    }

    public function __unset(string $name): void
    {
        unset($this->attributes[$name]);
    }

    public function __sleep(): array
    {
        return ['id', 'attributes'];
    }

    public function __wakeup(): void
    {
        $this->original = [];
    }

    public function getOriginal(): array
    {
        return $this->original;
    }

    public function isChanged(string $key = null): bool
    {
        if ($key === null) {
            return !empty($this->original);
        }

        return array_key_exists($key, $this->original);
    }
}