<?php

declare(strict_types=1);

namespace Core\Queue;

use Config;
use Core\Dependency\ContainerInterface;
use Core\Serializer\SerializerInterface;
use DomainException;
use LogicException;
use Throwable;

readonly class Bus implements MessageBusInterface, MessageHandlerInterface
{
    public function __construct(
        private BusTransportInterface $transport,
        private SerializerInterface $serializer,
        private ContainerInterface $container
    ) {
    }

    public function dispatch(MessageInterface $message): void
    {
        $this->transport->push(
            $this->serializer->serialize($message)
        );
    }

    public function process(): void
    {
        if (!$data = $this->transport->pop()) {
            return;
        }

        try {
            $message = $this->serializer->unserialize($data);
            $subscribers = $this->getSubscribers($message);
        } catch (Throwable $exception) {
            throw new LogicException(sprintf('Wrong message: %s', $data), 0, $exception);
        }

        foreach ($subscribers as $subscriberClass) {
            if (!in_array(MessageSubscriberInterface::class, class_implements($subscriberClass), true)) {
                throw new DomainException(
                    'Subscriber "%s" has not implement %s',
                    $subscriberClass,
                    MessageSubscriberInterface::class
                );
            }

            try {
                $subscriber = $this->container->get($subscriberClass);
                $subscriber($message);
            } catch (Throwable $exception) {
                throw new LogicException(sprintf('Wrong subscriber %s', $subscriberClass), 0, $exception);
            }
        }
    }

    private function getSubscribers(MessageInterface $message): array
    {
        /** @var array $subscribers */
        $subscribers = $this->container->get(Config::QUEUE_SUBSCRIBERS->name);
        if (!is_array($subscribers) || !array_key_exists($message::class,
                $subscribers) || !is_array($subscribers[$message::class])) {
            return [];
        }

        return $subscribers[$message::class];
    }
}