<?php

declare(strict_types=1);

namespace Settings\Repository;

use Core\Orm\Repository\RepositoryInterface;
use Settings\Model\UserSetting;

interface UserSettingsRepositoryInterface extends RepositoryInterface
{
    public function findBySettingAndUser(string $setting, int $userId): UserSetting;
}