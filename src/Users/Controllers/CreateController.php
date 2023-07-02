<?php

declare(strict_types=1);

namespace Users\Controllers;

use Core\ActionInterface;
use Core\Orm\NotFoundException;
use Core\Stream\StreamInterface;
use JetBrains\PhpStorm\ArrayShape;
use Users\Model\User;
use Users\Repository\UserRepositoryInterface;

readonly class CreateController implements ActionInterface
{
    public function __construct(private StreamInterface $stream, private UserRepositoryInterface $userRepository)
    {
    }

    public function __invoke(
        #[ArrayShape([
            'nickname' => 'string'
        ])] string ...$params
    ): void {
        $this->create($params['nickname'] ?? null);
    }

    private function create(string $nickname): void
    {
        try {
            $model = $this->userRepository->findByNickname($nickname);
            $text = 'User with nickname "%s" is already exists (id: %d)';
        } catch (NotFoundException) {
            $model = $this->userRepository->create(new User($nickname));
            $text = 'Create User with nickname "%s" (id: %d)';
        }

        $this->stream->write(sprintf($text, $nickname, $model->getId()));
    }
}