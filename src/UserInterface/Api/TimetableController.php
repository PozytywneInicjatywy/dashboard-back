<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\UserInterface\Api;

use PozytywneInicjatywy\Dashboard\Application\Exception\SchoolClassNotFoundException;
use PozytywneInicjatywy\Dashboard\Application\Query\FetchTimetableQuery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class TimetableController extends Controller
{
    /**
     * GET /api/timetable/{class}
     *
     * @param string $class
     *
     * @return Response
     */
    public function fetch(string $class): Response
    {
        try {
            $data = $this->get('queryHandler.fetchTimetable')->handle(
                new FetchTimetableQuery($class)
            );
        } catch (SchoolClassNotFoundException $e) {
            return $this->json(['error' => 'Requested class does not exist.'], 404);
        }

        return $this->json($data);
    }
}
