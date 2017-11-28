<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\UserInterface\Web;

use PozytywneInicjatywy\Dashboard\Domain\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UserController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * GET /admin/user
     *
     * @return Response
     */
    public function home(): Response
    {
        $users = $this->userRepository->all();

        return $this->render('@admin/user/list.twig', ['users' => $users]);
    }
}
