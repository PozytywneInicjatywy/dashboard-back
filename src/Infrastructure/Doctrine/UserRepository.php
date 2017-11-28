<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\Infrastructure\Doctrine;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use PozytywneInicjatywy\Dashboard\Domain\Exception\UserNotFoundException;
use PozytywneInicjatywy\Dashboard\Domain\User;
use PozytywneInicjatywy\Dashboard\Domain\UserRepository as DomainUserRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRepository extends EntityRepository implements DomainUserRepository
{

    /**
     * {@inheritdoc}
     */
    public function byId(int $id): User
    {
        /** @var User|null $user */
        $user = $this->find($id);

        if (null === $user) {
            throw new UserNotFoundException(sprintf(
                'User with id %d does not exist.',
                $id
            ));
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        try {
            return $this
                ->createQueryBuilder('u')
                ->select('u')
                ->where('u.username = :username')
                ->orWhere('u.email = :username')
                ->setParameter(':username', $username)
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException $e) {
            throw new UsernameNotFoundException();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function all(): array
    {
        return $this->findAll();
    }

    /**
     * {@inheritdoc}
     */
    public function save(User $user): void
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function delete(User $user): void
    {
        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$this->supportsClass($user)) {
            throw new UnsupportedUserException();
        }

        return $this->findBy(['username' => $user->getUsername()])[0];
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return $class instanceof User;
    }
}
