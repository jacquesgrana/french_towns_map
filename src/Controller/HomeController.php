<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\TownRepository;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(TownRepository $townRepository): Response
    {
        $townsGps = $townRepository->findByBoundingBox(43.520769, 3.752716, 43.700769, 4.000716);

        $townsName = $townRepository->findByName('Montp');

        return $this->render('home/index.html.twig', 
        [
            'townsGps' => $townsGps,
            'townsName' => $townsName
        ]);
    }

    #[Route('/', name: 'app_home_redirect')]
    public function redirectToHome(): Response
    {
        return $this->redirectToRoute('app_home');
    }
}
