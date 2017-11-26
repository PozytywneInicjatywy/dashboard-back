<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\Infrastructure\Doctrine;

use Doctrine\ORM\EntityRepository;
use PozytywneInicjatywy\Dashboard\Domain\Exception\SubjectNotFoundException;
use PozytywneInicjatywy\Dashboard\Domain\Subject;
use PozytywneInicjatywy\Dashboard\Domain\SubjectRepository as DomainSubjectRepository;

class SubjectRepository extends EntityRepository implements DomainSubjectRepository
{
    /**
     * {@inheritdoc}
     */
    public function byId(int $id): Subject
    {
        /** @var Subject|null $subject */
        $subject = $this->find($id);

        if (null == $subject) {
            throw new SubjectNotFoundException(sprintf(
                'Subject with id %d does not exist.',
                $id
            ));
        }

        return $subject;
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
    public function save(Subject $subject): void
    {
        $this->getEntityManager()->persist($subject);
        $this->getEntityManager()->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Subject $subject): void
    {
        $this->getEntityManager()->remove($subject);
        $this->getEntityManager()->flush();
    }
}
