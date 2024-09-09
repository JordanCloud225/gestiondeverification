<?php

namespace App\Controller;

use App\Repository\BailRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UpdateController extends AbstractController {

    #[Route('/update', name: 'app_update')]
    public function index(): Response {
        return $this->render('update/index.html.twig', [
                    'controller_name' => 'UpdateController',
        ]);
    }

    #[Route('/editbail', name: 'app_bail_update', methods: ['GET', 'POST'])]
    public function updatePartsAction(Request $request, BailRepository $bailRepository, EntityManagerInterface $entityMananger) {


        $bail = $bailRepository->findAll();

        if (!$bail) {
            throw $this->createNotFoundException('Unable to find Parts entity to edit.');
        }
        if ($request->isMethod("POST") ) {
            foreach ($bail as $bails) {
                $agence = $bail->getPartagence();
                $mont = $bail->getMontant();
                $calcul = $agence * $mont;
                $result = $calcul / 100;
                $bail->setTotalagence($result);
            }
            //$bail->setPath('get values from request');
            $entityMananger->persist($bail);
            $entityMananger->flush();
        }
            return $this->render('update/index.html.twig', [
                    'controller_name' => 'UpdateController',
        ]);
    }

}
