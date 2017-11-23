<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="\PozytywneInicjatywy\Dashboard\Infrastructure\Doctrine\SchoolClassRepository")
 * @ORM\Table(name="classes")
 */
class SchoolClass
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
     * @var string
     *
     * @ORM\Column(unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column()
     */
    private $displayName;

    /**
     * @var Lesson[]
     *
     * @ORM\OneToMany(targetEntity="Lesson", mappedBy="class")
     */
    private $lessons;

    /**
     * SchoolClass constructor.
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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    /**
     * @param string $displayName
     */
    public function setDisplayName(string $displayName): void
    {
        $this->displayName = $displayName;
    }

    /**
     * @return Collection
     */
    public function getLessons(): Collection
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
