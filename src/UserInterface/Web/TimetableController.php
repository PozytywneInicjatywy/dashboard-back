<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\UserInterface\Web;

use PozytywneInicjatywy\Dashboard\Domain\Exception\LessonNotFoundException;
use PozytywneInicjatywy\Dashboard\Domain\Exception\SchoolClassNotFoundException;
use PozytywneInicjatywy\Dashboard\Domain\LessonHourRepository;
use PozytywneInicjatywy\Dashboard\Domain\LessonRepository;
use PozytywneInicjatywy\Dashboard\Domain\SchoolClassRepository;
use PozytywneInicjatywy\Dashboard\Domain\Subject;
use PozytywneInicjatywy\Dashboard\Domain\SubjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TimetableController extends Controller
{
    /**
     * @var SchoolClassRepository
     */
    private $classRepository;

    /**
     * @var LessonHourRepository
     */
    private $lessonHourRepository;

    /**
     * @var LessonRepository
     */
    private $lessonRepository;

    /**
     * @var SubjectRepository
     */
    private $subjectRepository;

    /**
     * TimetableController constructor.
     *
     * @param SchoolClassRepository $classRepository
     * @param LessonHourRepository $lessonHourRepository
     * @param LessonRepository $lessonRepository
     * @param SubjectRepository $subjectRepository
     */
    public function __construct(
        SchoolClassRepository $classRepository,
        LessonHourRepository $lessonHourRepository,
        LessonRepository $lessonRepository,
        SubjectRepository $subjectRepository
    ) {
        $this->classRepository = $classRepository;
        $this->lessonHourRepository = $lessonHourRepository;
        $this->lessonRepository = $lessonRepository;
        $this->subjectRepository = $subjectRepository;
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
        $lessonHours = $this->lessonHourRepository->all();

        return $this->render('@admin/timetable/timetables.twig', [
            'classes' => $classes,
            'lessonHours' => $lessonHours
        ]);
    }

    /**
     * GET /admin/timetable/{class}
     *
     * @param string $class
     *
     * @return Response
     */
    public function editTimetable(string $class): Response
    {
        try {
            $class = $this->classRepository->byClassName($class);
        } catch (SchoolClassNotFoundException $e) {
            throw $this->createNotFoundException();
        }

        $lessonHours = $this->lessonHourRepository->all();

        return $this->render('@admin/timetable/edit_timetable.twig', [
            'class' => $class,
            'lessonHours' => $lessonHours
        ]);
    }

    /**
     * @param Request $request
     * @param string $class
     * @param int $day
     * @param int $lessonHour
     *
     * @return Response
     */
    public function changeLessonSubject(Request $request, string $class, int $day, int $lessonHour): Response
    {
        $subjects = $this->subjectRepository->all();

        $subjects = array_map(function (Subject &$subject) {
            $subject = [
                'id' => $subject->getId(),
                'name' => $subject->getName()
            ];
        }, $subjects);

        return $this->json([
            'subjects' => $subjects
        ]);
    }

    /**
     * DELETE /admin/timetable/{class}/{day}/{lessonHour}
     *
     * @param string $class
     * @param int $day
     * @param int $lessonHour
     *
     * @return Response
     */
    public function deleteLessonSubject(string $class, int $day, int $lessonHour): Response
    {
        try {
            $class = $this->classRepository->byClassName($class);
        } catch (SchoolClassNotFoundException $e) {
            throw $this->createNotFoundException();
        }

        try {
            $lesson = $this->lessonRepository->byClassDayAndHour($class, $day, $lessonHour);
        } catch (LessonNotFoundException $e) {
            throw $this->createNotFoundException();
        }

        $this->lessonRepository->delete($lesson);

        return $this->json(['message' => 'Lesson has been successfully deleted.'], Response::HTTP_NO_CONTENT);
    }
}
