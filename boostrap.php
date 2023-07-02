<?php

declare(strict_types=1);

use Core\Dependency\ContainerInterface;

spl_autoload_register(static function ($className) {
    $fileName = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . $className . '.php';
    if (!is_readable($fileName)) {
        throw new RuntimeException(sprintf('Class "%s" not found int %s', $className, $fileName));
    }

    include_once $fileName;
});

return new Core\Dependency\Container(
    include 'config.php',
    [
        Core\Queue\Transport\FileTransport::class => static function(ContainerInterface $container) {
            return new Core\Queue\Transport\FileTransport($container->get(Config::QUEUE_DSN->name));
        },
        Core\Orm\Storage\FileStorage::class => static function(ContainerInterface $container) {
            return new Core\Orm\Storage\FileStorage(
                $container->get(Config::ORM_DSN->name),
                $container->get(Core\Serializer\SerializerInterface::class)
            );
        },
        Core\Stream\NativeStream::class => static function(ContainerInterface $container) {
            return new Core\Stream\NativeStream(
                $container->get(Config::OUTPUT->name)
            );
        },
        Core\NotFoundController::class => static function(ContainerInterface $container) {
            return new Core\NotFoundController(
                $container->get(Core\Stream\StreamInterface::class),
                $container->get(Config::ACTIONS_LIST->name)
            );
        },
        Core\HelpCommand::class => static function(ContainerInterface $container) {
            return new Core\HelpCommand(
                $container->get(Core\Stream\StreamInterface::class),
                $container->get(Config::ACTIONS_LIST->name)
            );
        },
        Confirm\Service\Code\CodeGenerator::class => static function(ContainerInterface $container) {
            return new Confirm\Service\Code\CodeGenerator(
                $container->get(Config::SALT->name)
            );
        },
    ], [
        Core\Queue\BusTransportInterface::class => Core\Queue\Transport\FileTransport::class,
        Core\Serializer\SerializerInterface::class => Core\Serializer\NativeSerializer::class,
        Core\Queue\MessageHandlerInterface::class => Core\Queue\Bus::class,
        Core\Queue\MessageBusInterface::class => Core\Queue\Bus::class,
        Core\Orm\Storage\StorageInterface::class => Core\Orm\Storage\FileStorage::class,
        Core\Stream\StreamInterface::class => Core\Stream\NativeStream::class,
        Core\Stream\OutputInterface::class => Core\Stream\NativeStream::class,
        Core\Stream\InputInterface::class => Core\Stream\NativeStream::class,
        Settings\Repository\UserSettingsRepositoryInterface::class => Settings\Repository\UserSettingsStorageRepository::class,
        Users\Repository\UserRepositoryInterface::class => Users\Repository\UserStorageRepository::class,
        Confirm\Service\Code\CodeGeneratorInterface::class => Confirm\Service\Code\CodeGenerator::class,
        Confirm\Service\Senders\FactoryInterface::class => Confirm\Service\Senders\Sender::class,
    ]
);