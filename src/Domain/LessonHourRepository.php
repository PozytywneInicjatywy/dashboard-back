<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\Domain;

interface LessonHourRepository
{
    /**
     * @return array
     */
    public function all(): array;
}
