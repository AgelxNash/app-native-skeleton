<?php

declare(strict_types=1);

namespace Confirm\Subscribers;

use Confirm\Messages\ConfirmCodeMessage;
use Confirm\Service\Senders\FactoryInterface;
use Core\Queue\MessageInterface;
use Core\Queue\MessageSubscriberInterface;
use Users\Repository\UserRepositoryInterface;

readonly class SendCodeSubscriber implements MessageSubscriberInterface
{
    public function __construct(
        private FactoryInterface $senderFactory,
        private UserRepositoryInterface $userRepository
    ) {

    }

    public function __invoke(ConfirmCodeMessage|MessageInterface $message)
    {
        $this->senderFactory->factory($message->method)
            ->send(
                $message->code,
                $this->userRepository->findById($message->userId)
            );
    }
}