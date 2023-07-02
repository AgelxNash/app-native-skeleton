<?php

declare(strict_types=1);

namespace Core\Serializer;

interface SerializerInterface
{
    public function serialize(mixed $object): string;
    public function unserialize(string $data): mixed;
}