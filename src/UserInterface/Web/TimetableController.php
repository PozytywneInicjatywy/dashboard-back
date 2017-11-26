<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\UserInterface\Web;

use DateTime;
use PozytywneInicjatywy\Dashboard\Domain\Exception\LessonHourNotFoundException;
use PozytywneInicjatywy\Dashboard\Domain\Exception\LessonNotFoundException;
use PozytywneInicjatywy\Dashboard\Domain\Exception\NotFoundException;
use PozytywneInicjatywy\Dashboard\Domain\Exception\SchoolClassNotFoundException;
use PozytywneInicjatywy\Dashboard\Domain\Exception\SubjectNotFoundException;
use PozytywneInicjatywy\Dashboard\Domain\Lesson;
use PozytywneInicjatywy\Dashboard\Domain\LessonHour;
use PozytywneInicjatywy\Dashboard\Domain\LessonHourRepository;
use PozytywneInicjatywy\Dashboard\Domain\LessonRepository;
use PozytywneInicjatywy\Dashboard\Domain\SchoolClass;
use PozytywneInicjatywy\Dashboard\Domain\SchoolClassRepository;
use PozytywneInicjatywy\Dashboard\Domain\Subject;
use PozytywneInicjatywy\Dashboard\Domain\SubjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

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
     * POST /admin/timetable/{class}/{day}/{lessonHour}
     *
     * @param Request $request
     * @param string $class
     * @param string $day
     * @param string $lessonHour
     *
     * @return Response
     */
    public function saveLesson(Request $request, string $class, string $day, string $lessonHour): Response
    {
        $day = intval($day);
        $lessonHour = intval($lessonHour);
        $subjectId = intval($request->get('subject'));

        try {
            $class = $this->classRepository->byClassName($class);

            try {
                $lesson = $this->lessonRepository->byClassDayAndHour($class, $day, $lessonHour);
            } catch (LessonNotFoundException $e) {
                $lesson = new Lesson();
                $lesson->setClass($class);
                $lesson->setDayOfWeek($day);
                $lesson->setRoom('');

                $lesson->setLessonHour(
                    $this->lessonHourRepository->byId($lessonHour)
                );
            }
        } catch (NotFoundException $e) {
            throw $this->createNotFoundException();
        }

        try {
            $subject = $this->subjectRepository->byId($subjectId);
        } catch (SubjectNotFoundException $e) {
            return $this->json(['error' => $e->getMessage()]);
        }

        $lesson->setSubject($subject);
        $this->lessonRepository->save($lesson);

        return $this->json([
            'subjectName' => $lesson->getSubject()->getName(),
            'saveUrl' => $this->generateUrl('admin.timetable.saveLesson', [
                'class' => $class->getName(),
                'day' => $day,
                'lessonHour' => $lessonHour
            ]),
            'deleteUrl' => $this->generateUrl('admin.timetable.deleteLesson', [
                'class' => $class->getName(),
                'day' => $day,
                'lessonHour' => $lessonHour
            ]),
            'hour' => $lessonHour
        ], Response::HTTP_CREATED);
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
    public function deleteLesson(string $class, int $day, int $lessonHour): Response
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

        return $this->json([
            'saveUrl' => $this->generateUrl('admin.timetable.saveLesson', [
                'class' => $class->getName(),
                'day' => $day,
                'lessonHour' => $lessonHour
            ])
        ], Response::HTTP_OK);
    }

    /**
     * GET /admin/timetable/class
     *
     * @return Response
     */
    public function listClasses(): Response
    {
        $classes = $this->classRepository->all();

        return $this->render('@admin/timetable/class/list.twig', ['classes' => $classes]);
    }

    /**
     * GET /admin/timetable/class/new
     *
     * @return Response
     */
    public function newClass(): Response
    {
        return $this->render('@admin/timetable/class/form.twig', ['class' => null]);
    }

    /**
     * POST /admin/timetable/class
     *
     * @param Request $request
     *
     * @return Response
     */
    public function newClassPost(Request $request): Response
    {
        // TODO: Validation stuff

        $class = new SchoolClass();
        $class->setDisplayName($request->get('displayName'));
        $class->setName($request->get('name'));
        $this->classRepository->save($class);

        $this->addFlash('messages.success', sprintf('Pomyślnie dodano klasę <b>%s</b>.', $class->getDisplayName()));

        return $this->redirectToRoute('admin.timetable.class.list');
    }

    /**
     * GET /admin/timetable/class/{class}
     *
     * @param string $class
     *
     * @return Response
     */
    public function editClass(string $class): Response
    {
        try {
            $class = $this->classRepository->byId(intval($class));
        } catch (SchoolClassNotFoundException $e) {
            throw $this->createNotFoundException();
        }

        return $this->render('@admin/timetable/class/form.twig', ['class' => $class]);
    }

    /**
     * PATCH /admin/timetable/class/{class}
     *
     * @param Request $request
     * @param string $class
     *
     * @return Response
     */
    public function editClassPost(Request $request, string $class): Response
    {
        try {
            $class = $this->classRepository->byId(intval($class));
        } catch (SchoolClassNotFoundException $e) {
            throw $this->createNotFoundException();
        }

        // TODO: Validation stuff

        $class->setDisplayName($request->get('displayName'));
        $class->setName($request->get('name'));
        $this->classRepository->save($class);

        $this->addFlash('messages.success', sprintf('Pomyślnie zaktualizowano klasę <b>%s</b>.', $class->getDisplayName()));

        return $this->redirectToRoute('admin.timetable.class.list');
    }

    /**
     * DELETE /admin/timetable/class/{class}
     *
     * @param string $class
     *
     * @return Response
     */
    public function deleteClass(string $class): Response
    {
        try {
            $class = $this->classRepository->byId(intval($class));
        } catch (SchoolClassNotFoundException $e) {
            throw $this->createNotFoundException();
        }

        $this->classRepository->delete($class);

        $this->addFlash('messages.success', 'Pomyślnie usunięto klasę <b>%s</b>.', $class->getDisplayName());

        return $this->redirectToRoute('admin.timetable.class.list');
    }

    /**
     * GET /admin/timetable/subject
     *
     * @param Request $request
     *
     * @return Response
     */
    public function listSubjects(Request $request): Response
    {
        $subjects = $this->subjectRepository->all();

        if (in_array('application/json', $request->getAcceptableContentTypes())) {
            $resultSubjects = [];
            foreach ($subjects as $subject) {
                $resultSubjects[] = [
                    'id' => $subject->getId(),
                    'name' => $subject->getName()
                ];
            };

            return $this->json(['subjects' => $resultSubjects]);
        }

        return $this->render('@admin/timetable/subject/list.twig', ['subjects' => $subjects]);
    }

    /**
     * GET /admin/timetable/subject/new
     *
     * @return Response
     */
    public function newSubject(): Response
    {
        return $this->render('@admin/timetable/subject/form.twig', ['subject' => null]);
    }

    /**
     * POST /admin/timetable/subject
     *
     * @param Request $request
     *
     * @return Response
     */
    public function newSubjectPost(Request $request): Response
    {
        // TODO: Validation stuff

        $subject = new Subject();
        $subject->setName($request->get('name'));
        $this->subjectRepository->save($subject);

        $this->addFlash('messages.success', sprintf('Pomyślnie dodano przedmiot <b>%s</b>.', $subject->getName()));

        return $this->redirectToRoute('admin.timetable.subject.list');
    }

    /**
     * GET /admin/timetable/subject/{subject}
     *
     * @param string $subject
     *
     * @return Response
     */
    public function editSubject(string $subject): Response
    {
        try {
            $subject = $this->subjectRepository->byId(intval($subject));
        } catch (SubjectNotFoundException $e) {
            throw $this->createNotFoundException();
        }

        return $this->render('@admin/timetable/subject/form.twig', ['subject' => $subject]);
    }

    /**
     * PATCH /admin/timetable/subject/{subject}
     *
     * @param Request $request
     * @param string $subject
     *
     * @return Response
     */
    public function editSubjectPost(Request $request, string $subject): Response
    {
        try {
            $subject = $this->subjectRepository->byId(intval($subject));
        } catch (SubjectNotFoundException $e) {
            throw $this->createNotFoundException();
        }

        // TODO: Validation stuff

        $subject->setName($request->get('name'));
        $this->subjectRepository->save($subject);

        $this->addFlash('messages.success', sprintf('Pomyślnie zaktualizowano przedmiot <b>%s</b>.', $subject->getName()));

        return $this->redirectToRoute('admin.timetable.subject.list');
    }

    /**
     * DELETE /admin/timetable/subject/{subject}
     *
     * @param string $subject
     *
     * @return Response
     */
    public function deleteSubject(string $subject): Response
    {
        try {
            $subject = $this->subjectRepository->byId(intval($subject));
        } catch (SubjectNotFoundException $e) {
            throw $this->createNotFoundException();
        }

        $this->subjectRepository->delete($subject);

        $this->addFlash('messages.success', sprintf('Pomyślnie usunięto przedmiot <b>%s</b>.', $subject->getName()));

        return $this->redirectToRoute('admin.timetable.subject.list');
    }

    /**
     * GET /admin/timetable/lesson-hour
     *
     * @return Response
     */
    public function listLessonHour(): Response
    {
        $lessonHours = $this->lessonHourRepository->all();

        return $this->render('@admin/timetable/lesson_hour/list.twig', ['lessonHours' => $lessonHours]);
    }

    /**
     * GET /admin/timetable/lesson-hour/new
     *
     * @return Response
     */
    public function newLessonHour(): Response
    {
        return $this->render('@admin/timetable/lesson_hour/form.twig', ['lessonHour' => null]);
    }

    /**
     * POST /admin/timetable/lesson-hour
     *
     * @param Request $request
     *
     * @return Response
     */
    public function newLessonHourPost(Request $request): Response
    {
        // TODO: Validation stuff

        $lessonHour = new LessonHour();
        $lessonHour->setStartsAt(
            DateTime::createFromFormat('H:i', $request->get('startsAt'))
        );
        $lessonHour->setEndsAt(
            DateTime::createFromFormat('H:i', $request->get('endsAt'))
        );
        $this->lessonHourRepository->save($lessonHour);

        $this->addFlash('messages.success', 'Pomyślnie dodano nową godzinę lekcyjną.');

        return $this->redirectToRoute('admin.timetable.lessonHour.list');
    }

    /**
     * GET /admin/timetable/lesson-hour/{lessonHour}
     *
     * @param string $lessonHour
     *
     * @return Response
     */
    public function editLessonHour(string $lessonHour): Response
    {
        try {
            $lessonHour = $this->lessonHourRepository->byId(intval($lessonHour));
        } catch (LessonHourNotFoundException $e) {
            throw $this->createNotFoundException();
        }

        return $this->render('@admin/timetable/lesson_hour/form.twig', ['lessonHour' => $lessonHour]);
    }

    /**
     * PATCH /admin/timetable/lesson-hour/{lessonHour}
     *
     * @param Request $request
     * @param string $lessonHour
     *
     * @return Response
     */
    public function editLessonHourPost(Request $request, string $lessonHour): Response
    {
        try {
            $lessonHour = $this->lessonHourRepository->byId(intval($lessonHour));
        } catch (LessonHourNotFoundException $e) {
            throw $this->createNotFoundException();
        }

        // TODO: Validation stuff

        $lessonHour->setStartsAt(
            DateTime::createFromFormat('H:i', $request->get('startsAt'))
        );
        $lessonHour->setEndsAt(
            DateTime::createFromFormat('H:i', $request->get('endsAt'))
        );
        $this->lessonHourRepository->save($lessonHour);

        $this->addFlash('messages.success', 'Pomyślnie zaktualizowano godzinę lekcyjną.');

        return $this->redirectToRoute('admin.timetable.lessonHour.list');
    }

    /**
     * DELETE /admin/timetable/lesson-hour/{lessonHour}
     *
     * @param string $lessonHour
     *
     * @return Response
     */
    public function deleteLessonHour(string $lessonHour): Response
    {
        try {
            $lessonHour = $this->lessonHourRepository->byId(intval($lessonHour));
        } catch (LessonHourNotFoundException $e) {
            throw $this->createNotFoundException();
        }

        $this->lessonHourRepository->delete($lessonHour);

        $this->addFlash('messages.success', 'Pomyślnie usunięto godzinę lekcyjną.');

        return $this->redirectToRoute('admin.timetable.lessonHour.list');
    }
}
