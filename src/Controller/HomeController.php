<?php

namespace App\Controller;

use App\Repository\DogRepository;
use App\Repository\WalkRepository;
use App\Repository\WalkRegistrationRepository;
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
        WalkRegistrationRepository $walkRegistrationRepository,
        Request $request,
        TrailRepository $trailRepository
    ): Response {

        $nextWalks = $walkRepository->findNext(4);

        $currentUser = $this->getUser();

        if ($currentUser) {
            $dogs = $dogRepository->findByUser($currentUser);
            foreach ($dogs as $dog) {
                $wr = $walkRegistrationRepository->findNextWalkByDog($dog, $currentUser);
                $dogNextWalks[$dog->getId()] = $wr;
            }
        } else {
            $dogs = [];
            $dogNextWalk = "";
        }


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
            $foundTrails = $trailRepository->findAllLimit(8);
        }


        return $this->render('home/index.html.twig', [
            'nextWalks'   => $nextWalks,
            'dogs'        => $dogs,
            'dogNextWalks' => $dogNextWalks,
            'form'        => $searchForm->createView(),
            'foundTrails' => $foundTrails,
        ]);
    }
}
