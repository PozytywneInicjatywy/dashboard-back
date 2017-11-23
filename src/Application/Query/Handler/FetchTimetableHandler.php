<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\Application\Query\Handler;

use PozytywneInicjatywy\Dashboard\Application\Exception\SchoolClassNotFoundException;
use PozytywneInicjatywy\Dashboard\Domain\Exception\SchoolClassNotFoundException as DomainSchoolClassNotFoundException;
use PozytywneInicjatywy\Dashboard\Application\Query\FetchTimetableQuery;
use PozytywneInicjatywy\Dashboard\Domain\SchoolClassRepository;

class FetchTimetableHandler
{
    /**
     * @var SchoolClassRepository
     */
    private $classRepository;

    /**
     * FetchTimetableHandler constructor.
     *
     * @param SchoolClassRepository $classRepository
     */
    public function __construct(SchoolClassRepository $classRepository)
    {
        $this->classRepository = $classRepository;
    }

    /**
     * @param FetchTimetableQuery $command
     *
     * @throws SchoolClassNotFoundException
     *
     * @return array
     */
    public function handle(FetchTimetableQuery $command): array
    {
        try {
            $class = $this->classRepository->byClassName($command->getClass());
        } catch (DomainSchoolClassNotFoundException $e) {
            throw new SchoolClassNotFoundException();
        }

        $data = [];

        foreach ($class->getLessons() as $lesson) {
            if (!isset($data[$lesson->getDayOfWeek()])) {
                $data[$lesson->getDayOfWeek()] = [];
            }

            $data[$lesson->getDayOfWeek()][$lesson->getLessonHour()->getId()] = [
                'name' => $lesson->getSubject()->getName(),
                'room' => $lesson->getRoom()
            ];
        }

        return $data;
    }
}
