<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\Infrastructure\Doctrine;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use PozytywneInicjatywy\Dashboard\Domain\Exception\SchoolClassNotFoundException;
use PozytywneInicjatywy\Dashboard\Domain\Lesson;
use PozytywneInicjatywy\Dashboard\Domain\LessonHour;
use PozytywneInicjatywy\Dashboard\Domain\LessonHourRepository;
use PozytywneInicjatywy\Dashboard\Domain\SchoolClass;
use PozytywneInicjatywy\Dashboard\Domain\SchoolClassRepository as DomainSchoolClassRepository;

class SchoolClassRepository extends EntityRepository implements DomainSchoolClassRepository
{
    /**
     * @var LessonHourRepository
     */
    private $lessonHourRepository;

    /**
     * @param LessonHourRepository $lessonHourRepository
     */
    public function setLessonHourRepository(LessonHourRepository $lessonHourRepository): void
    {
        $this->lessonHourRepository = $lessonHourRepository;
    }

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

        // Fill mapped lessons with 2d array of lessons.
        $lessonHours = $this->lessonHourRepository->all();
        foreach ($classes as $class) {
            $this->fillWithMappedTimetable($class, $lessonHours);
        }

        return $classes;
    }

    /**
     * @param SchoolClass $class
     * @param LessonHour[] $lessonHours
     */
    private function fillWithMappedTimetable(SchoolClass $class, array $lessonHours)
    {
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

        // Fill blank cells of timetable with nulls.
        foreach ($lessonHours as $hour) {
            for ($i = 1; $i <= 5; $i++) {
                if (isset($array[$i])) {
                    if (!isset($array[$i][$hour->getId()])) {
                        $array[$i][$hour->getId()] = null;
                    }
                } else {
                    $array[$i] = [null, null, null, null, null];
                }
            }
        }

        $class->setMappedLessons($array);
    }
}
