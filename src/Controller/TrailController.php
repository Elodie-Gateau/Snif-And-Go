<?php

namespace App\Controller;

use App\Entity\Trail;
use App\Form\TrailType;
use App\Repository\TrailRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/trail')]
final class TrailController extends AbstractController
{
    #[Route(name: 'app_trail_index', methods: ['GET'])]
    public function index(TrailRepository $trailRepository): Response
    {
        return $this->render('trail/index.html.twig', [
            'trails' => $trailRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_trail_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $trail = new Trail();
        $form = $this->createForm(TrailType::class, $trail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($trail);
            $entityManager->flush();

            return $this->redirectToRoute('app_trail_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trail/new.html.twig', [
            'trail' => $trail,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_trail_show', methods: ['GET'])]
    public function show(Trail $trail): Response
    {
        return $this->render('trail/show.html.twig', [
            'trail' => $trail,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_trail_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Trail $trail, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TrailType::class, $trail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_trail_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trail/edit.html.twig', [
            'trail' => $trail,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_trail_delete', methods: ['POST'])]
    public function delete(Request $request, Trail $trail, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trail->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($trail);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_trail_index', [], Response::HTTP_SEE_OTHER);
    }
}
