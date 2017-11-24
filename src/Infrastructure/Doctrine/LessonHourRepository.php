<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\Infrastructure\Doctrine;

use Doctrine\ORM\EntityRepository;
use PozytywneInicjatywy\Dashboard\Domain\Exception\LessonHourNotFoundException;
use PozytywneInicjatywy\Dashboard\Domain\LessonHour;
use PozytywneInicjatywy\Dashboard\Domain\LessonHourRepository as DomainLessonHourRepository;
use PozytywneInicjatywy\Dashboard\Domain\SchoolClass;

class LessonHourRepository extends EntityRepository implements DomainLessonHourRepository
{
    /**
     * {@inheritdoc}
     */
    public function byId(int $id): LessonHour
    {
        /** @var LessonHour|null $lessonHour */
        $lessonHour = $this->find($id);

        if (null == $lessonHour) {
            throw new LessonHourNotFoundException(sprintf(
                'Lesson hour with id %d does not exist.',
                $id
            ));
        }

        return $lessonHour;
    }

    /**
     * {@inheritdoc}
     */
    public function all(): array
    {
        return $this->findAll();
    }
}
