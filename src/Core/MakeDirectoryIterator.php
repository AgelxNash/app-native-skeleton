<?php

declare(strict_types=1);

namespace Core;

use DirectoryIterator;
use InvalidArgumentException;
use RuntimeException;

trait MakeDirectoryIterator
{
    public function getDirectoryIterator(string $directory): DirectoryIterator
    {
        if (is_file($directory)) {
            throw new InvalidArgumentException(sprintf('Is file "%s", but not directory', $directory));
        }

        if (is_dir($directory) && !(is_writable($directory) && is_readable($directory))) {
            throw new InvalidArgumentException(sprintf('Directory "%s" exists but not able to access it', $directory));
        }

        if (!is_dir($directory) && !mkdir($directory, 0777, true) && !is_dir($directory)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $directory));
        }

        return new DirectoryIterator($directory);
    }
}