<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(
        AuthenticationUtils $authenticationUtils,
        Security $security
        ): Response
    {

        if ($security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new RedirectResponse($this->generateUrl('home', ['reinitMap' => true]));
        }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): Response
    {
        return $this->redirectToRoute('app_home');
    }

    #[Route('/check-auth', name: 'check_auth' , methods: ['GET'])]
    public function checkAuthMethod(): JsonResponse
    {
        return $this->json([
            'isLoggedIn' => $this->getUser() !== null
        ]);
    }

    #[Route('/get-user-details', name: 'get_user_details' , methods: ['GET'])]
    public function getUserDetails(): JsonResponse    {

        /** @var \App\Entity\User $user **/
        $user = $this->getUser();
        if($user === null) {
            return $this->json([
                'isLoggedIn' => false
            ]);
        }
        else {
            $userToReturn = [
                'id' => $user->getId(),
                'firstName' => $user->getFirstName(),
                'lastName' => $user->getLastName(),
                'pseudo' => $user->getPseudo(),
                'email' => $user->getEmail()
            ];
        }
        
        return $this->json($userToReturn);
    }
    
}
