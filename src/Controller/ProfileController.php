<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Dog;
use App\Entity\Walk;
use App\Entity\Trail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile', methods: ['GET'])]
    public function index(): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }
        $trails = $user->getTrails();
        $dogs = $user->getDogs();

        $walkRegistrations = [];
        foreach ($dogs as $dog) {
            $wrs = $dog->getWalkRegistration();
            foreach ($wrs as $wr) {
                $walkRegistrations[] = $wr;
            }
        }
        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'trails' => $trails,
            'dogs' => $dogs,
            'walkRegistrations' => $walkRegistrations
        ]);
    }
}
