<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\Domain;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="\PozytywneInicjatywy\Dashboard\Infrastructure\Doctrine\LessonHourRepository")
 * @ORM\Table(name="lesson_hours")
 */
class LessonHour
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     */
    private $id;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="time")
     */
    private $startsAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="time")
     */
    private $endsAt;

    /**
     * @var Lesson[]
     *
     * @ORM\OneToMany(targetEntity="Lesson", mappedBy="lessonHour", cascade={"remove"})
     */
    private $lessons;

    /**
     * LessonHour constructor.
     */
    public function __construct()
    {
        $this->lessons = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return DateTime
     */
    public function getStartsAt(): DateTime
    {
        return $this->startsAt;
    }

    /**
     * @param DateTime $startsAt
     */
    public function setStartsAt(DateTime $startsAt): void
    {
        $this->startsAt = $startsAt;
    }

    /**
     * @return DateTime
     */
    public function getEndsAt(): DateTime
    {
        return $this->endsAt;
    }

    /**
     * @param DateTime $endsAt
     */
    public function setEndsAt(DateTime $endsAt): void
    {
        $this->endsAt = $endsAt;
    }

    /**
     * @return Lesson[]
     */
    public function getLessons(): array
    {
        return $this->lessons;
    }

    /**
     * @param Lesson $lesson
     */
    public function addLesson(Lesson $lesson): void
    {
        $this->lessons->add($lesson);
    }
}
