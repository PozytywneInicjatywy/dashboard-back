<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\Application\Query;

class FetchTimetableQuery
{
    /**
     * @var string
     */
    private $class;

    /**
     * FetchTimetableCommand constructor.
     *
     * @param string $class
     */
    public function __construct(string $class)
    {
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }
}