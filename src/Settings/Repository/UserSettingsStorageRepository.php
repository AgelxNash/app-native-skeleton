<?php

declare(strict_types=1);

namespace Settings\Repository;

use Core\Orm\Repository\StorageRepository;
use Settings\Model\UserSetting;

class UserSettingsStorageRepository extends StorageRepository implements UserSettingsRepositoryInterface
{
    public const MODEL = UserSetting::class;

    public function findBySettingAndUser(string $setting, int $userId): UserSetting
    {
        return $this->findOneByMultipleFields(compact('setting', 'userId'));
    }
}