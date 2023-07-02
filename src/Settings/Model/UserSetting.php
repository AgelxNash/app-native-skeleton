<?php

declare(strict_types=1);

namespace Settings\Model;

use Core\Orm\Model;

/**
 * @property int|null $id
 * @property int $userId
 * @property string $setting
 * @property string $value
 */
class UserSetting extends Model
{
    protected array $fields = [
        'userId', 'setting', 'value'
    ];

    public function __construct(int $userId, string $setting, string $value)
    {
        $this->userId = $userId;
        $this->setting = $setting;
        $this->value = $value;
    }
}