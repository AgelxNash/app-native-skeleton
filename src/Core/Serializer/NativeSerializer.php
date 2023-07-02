<?php

declare(strict_types=1);

namespace Core\Serializer;

readonly class NativeSerializer implements SerializerInterface
{
    public function __construct(private array|bool $allowedClasses = true)
    {
    }

    public function serialize(mixed $object): string
    {
        return serialize($object);
    }

    public function unserialize(string $data): mixed
    {
        return unserialize($data, ['allowed_classes' => $this->allowedClasses]);
    }
}