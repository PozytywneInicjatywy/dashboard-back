<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\UserInterface\Web;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * GET /admin/login
     *
     * @param AuthenticationUtils $authUtils
     *
     * @param CsrfTokenManagerInterface $csrfTokenManager
     * @return Response
     */
    public function login(AuthenticationUtils $authUtils, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $lastUsername = $authUtils->getLastUsername();
        $error = null !== $authUtils->getLastAuthenticationError();
        $csrfToken = $csrfTokenManager->getToken('authenticate');

        return $this->render('@admin/login.twig', [
            'lastUsername' => $lastUsername,
            'error' => $error,
            'csrfToken' => $csrfToken
        ]);
    }
}
