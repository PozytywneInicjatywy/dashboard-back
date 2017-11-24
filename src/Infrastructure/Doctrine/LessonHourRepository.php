<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\Infrastructure\Doctrine;

use Doctrine\ORM\EntityRepository;
use PozytywneInicjatywy\Dashboard\Domain\LessonHourRepository as DomainLessonHourRepository;
use PozytywneInicjatywy\Dashboard\Domain\SchoolClass;

class LessonHourRepository extends EntityRepository implements DomainLessonHourRepository
{
    /**
     * @return array
     */
    public function all(): array
    {
        return $this->findAll();
    }
}
