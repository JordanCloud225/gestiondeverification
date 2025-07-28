<?php

namespace App\Controller;

use App\Entity\Tolerance;
use App\Form\ToleranceType;
use App\Repository\ToleranceRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tolerance')]
class ToleranceController extends AbstractController
{
    #[Route('/', name: 'app_tolerance')]
    public function index(ToleranceRepository $ToleranceRepository): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $user = $this->getUser();
        $tolerance = $ToleranceRepository->findBy(['deletedAt' => NULL]);
        return $this->render('tolerance/index.html.twig', [
            'tolerance' => $tolerance,
        ]);
    }
    #[Route('/create', name: 'app_tolerance_create', methods: ['GET', 'POST'])]
    public function create (Request $request, EntityManagerInterface $em): Response
     {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $tolerance = new tolerance();
        $form = $this->createForm(ToleranceType::class, $tolerance);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('message', 'Enregistrement avec succès');
            $tolerance->setCreatedAt(new DateTime("now"));
            $tolerance->setIdentreprise($this->getUser()->getEntreprise()->getId());
            $em->persist($tolerance);
            $em->flush();
            return $this->redirectToRoute('app_tolerance');
        }
         return $this->render('tolerance/create.html.twig', [
            'form' => $form->createview(),
         ]);
         
     }

     #[Route('/edit/{id}', name: 'app_tolerance_edit', methods: ['GET', 'POST'])]
     public function edit (Request $request, $id, ToleranceRepository $tolerancerepository, EntityManagerInterface $em): Response
     {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");        $tolerance = $tolerancerepository->find($id);
        if (!$tolerance) {
            throw $this->createNotFoundException('Aucun tolerance trouvé '. $id);
        }
        $form = $this->createForm(ToleranceType::class, $tolerance);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isvalid()){
            $em->flush();
            return$this->redirectToRoute('app_tolerance');
        }
        return $this->render('tolerance/edit.html.twig',[
            'form'=> $form->createview(),
        ]);
     }
    
     #[Route('/delete/{id}', name: 'app_tolerance_delete', methods: ['GET','POST'])]
     public function delete(Request $request, $id, Tolerance $tolerance, ToleranceRepository $toleranceRepository, EntityManagerInterface $em): Response
     {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $tolerance = $toleranceRepository->find($id);
        $tolerance->setdeletedAt( new DateTime ("now"));
             $em->flush();
     
         return $this->redirectToRoute('app_tolerance', [], Response::HTTP_SEE_OTHER);
     }
}
