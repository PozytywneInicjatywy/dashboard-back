<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="\PozytywneInicjatywy\Dashboard\Infrastructure\Doctrine\LessonRepository")
 * @ORM\Table(name="lessons")
 */
class Lesson
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
     * @var Subject
     *
     * @ORM\ManyToOne(targetEntity="Subject")
     */
    private $subject;

    /**
     * @var LessonHour
     *
     * @ORM\ManyToOne(targetEntity="LessonHour")
     */
    private $lessonHour;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $dayOfWeek;

    /**
     * @var string
     *
     * @ORM\Column()
     */
    private $room;

    /**
     * @var SchoolClass
     *
     * @ORM\ManyToOne(targetEntity="SchoolClass", inversedBy="lessons")
     */
    private $class;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Subject
     */
    public function getSubject(): Subject
    {
        return $this->subject;
    }

    /**
     * @param Subject $subject
     */
    public function setSubject(Subject $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return LessonHour
     */
    public function getLessonHour(): LessonHour
    {
        return $this->lessonHour;
    }

    /**
     * @param LessonHour $lessonHour
     */
    public function setLessonHour(LessonHour $lessonHour): void
    {
        $this->lessonHour = $lessonHour;
    }

    /**
     * @return int
     */
    public function getDayOfWeek(): int
    {
        return $this->dayOfWeek;
    }

    /**
     * @param int $dayOfWeek
     */
    public function setDayOfWeek(int $dayOfWeek)
    {
        $this->dayOfWeek = $dayOfWeek;
    }

    /**
     * @return string
     */
    public function getRoom(): string
    {
        return $this->room;
    }

    /**
     * @param string $room
     */
    public function setRoom(string $room)
    {
        $this->room = $room;
    }

    /**
     * @return SchoolClass
     */
    public function getClass(): SchoolClass
    {
        return $this->class;
    }

    /**
     * @param SchoolClass $class
     */
    public function setClass(SchoolClass $class)
    {
        $this->class = $class;
    }
}
