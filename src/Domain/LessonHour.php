<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\Domain;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
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
}
