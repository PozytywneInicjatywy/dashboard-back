<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\Domain;

use Symfony\Component\Security\Core\User\UserProviderInterface;

interface UserRepository extends UserProviderInterface
{
    /**
     * @param int $id
     *
     * @throws Exception\UserNotFoundException
     *
     * @return User
     */
    public function byId(int $id): User;

    /**
     * @return User[]
     */
    public function all(): array;

    /**
     * @param User $user
     */
    public function save(User $user): void;

    /**
     * @param User $user
     */
    public function delete(User $user): void;
}
