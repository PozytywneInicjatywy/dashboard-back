<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\Domain;

interface SchoolClassRepository
{
    /**
     * @param string $class
     *
     * @throws Exception\SchoolClassNotFoundException
     *
     * @return SchoolClass
     */
    public function byClassName(string $class): SchoolClass;
}
