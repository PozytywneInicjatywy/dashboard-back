<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\Infrastructure\Doctrine;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use PozytywneInicjatywy\Dashboard\Domain\Exception\SchoolClassNotFoundException;
use PozytywneInicjatywy\Dashboard\Domain\Lesson;
use PozytywneInicjatywy\Dashboard\Domain\SchoolClass;
use PozytywneInicjatywy\Dashboard\Domain\SchoolClassRepository as DomainSchoolClassRepository;

class SchoolClassRepository extends EntityRepository implements DomainSchoolClassRepository
{
    /**
     * {@inheritdoc}
     */
    public function byClassName(string $name): SchoolClass
    {
        /** @var SchoolClass|null $class */
        $class = $this->findOneBy(['name' => $name]);

        if (null === $class) {
            throw new SchoolClassNotFoundException();
        }

        return $class;
    }

    /**
     * {@inheritdoc}
     */
    public function all(): array
    {
        /** @var SchoolClass[] $classes */
        $classes = $this
            ->createQueryBuilder('c')
            ->select('c', 'l', 'h', 's')
            ->leftJoin('c.lessons', 'l', Join::WITH, 'l.class = c')
            ->leftJoin('l.lessonHour', 'h', Join::WITH, 'l.lessonHour = h')
            ->leftJoin('l.subject', 's', Join::WITH, 'l.subject = s')
            ->getQuery()
            ->getResult();

        foreach ($classes as $class) {
            $array = [];

            /** @var Lesson $lesson */
            foreach ($class->getLessons() as $lesson) {
                $x = $lesson->getDayOfWeek();
                $y = $lesson->getLessonHour()->getId();

                if (!isset($array[$x])) {
                    $array[$x] = [];
                }

                $array[$x][$y] = $lesson;
            }

            $class->setMappedLessons($array);
        }

        return $classes;
    }
}
