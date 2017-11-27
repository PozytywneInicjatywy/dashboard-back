<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\Infrastructure\Doctrine;

use Doctrine\ORM\EntityRepository;
use PozytywneInicjatywy\Dashboard\Domain\Exception;
use PozytywneInicjatywy\Dashboard\Domain\News;
use PozytywneInicjatywy\Dashboard\Domain\NewsRepository as DomainNewsRepository;

class NewsRepository extends EntityRepository implements DomainNewsRepository
{
    /**
     * {@inheritdoc}
     */
    public function byId(int $id): News
    {
        /** @var News|null $news */
        $news = $this->find($id);

        if (null === $news) {
            throw new Exception\NewsNotFoundException(sprintf(
                'News with id %d does not exist.',
                $id
            ));
        }

        return $news;
    }

    /**
     * {@inheritdoc}
     */
    public function all(): array
    {
        return $this
            ->createQueryBuilder('n')
            ->select('n')
            ->orderBy('n.publishedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * {@inheritdoc}
     */
    public function newest(int $howMany): array
    {
        return $this
            ->createQueryBuilder('n')
            ->select('n')
            ->orderBy('n.publishedAt', 'DESC')
            ->setMaxResults($howMany)
            ->getQuery()
            ->getResult();
    }

    /**
     * {@inheritdoc}
     */
    public function save(News $news): void
    {
        $this->getEntityManager()->persist($news);
        $this->getEntityManager()->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function delete(News $news): void
    {
        $this->getEntityManager()->remove($news);
        $this->getEntityManager()->flush();
    }
}
