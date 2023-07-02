<?php

declare(strict_types=1);

namespace Settings\Service;

use Settings\Model\UserSetting;
use Settings\Repository\UserSettingsRepositoryInterface;

readonly class ChangeSetting
{
    public function __construct(private ResolveUserSetting $resolveUserSetting, private UserSettingsRepositoryInterface $userSettingsRepo)
    {

    }

    public function handle(UserSetting $userSetting, string $value): bool
    {
        $userSetting->value = $value;

        if ($userSetting->isNew()) {
            $this->userSettingsRepo->create($userSetting);
            return true;
        }

        if ($userSetting->isChanged('value')) {
            $this->userSettingsRepo->update($userSetting);

            return true;
        }

        return false;
    }

    public function resolveUserSettings(int $userId, string $settingName): UserSetting
    {
        return $this->resolveUserSetting->handle($userId, $settingName);
    }
}