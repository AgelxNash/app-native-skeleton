<?php

declare(strict_types=1);

namespace Users\Model;

use Core\Orm\Model;

/**
 * @property int|null $id
 * @property string $nickname
 * @property string|null $email
 * @property string|null $phone
 * @property int|null $tgId
 */
class User extends Model
{
    protected array $fields = [
        'nickname', 'email', 'phone', 'tgId'
    ];

    public function __construct(string $nickname, string $email = null, string $phone = null, int $tgId = null)
    {
        $this->nickname = $nickname;
        $this->email = $email;
        $this->phone = $phone;
        $this->tgId = $tgId;
    }
}