<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\Domain;

use PozytywneInicjatywy\Dashboard\Domain\Exception\LessonHourNotFoundException;

interface LessonHourRepository
{
    /**
     * @param int $id
     *
     * @throws LessonHourNotFoundException
     *
     * @return LessonHour
     */
    public function byId(int $id): LessonHour;

    /**
     * @return array
     */
    public function all(): array;

    /**
     * @param LessonHour $lessonHour
     */
    public function save(LessonHour $lessonHour): void;

    /**
     * @param LessonHour $lessonHour
     */
    public function delete(LessonHour $lessonHour): void;
}
