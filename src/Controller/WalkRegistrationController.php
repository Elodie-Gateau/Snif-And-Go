<?php

namespace App\Controller;

use App\Entity\WalkRegistration;
use App\Form\WalkRegistrationType;
use App\Repository\WalkRegistrationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\WalkRepository;


#[Route('/walk/registration')]
final class WalkRegistrationController extends AbstractController
{
    #[Route(name: 'app_walk_registration_index', methods: ['GET'])]
    public function index(WalkRegistrationRepository $walkRegistrationRepository): Response
    {
        return $this->render('walk_registration/index.html.twig', [
            'walk_registrations' => $walkRegistrationRepository->findAll(),
        ]);
    }

    #[Route('/new/{walkId}', name: 'app_walk_registration_new', methods: ['GET', 'POST'])]
    public function new(int $walkId, WalkRepository $walkRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $walk = $walkRepository->find($walkId);
        $walkRegistration = new WalkRegistration();
        $walkRegistration->setWalk($walk);

        $form = $this->createForm(WalkRegistrationType::class, $walkRegistration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($walkRegistration);
            $entityManager->flush();

            return $this->redirectToRoute('app_walk_registration_show', ['id' => $walkRegistration->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('walk_registration/new.html.twig', [
            'walk' => $walk,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_walk_registration_show', methods: ['GET'])]
    public function show(WalkRegistration $walkRegistration): Response
    {
        return $this->render('walk_registration/show.html.twig', [
            'walk_registration' => $walkRegistration,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_walk_registration_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, WalkRegistration $walkRegistration, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WalkRegistrationType::class, $walkRegistration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_walk_registration_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('walk_registration/edit.html.twig', [
            'walk_registration' => $walkRegistration,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_walk_registration_delete', methods: ['POST'])]
    public function delete(Request $request, WalkRegistration $walkRegistration, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $walkRegistration->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($walkRegistration);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_walk_registration_index', [], Response::HTTP_SEE_OTHER);
    }
}
