<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use App\Form\UserRegisterType;


class RegisterController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function index(): Response
    {
        $user = new User();
        $form = $this->createForm(UserRegisterType::class, $user);


        return $this->render('register/register.index.html.twig', [
            'controller_name' => 'RegisterController',
            'user' => $user,
            'form' => $form,
        ]);
    }

}