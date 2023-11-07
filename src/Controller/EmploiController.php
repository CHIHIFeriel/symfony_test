<?php

namespace App\Controller;

use App\Entity\Emplois;
use App\Entity\Personnes;
use App\Repository\EmploisRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/emploi')]
class EmploiController extends AbstractController
{
    #[Route('/', name: 'app_emploi')]
    public function index(): Response
    {
        return $this->render('emploi/index.html.twig', [
            'controller_name' => 'EmploiController',
        ]);
    }

    #[Route('/{id}/add-emploi', name: 'add_emploi_to_personne', methods: ['POST'])]
    public function addEmploi(Request $request, Personnes $personne, EmploisRepository $emploisRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $emploi = new Emplois();
        $emploi->setNomEntreprise($data['nomEntreprise']);
        $emploi->setPost($data['poste']);
        $emploi->setDateDebut(new \DateTime($data['dateDebut']));

        if (isset($data['dateFin'])) {
            $emploi->setDateFin(new \DateTime($data['dateFin']));
        }

        $personne->addEmploi($emploi);

        $emploisRepository->save($emploi);

        return new JsonResponse(['message' => 'Emploi ajouté avec succès'], JsonResponse::HTTP_CREATED);
    }
}
