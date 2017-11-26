<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\Domain;

interface SchoolClassRepository
{
    /**
     * @param LessonHourRepository $lessonHourRepository
     *
     * @internal
     */
    public function setLessonHourRepository(LessonHourRepository $lessonHourRepository): void;

    /**
     * @param int $id
     *
     * @throws Exception\SchoolClassNotFoundException
     *
     * @return SchoolClass
     */
    public function byId(int $id): SchoolClass;

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

    /**
     * @param SchoolClass $class
     */
    public function save(SchoolClass $class): void;

    /**
     * @param SchoolClass $class
     */
    public function delete(SchoolClass $class): void;
}
