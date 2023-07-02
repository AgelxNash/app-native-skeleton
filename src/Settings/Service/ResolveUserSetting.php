<?php

declare(strict_types=1);

namespace Settings\Service;

use Core\Orm\NotFoundException;
use InvalidArgumentException;
use Settings\Map;
use Settings\Model\UserSetting;
use Settings\Repository\UserSettingsRepositoryInterface;
use Throwable;
use Users\Repository\UserRepositoryInterface;

readonly class ResolveUserSetting
{
    public function __construct(private UserRepositoryInterface $userRepository, private UserSettingsRepositoryInterface $userSettingsRepo)
    {

    }

    public function handle(int $userId, string $settingName): UserSetting
    {
        try {
            $setting = Map::fromName($settingName);
        } catch (Throwable) {
            throw new InvalidArgumentException(sprintf('Setting "%s" not found', $settingName));
        }

        $user = $this->userRepository->findById($userId);

        try {
            $userSetting = $this->userSettingsRepo->findBySettingAndUser(
                $setting->name,
                $user->getId()
            );
        } catch (NotFoundException) {
            $userSetting = new UserSetting($user->getId(), $setting->name, $setting->value);
        }

        return $userSetting;
    }
}