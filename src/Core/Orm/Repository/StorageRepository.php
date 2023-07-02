<?php

declare(strict_types=1);

namespace Core\Orm\Repository;

use Core\Orm\Events;
use Core\Orm\ModelInterface;
use Core\Orm\NotFoundException;
use Core\Orm\Storage\StorageInterface;

abstract class StorageRepository implements RepositoryInterface
{
    private array $observers = [];

    public function __construct(protected readonly StorageInterface $storage)
    {

    }

    public function addObserver(callable $callback, array $events): void
    {
        foreach ($events as $event) {
            $this->observers[$event][] = $callback;
        }
    }

    public function notify(string $event, ModelInterface $model): ModelInterface
    {
        foreach ($this->observers[$event] ?? [] as $observer) {
            $result = $observer($event, $model);
            if ($result === null) {
                continue;
            }
            $model = $result;
        }

        return $model;
    }

    public function create(ModelInterface $model): ModelInterface
    {
        return $this->save($model, Events::CREATE_BEFORE->name, Events::CREATE_AFTER->name);
    }

    public function update(ModelInterface $model): ModelInterface
    {
        return $this->save($model, Events::UPDATE_BEFORE->name, Events::UPDATE_AFTER->name);
    }

    private function save(ModelInterface $model, string $eventBefore = null, string $eventAfter = null): ?ModelInterface
    {
        if ($eventBefore) {
            $model = $this->notify($eventBefore, $model);
        }

        $model = $this->storage->save($model);

        if ($eventAfter) {
            $this->notify($eventAfter, $model);
        }

        return $model;
    }

    public function findById(int $id): ModelInterface
    {
        $model = $this->storage->get(static::MODEL, $id);
        $model->setId($id);
        return $model;
    }

    public function findOneByMultipleFields(array $params): ModelInterface
    {
        foreach ($this->all() as $model) {
            foreach ($params as $field => $value) {
                if ($model->{$field} !== $value) {
                    continue 2;
                }
            }

            return $model;
        }

        throw new NotFoundException(sprintf(
            'Element "%s" by fields "%s" with values "%s" not found',
            static::MODEL,
            implode(', ', array_keys($params)),
            implode(', ', array_values($params))
        ));
    }

    public function findOneByField(string $field, $value): ModelInterface
    {
        return $this->findOneByMultipleFields([$field => $value]);
    }

    /**
     * @return array|ModelInterface[]
     */
    public function all(): array
    {
        return $this->storage->all(static::MODEL);
    }
}