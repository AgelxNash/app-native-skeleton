<?php

declare(strict_types=1);

namespace Core\Orm\Repository;

use Core\Orm\ModelInterface;

interface RepositoryInterface
{
    public const MODEL = '';

    public function addObserver(callable $callback, array $events): void;
    public function notify(string $event, ModelInterface $model): ModelInterface;
    public function create(ModelInterface $model): ModelInterface;
    public function update(ModelInterface $model): ModelInterface;
    public function findById(int $id): ModelInterface;
    public function findOneByMultipleFields(array $params): ModelInterface;
    public function findOneByField(string $field, $value): ModelInterface;
    public function all(): array;
}