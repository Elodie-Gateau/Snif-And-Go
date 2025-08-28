<?php

namespace App\Controller;

use App\Repository\WalkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(WalkRepository $walkRepository): Response
    {
        $nextWalks = $walkRepository->findNext(4);

        return $this->render('home/index.html.twig', [
            'nextWalks' => $nextWalks
        ]);
    }
}
