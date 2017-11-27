<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\UserInterface\Api;

use PozytywneInicjatywy\Dashboard\Domain\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class NewsfeedController extends Controller
{
    /**
     * @var NewsRepository
     */
    private $newsRepository;

    /**
     * NewsfeedController constructor.
     *
     * @param NewsRepository $newsRepository
     */
    public function __construct(NewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    /**
     * GET /api/newsfeed/
     *
     * @param string $howMany
     *
     * @return Response
     */
    public function fetch(string $howMany): Response
    {
        $news = $this->newsRepository->newest(intval($howMany));

        $newNews = [];
        foreach ($news as $newsItem) {
            $newNews[] = [
                'title' => $newsItem->getTitle(),
                'content' => $newsItem->getContent(),
                'publishedAt' => $newsItem->getPublishedAt()->getTimestamp(),
                'author' => [
                    'username' => $newsItem->getAuthor()->getUsername()
                ]
            ];
        }

        return $this->json(['news' => $newNews]);
    }
}
