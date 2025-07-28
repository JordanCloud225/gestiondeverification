<?php

namespace App\Controller;

use App\Entity\Conditionsetalonnage;
use App\Form\ConditionsetalonnageType;
use App\Repository\ConditionsetalonnageRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/conditionsetalonnage')]

class ConditionsetalonnageController extends AbstractController
{
    #[Route('/', name: 'app_conditionsetalonnage')]
    public function index(ConditionsetalonnageRepository $conditionsetalonnageRepository): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $user = $this->getUser();
        $conditionsetalonnage = $conditionsetalonnageRepository->findBy(['deletedAt' => NULL]);
        return $this->render('conditionsetalonnage/index.html.twig', [
            'conditionsetalonnage' => $conditionsetalonnage,
        ]);
    }

    #[Route('/create', name: 'app_conditionsetalonnage_create', methods: ['GET', 'POST'])]
    public function create (Request $request, EntityManagerInterface $em): Response
     {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $conditionsetalonnage = new Conditionsetalonnage();
        $form = $this->createForm(ConditionsetalonnageType::class, $conditionsetalonnage);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('message', 'Enregistrement avec succès');
            $conditionsetalonnage->setCreatedAt(new DateTime("now"));
            $conditionsetalonnage->setIdentreprise($this->getUser()->getEntreprise()->getId());
            $em->persist($conditionsetalonnage);
            $em->flush();
            return $this->redirectToRoute('app_conditionsetalonnage');
        }
         return $this->render('conditionsetalonnage/create.html.twig', [
            'form' => $form->createview(),
         ]);
         
     }
     #[Route('/edit/{id}', name: 'app_conditionsetalonnage_edit', methods: ['GET', 'POST'])]
     public function edit (Request $request, $id,  ConditionsetalonnageRepository $conditionsetalonnagerepository, EntityManagerInterface $em): Response
     {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $conditionsetalonnage = $conditionsetalonnagerepository->find($id);
        if (!$conditionsetalonnage) {
            throw $this->createNotFoundException('Aucune conditionsetalonnage trouvé '. $id);
        }
        $form = $this->createForm(ConditionsetalonnageType::class, $conditionsetalonnage);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isvalid()){
            $em->flush();
            return$this->redirectToRoute('app_conditionsetalonnage');
        }
        return $this->render('conditionsetalonnage/edit.html.twig',[
            'form'=> $form->createview(),
        ]);
     }

     #[Route('/delete/{id}', name: 'app_conditionsetalonnage_delete', methods: ['GET','POST'])]
     public function delete(Request $request, $id, Conditionsetalonnage $conditionsetalonnage, ConditionsetalonnageRepository $conditionsetalonnageRepository, EntityManagerInterface $em): Response
     {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $conditionsetalonnage = $conditionsetalonnageRepository->find($id);
        $conditionsetalonnage->setdeletedAt( new DateTime ("now"));
             $em->flush();
     
         return $this->redirectToRoute('app_conditionsetalonnage', [], Response::HTTP_SEE_OTHER);
     }

}


