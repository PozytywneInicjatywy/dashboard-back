<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\UserInterface\Web;

use PozytywneInicjatywy\Dashboard\Domain\Exception\UserNotFoundException;
use PozytywneInicjatywy\Dashboard\Domain\User;
use PozytywneInicjatywy\Dashboard\Domain\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

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

    /**
     * GET /admin/user/new
     *
     * @return Response
     */
    public function new(): Response
    {
        return $this->render('@admin/user/form.twig', ['user' => null]);
    }

    /**
     * POST /admin/user
     *
     * @param Request $request
     *
     * @return Response
     */
    public function newPost(Request $request): Response
    {
        // TODO: Validation stuff

        $user = new User();
        $user->setUsername($request->get('username'));
        $user->setEmail($request->get('email'));
        $user->setPassword($request->get('password'));
        $user->setRoles($request->get('roles'));
        $this->userRepository->save($user);

        $this->addFlash('messages.success', sprintf('Pomyślnie dodano użytkownika <b>%s</b>.', $user->getUsername()));

        return $this->redirectToRoute('admin.user.home');
    }

    /**
     * GET /admin/user/{user}
     *
     * @param string $user
     *
     * @return Response
     */
    public function edit(string $user): Response
    {
        try {
            $user = $this->userRepository->byId(intval($user));
        } catch (UserNotFoundException $e) {
            throw $this->createNotFoundException();
        }

        return $this->render('@admin/user/form.twig', ['user' => $user]);
    }

    /**
     * PATCH /admin/user/{user}
     *
     * @param Request $request
     * @param string $user
     *
     * @return Response
     */
    public function editPost(Request $request, string $user, EncoderFactoryInterface $encoderFactory): Response
    {
        try {
            $user = $this->userRepository->byId(intval($user));
        } catch (UserNotFoundException $e) {
            throw $this->createNotFoundException();
        }

        $encoder = $encoderFactory->getEncoder(User::class);

        $user->setUsername($request->get('username'));
        $user->setEmail($request->get('email'));

        if ('' !== $request->get('password')) {
            $user->setPassword(
                $encoder->encodePassword($request->get('password'), '')
            );
        }

        $user->setRoles($request->get('roles'));

        $this->userRepository->save($user);

        $this->addFlash('messages.success', sprintf(
            'Pomyślnie zaktualizowano użytkownika <b>%s</b>.<br><br>Aby zmiana odniosła skutek, użytkownik musi się wylogować.',
            $user->getUsername()
        ));

        return $this->redirectToRoute('admin.user.home');
    }

    /**
     * DELETE /admin/user/{user}
     *
     * @param string $user
     *
     * @return Response
     */
    public function delete(string $user): Response
    {
        $user = intval($user);

        if (1 === $user) {
            throw $this->createNotFoundException();
        }

        try {
            $user = $this->userRepository->byId($user);
        } catch (UserNotFoundException $e) {
            throw $this->createNotFoundException();
        }

        $this->userRepository->delete($user);

        $this->addFlash('messages.success', sprintf('Pomyślnie usunięto użytkownika <b>%s</b>.', $user->getUsername()));

        return $this->redirectToRoute('admin.user.home');
    }
}
