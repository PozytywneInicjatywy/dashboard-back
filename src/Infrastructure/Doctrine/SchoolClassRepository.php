<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\Infrastructure\Doctrine;

use Doctrine\ORM\EntityRepository;
use PozytywneInicjatywy\Dashboard\Domain\Exception\SchoolClassNotFoundException;
use PozytywneInicjatywy\Dashboard\Domain\SchoolClass;
use PozytywneInicjatywy\Dashboard\Domain\SchoolClassRepository as DomainSchoolClassRepository;

class SchoolClassRepository extends EntityRepository implements DomainSchoolClassRepository
{
    /**
     * {@inheritdoc}
     */
    public function byClassName(string $name): SchoolClass
    {
        /** @var SchoolClass|null $class */
        $class = $this->findOneBy(['name' => $name]);

        if (null === $class) {
            throw new SchoolClassNotFoundException();
        }

        return $class;
    }
}
