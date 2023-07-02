<?php

declare(strict_types=1);

namespace Core\Orm;

interface ModelInterface
{
    public function setId(int $id): void;
    public function getId(): int|null;
    public function getOriginal(): array;
    public function isChanged(string $key = null): bool;
    public function isNew(): bool;
}