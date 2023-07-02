<?php

declare(strict_types=1);

namespace Core\Queue\Transport;

use Core\MakeDirectoryIterator;
use Core\Queue\BusTransportInterface;
use DirectoryIterator;

class FileTransport implements BusTransportInterface
{
    use MakeDirectoryIterator;

    private DirectoryIterator $splDir;

    public function __construct(string $directory)
    {
        $this->splDir = $this->getDirectoryIterator($directory);
    }

    public function push(string $message): void
    {
        file_put_contents($this->splDir->getRealPath() . '/' . sha1($message) . '.txt', $message);
    }

    public function pop(): ?string
    {
        $this->splDir->rewind();

        foreach($this->splDir as $file) {
            if (!$file->isFile() || in_array($file->getFilename(), ['.', '..'], true)) {
                continue;
            }

            $data = $file->openFile()->__toString();
            unlink($file->getRealPath());
            return $data;
        }

        return null;
    }
}