<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\Infrastructure\Doctrine;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use PozytywneInicjatywy\Dashboard\Domain\Exception\LessonNotFoundException;
use PozytywneInicjatywy\Dashboard\Domain\Lesson;
use PozytywneInicjatywy\Dashboard\Domain\LessonRepository as DomainLessonRepository;
use PozytywneInicjatywy\Dashboard\Domain\SchoolClass;

class LessonRepository extends EntityRepository implements DomainLessonRepository
{
    /**
     * {@inheritdoc}
     */
    public function byClassDayAndHour(SchoolClass $class, int $dayOfWeek, int $hour): Lesson
    {
        $lesson = $this
            ->createQueryBuilder('l')
            ->select('l')
            ->leftJoin('l.class', 'c', Join::WITH, 'l.class = c')
            ->leftJoin('l.lessonHour', 'h', Join::WITH, 'l.lessonHour = h')
            ->where('l.class = :class')
            ->andWhere('l.dayOfWeek = :dayOfWeek')
            ->andWhere('h.id = :hour')
            ->setParameter('class', $class)
            ->setParameter('dayOfWeek', $dayOfWeek)
            ->setParameter('hour', $hour)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $lesson) {
            throw new LessonNotFoundException(sprintf(
                'Lesson of %s class, %d day and %d hour does not exist',
                $class->getName(),
                $dayOfWeek,
                $hour
            ));
        }

        return $lesson;
    }

    /**
     * {@inheritdoc}
     */
    public function save(Lesson $lesson): void
    {
        $this->getEntityManager()->persist($lesson);
        $this->getEntityManager()->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Lesson $lesson): void
    {
        $this->getEntityManager()->remove($lesson);
        $this->getEntityManager()->flush();
    }
}
