<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\Infrastructure\Doctrine;

use Doctrine\ORM\EntityRepository;
use PozytywneInicjatywy\Dashboard\Domain\SubjectRepository as DomainSubjectRepository;

class SubjectRepository extends EntityRepository implements DomainSubjectRepository
{
    /**
     * {@inheritdoc}
     */
    public function all(): array
    {
        return $this->findAll();
    }
}
