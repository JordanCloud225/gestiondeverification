<?php

namespace App\Controller;

use App\Repository\CertificationRepository;
use App\Repository\ClientRepository;
use App\Repository\InstrumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(ClientRepository $clientRepository, InstrumentRepository $instrumentRepository, CertificationRepository $certificationRepository): Response {

    // Récupérer le nombre de clients par mois
    $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
    $clientsThisMonth = $clientRepository->getClientsThisMonth();
    $instrumentsThisMonth = $instrumentRepository->getInstrumentsThisMonth();
    $certificationsThisMonth = $certificationRepository->getCertificationThisMonth();
    $currentMonth = (new \DateTime())->format('F Y'); // Exemple: 'September 2024'
    return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'clientsParMois' => $clientsThisMonth, // On passe les données à la vue
            'currentMonth' => $currentMonth,
            'instrumentsThisMonth'=> $instrumentsThisMonth,
            'certificationsThisMonth'=> $certificationsThisMonth,
        ]);
    }
}