<?php

declare(strict_types=1);

namespace Core\Stream;

use LogicException;

class NativeStream implements StreamInterface
{
    private $resource;

    public function __construct(string $path)
    {
        $this->resource = fopen($path, 'rb');

    }

    public function __destruct()
    {
        $this->close();
    }

    public function write(string $data): void
    {
        fwrite($this->resource, $data);
    }

    public function read(): string
    {
        throw new LogicException('Пока нам это не нужно');
    }

    public function close(): void
    {
        fclose($this->resource);
    }
}