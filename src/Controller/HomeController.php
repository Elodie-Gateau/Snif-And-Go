<?php

namespace App\Controller;

use App\Repository\DogRepository;
use App\Repository\WalkRepository;
use App\Form\TrailSearchType;
use App\Repository\TrailRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(
        WalkRepository $walkRepository,
        DogRepository $dogRepository,
        Request $request,
        TrailRepository $trailRepository
    ): Response {

        $nextWalks = $walkRepository->findNext(4);

        $dogs = $dogRepository->findAll();

        $searchForm = $this->createForm(TrailSearchType::class);
        $searchForm->handleRequest($request);

        $criteria = $searchForm->getData() ?? [];

        $foundTrails = [];
        if (
            !empty($criteria['search']) || !empty($criteria['difficulty'])
            || !empty($criteria['minDistance']) || !empty($criteria['maxDistance'])
            || !empty($criteria['minDuration']) || !empty($criteria['maxDuration'])
            || !empty($criteria['minScore']) || !empty($criteria['maxScore'])
        ) {
            $foundTrails = $trailRepository->search($criteria);
        } else {
            $foundTrails = $trailRepository->findAll();
        }


        return $this->render('home/index.html.twig', [
            'nextWalks'   => $nextWalks,
            'dogs'        => $dogs,
            'form'        => $searchForm->createView(),
            'foundTrails' => $foundTrails,
        ]);
    }
}
