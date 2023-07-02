<?php

declare(strict_types=1);

namespace Confirm\Controllers;

use Confirm\Messages\ConfirmCodeMessage;
use Confirm\Service\Code\CodeDto;
use Confirm\Service\Code\CodeGeneratorInterface;
use Core\ActionInterface;
use Core\Queue\MessageBusInterface;
use Core\Stream\StreamInterface;
use JetBrains\PhpStorm\ArrayShape;
use Settings\Service\ResolveUserSetting;

readonly class UserSettingChangeController implements ActionInterface
{
    public function __construct(private StreamInterface $stream, private ResolveUserSetting $service, private CodeGeneratorInterface $codeGenerator, private MessageBusInterface $bus)
    {

    }

    public function __invoke(
        #[ArrayShape([
            'user' => 'string',
            'setting' => 'string',
            'value' => 'string',
            'method' => 'string',
        ])] string ...$params
    ): void {
        $value = $params['value'] ?? null;
        $userId = (int)($params['user'] ?? null);
        $userSetting = $this->service->handle($userId, $params['setting'] ?? null);

        if ($userSetting->value !== $value || $userSetting->isNew()) {
            $code = $this->codeGenerator->pack(new CodeDto($userSetting->setting, $userSetting->value));
            $this->bus->dispatch(new ConfirmCodeMessage($userId, $code, $params['method'] ?? null));
            $this->stream->write('<p>Отправлен запрос на подтверждение изменений настроек</p>');
            $this->stream->write(sprintf('<p>Введите код подтверждения <strong>%s</strong> на следующий странице</p>', $code));
        } else {
            $this->stream->write('<p>Обновление настройки пропущено</p>');
        }
    }
}