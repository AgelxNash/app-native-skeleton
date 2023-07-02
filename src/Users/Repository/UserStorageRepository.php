<?php

declare(strict_types=1);

namespace Users\Repository;

use Core\Orm\Repository\StorageRepository;
use Users\Model\User;

class UserStorageRepository extends StorageRepository implements UserRepositoryInterface
{
    public const MODEL = User::class;

    public function findByNickname(string $name): User
    {
        return $this->findOneByField('nickname', $name);
    }
}