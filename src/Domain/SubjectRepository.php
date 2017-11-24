<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\Domain;

interface SubjectRepository
{
    /**
     * @return Subject[]
     */
    public function all(): array;
}
