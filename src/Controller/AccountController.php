<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/user/account')]
class AccountController extends AbstractController
{
    //private $tokenStorage;

    public function __construct()  // TokenStorageInterface $tokenStorage
    {
        //$this->tokenStorage = $tokenStorage;
    }

    #[Route('/', name: 'app_user_account')]
    public function index()
    {
        $user = $this->getUser();
        return $this->render('account/account.index.html.twig', [
            'user' => $user
        ]);
    }
}