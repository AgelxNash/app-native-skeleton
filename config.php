<?php

return [
    Config::ACTIONS_LIST->name => [
        /** Web */
        Core\ActionPrefix::ONLY_WEB->value . 'user/create' => Users\Controllers\CreateController::class,
        Core\ActionPrefix::ONLY_WEB->value . 'user/settings/change' => Confirm\Controllers\UserSettingChangeController::class,
        Core\ActionPrefix::ONLY_WEB->value . 'user/settings/confirm' => Confirm\Controllers\UserSettingConfirmController::class,
        /** CLI */
        Core\ActionPrefix::ONLY_CLI->value . 'queue/process' => Core\Queue\Command::class,
    ],
    Config::ACTION_DEFAULT_CLI->name => Core\HelpCommand::class,
    Config::ACTION_DEFAULT_HTTP->name => Core\NotFoundController::class,
    Config::QUEUE_DSN->name => __DIR__ . '/storage/queue',
    Config::QUEUE_SUBSCRIBERS->name => [
        Confirm\Messages\ConfirmCodeMessage::class => [
            Confirm\Subscribers\SendCodeSubscriber::class
        ],
    ],
    Config::ORM_DSN->name => __DIR__ . '/storage/orm',
    Config::OUTPUT->name => 'php://output',
    Config::SALT->name => 'YouSecretKey',
];