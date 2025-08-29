<?php

namespace App\Controller;

use App\Repository\DogRepository;
use App\Repository\WalkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(WalkRepository $walkRepository, DogRepository $dogRepository): Response
    {
        $nextWalks = $walkRepository->findNext(4);
        $dogs = $dogRepository->findAll();

        return $this->render('home/index.html.twig', [
            'nextWalks' => $nextWalks,
            'dogs' => $dogs
        ]);
    }
}
