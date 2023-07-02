<?php

declare(strict_types=1);

namespace Users\Repository;

use Core\Orm\Repository\RepositoryInterface;
use Users\Model\User;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function findByNickname(string $name): User;
}