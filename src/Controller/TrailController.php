<?php

namespace App\Controller;

use App\Entity\Trail;
use App\Entity\Photo;
use App\Form\TrailType;
use App\Repository\TrailRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Service\GeocodingService;
use App\Service\DistanceService;
use App\Service\GpxService;



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
    public function new(
        Request $request,
        EntityManagerInterface $em,
        GeocodingService $geocoding,
        DistanceService $distanceService,
        GpxService $gpx,
        SluggerInterface $slugger,
        TrailRepository $trailRepository
    ): Response {
        // On prérempli le user avec l'user connecté
        $user = $this->getUser();
        $trail = new Trail();
        $trail->setUser($user);


        $form = $this->createForm(TrailType::class, $trail)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // On vérifie quel mode d'entrée des données est choisi : manuel ou fichier gpx
            $mode = $trail->getInputMode();

            // Si téléchargement d'un fichier GPX : on le stock sur server et on enregistre son lien
            if ($mode === 'gpx' && $file = $form->get('gpxFile')->getData()) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
                $file->move($this->getParameter('gpx_directory'), $newFilename);
                $trail->setGpxFile($newFilename);

                // On récupère le fichier récemment enregistré
                $filePath = $this->getParameter('gpx_directory') . '/' . $newFilename;

                // On lit le fichier GPX, on le parse (via méthode dans) GPXService
                if ($infos = $gpx->parse($filePath)) {

                    // On récupère la distance calculé en mètres et on la converti en kms ainsi que la durée
                    $meters = $infos['distance_m'];
                    $seconds = $infos['duration_s'];
                    $trail->setDistance(round($meters / 1000, 2));
                    $trail->setDuration(round($seconds / 60, 2));

                    $startLat = $infos['start']['lat'];
                    $startLon = $infos['start']['lon'];
                    $endLat = $infos['end']['lat'];
                    $endLon = $infos['end']['lon'];


                    // On enregistre les latitudes et longitudes des coordonnées début et fin
                    $trail->setStartLat($startLat);
                    $trail->setStartLon($startLon);
                    $trail->setEndLat($endLat);
                    $trail->setEndLon($endLon);

                    // On applique la fonction de reverse dans GeocodingService pour récupérer des adresses
                    // Si des résultats sont trouvés on les appliques à l'objet Trail créé
                    if ($rev = $geocoding->reverse($startLat, $startLon)) {
                        $trail->setStartAddress($rev['street'] ?: ($rev['label'] ?? ''));
                        $trail->setStartCity($rev['city'] ?? '');
                        $trail->setStartCode($rev['postcode'] ?? '');
                    }
                    if ($rev = $geocoding->reverse($endLat, $endLon)) {
                        $trail->setEndAddress($rev['street'] ?: ($rev['label'] ?? ''));
                        $trail->setEndCity($rev['city'] ?? '');
                        $trail->setEndCode($rev['postcode'] ?? '');
                    }
                } else {
                    $this->addFlash('error', "Fichier GPX invalide (aucun point exploitable).");
                    // On retombe sur l’affichage du form en bas
                }
            } else {
                // Si méthode de saisie manuelle des infos :

                // On défini une adresse de départ composée de l'adresse, du code postal et de la ville tout attaché
                $fromStr = trim(sprintf('%s %s %s', $trail->getStartAddress(), $trail->getStartCode(), $trail->getStartCity()));
                // On défini une adresse d'arrivée composée de l'adresse, du code postal et de la ville tout attaché
                $toStr = trim(sprintf('%s %s %s', $trail->getEndAddress(), $trail->getEndCode(), $trail->getEndCity()));

                // On récupère les coordonnées des adresses via la méthode dans le GeocodeService
                $from = $geocoding->geocode($fromStr);
                $to = $geocoding->geocode($toStr);

                // On enregistre les coordonnées dans le Trail nouvellement créé
                if ($from) {
                    $trail->setStartLat($from['lat']);
                    $trail->setStartLon($from['lon']);
                }
                if ($to) {
                    $trail->setEndLat($to['lat']);
                    $trail->setEndLon($to['lon']);
                }

                // Si des données sont trouvées...
                if (isset($from, $to)) {
                    // ... on définit une route avec la méthode osrm de DistanceService
                    $route = $distanceService->osrmFootRoute($from['lat'], $from['lon'], $to['lat'], $to['lon']);


                    if ($route) {
                        // On enregistre la distance convertie en km, et la durée en minutes
                        $trail->setDistance(round($route['distance_m'] / 1000, 2));
                        $trail->setDuration((int) round($route['duration_s'] / 60));
                    } else {
                        // sinon on tente la méthode Haversine + estimation durée
                        $meters = $distanceService->haversine($from['lat'], $from['lon'], $to['lat'], $to['lon']);
                        $km = $meters / 1000;
                        $trail->setDistance(round($km, 2));
                        $trail->setDuration($distanceService->estimateMinutesFromKm($km));
                    }
                }
            }
            $em->persist($trail);
            $em->flush();

            return $this->redirectToRoute('app_trail_index');
        }

        return $this->render('trail/new.html.twig', [
            'form' => $form->createView(),
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
        if ($this->isCsrfTokenValid('delete' . $trail->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($trail);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_trail_index', [], Response::HTTP_SEE_OTHER);
    }
}
