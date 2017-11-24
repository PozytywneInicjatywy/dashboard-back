<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\Domain;

interface SchoolClassRepository
{
    public function setLessonHourRepository(LessonHourRepository $lessonHourRepository): void;

    /**
     * @param string $class
     *
     * @throws Exception\SchoolClassNotFoundException
     *
     * @return SchoolClass
     */
    public function byClassName(string $class): SchoolClass;

    /**
     * @return array
     */
    public function all(): array;
}
