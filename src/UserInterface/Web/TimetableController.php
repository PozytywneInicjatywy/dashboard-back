<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\UserInterface\Web;

use Doctrine\ORM\EntityRepository;
use PozytywneInicjatywy\Dashboard\Domain\LessonHour;
use PozytywneInicjatywy\Dashboard\Domain\SchoolClassRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class TimetableController extends Controller
{
    /**
     * @var SchoolClassRepository
     */
    private $classRepository;

    /**
     * TimetableController constructor.
     *
     * @param SchoolClassRepository $classRepository
     */
    public function __construct(SchoolClassRepository $classRepository)
    {
        $this->classRepository = $classRepository;
    }

    /**
     * GET /admin/timetable
     *
     * Shows classes timetables.
     *
     * @return Response
     */
    public function home(): Response
    {
        $classes = $this->classRepository->all();
        /** @var EntityRepository $lessonHours */
        $lessonHours = $this->get('doctrine.orm.entity_manager')->getRepository(LessonHour::class);


        return $this->render('@admin/timetable/timetables.twig', [
            'classes' => $classes,
            'lessonHours' => $lessonHours->findAll()
        ]);
    }
}
