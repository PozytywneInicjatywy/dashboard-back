<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\Domain;

use PozytywneInicjatywy\Dashboard\Domain\Exception\SubjectNotFoundException;

interface SubjectRepository
{
    /**
     * @param int $id
     *
     * @throws SubjectNotFoundException
     *
     * @return Subject
     */
    public function byId(int $id): Subject;

    /**
     * @return Subject[]
     */
    public function all(): array;

    /**
     * @param Subject $subject
     */
    public function save(Subject $subject): void;
}
