<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\Domain;

use PozytywneInicjatywy\Dashboard\Domain\Exception\LessonNotFoundException;

interface LessonRepository
{
    /**
     * @param SchoolClass $class
     * @param int $dayOfWeek
     * @param int $hour
     *
     * @throws LessonNotFoundException
     *
     * @return Lesson
     */
    public function byClassDayAndHour(SchoolClass $class, int $dayOfWeek, int $hour): Lesson;

    /**
     * @param Lesson $lesson
     */
    public function save(Lesson $lesson): void;

    /**
     * @param Lesson $lesson
     */
    public function delete(Lesson $lesson): void;
}
