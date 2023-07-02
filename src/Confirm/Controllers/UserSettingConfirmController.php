<?php

declare(strict_types=1);

namespace Confirm\Controllers;

use Confirm\Service\Code\CodeGeneratorInterface;
use Confirm\Service\Code\InvalidCodeException;
use Core\ActionInterface;
use Core\Stream\StreamInterface;
use JetBrains\PhpStorm\ArrayShape;
use Settings\Service\ChangeSetting;

readonly class UserSettingConfirmController implements ActionInterface
{
    public function __construct(
        private StreamInterface $stream,
        private ChangeSetting $service,
        private CodeGeneratorInterface $codeGenerator
    ) {

    }

    public function __invoke(
        #[ArrayShape([
            'user' => 'string',
            'setting' => 'string',
            'code' => 'string'
        ])] string ...$params
    ): void {
        $userSetting = $this->service->resolveUserSettings(
            (int)($params['user'] ?? null),
            $params['setting'] ?? null
        );

        try {
            $codeDto = $this->codeGenerator->unpack($params['code'] ?? null, $userSetting->setting);
        } catch (InvalidCodeException) {
            $this->stream->write('<p>Некорректный код подверждения</p>');
            return;
        }

        $status = $this->service->handle($userSetting, $codeDto->value);

        if ($status) {
            $this->stream->write('<p>Настройки обновлены</p>');
            return;
        }

        $this->stream->write('<p>Обновление настройки пропущено</p>');
    }
}