<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\UserInterface\Web;

use DateTime;
use PozytywneInicjatywy\Dashboard\Domain\Exception\NewsNotFoundException;
use PozytywneInicjatywy\Dashboard\Domain\News;
use PozytywneInicjatywy\Dashboard\Domain\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
     * GET /admin/newsfeed
     *
     * @return Response
     */
    public function home(): Response
    {
        $newses = $this->newsRepository->all();

        return $this->render('@admin/newsfeed/list.twig', ['newses' => $newses]);
    }

    /**
     * GET /admin/newsfeed/new
     *
     * @return Response
     */
    public function new(): Response
    {
        return $this->render('@admin/newsfeed/form.twig', ['news' => null]);
    }

    /**
     * POST /admin/newsfeed
     *
     * @param Request $request
     *
     * @return Response
     */
    public function newPost(Request $request): Response
    {
        // TODO: Validation stuff

        $news = new News();
        $news->setTitle($request->get('title'));
        $news->setContent($request->get('content'));
        $news->setPublishedAt(new DateTime());
        $this->newsRepository->save($news);

        $this->addFlash('messages.success', sprintf('Pomyślnie dodano newsa <b>%s</b>.', $news->getTitle()));

        return $this->redirectToRoute('admin.newsfeed.home');
    }

    /**
     * GET /admin/newsfeed/{news}
     *
     * @param string $news
     *
     * @return Response
     */
    public function edit(string $news): Response
    {
        try {
            $news = $this->newsRepository->byId(intval($news));
        } catch (NewsNotFoundException $e) {
            throw $this->createNotFoundException();
        }

        return $this->render('@admin/newsfeed/form.twig', ['news' => $news]);
    }

    /**
     * PATCH /admin/newsfeed/{news}
     *
     * @param Request $request
     * @param string $news
     *
     * @return Response
     */
    public function editPost(Request $request, string $news): Response
    {
        try {
            $news = $this->newsRepository->byId(intval($news));
        } catch (NewsNotFoundException $e) {
            throw $this->createNotFoundException();
        }

        $news->setTitle($request->get('title'));
        $news->setContent($request->get('content'));
        $this->newsRepository->save($news);

        $this->addFlash('messages.success', sprintf('Pomyślnie zaktualizowano newsa <b>%s</b>.', $news->getTitle()));

        return $this->redirectToRoute('admin.newsfeed.home');
    }

    /**
     * DELETE /admin/newsfeed/{news}
     *
     * @param string $news
     *
     * @return Response
     */
    public function delete(string $news): Response
    {
        try {
            $news = $this->newsRepository->byId(intval($news));
        } catch (NewsNotFoundException $e) {
            throw $this->createNotFoundException();
        }

        $this->newsRepository->delete($news);

        $this->addFlash('messages.success', sprintf('Pomyślnie usunięto newsa <b>%s</b>.', $news->getTitle()));

        return $this->redirectToRoute('admin.newsfeed.home');
    }
}
