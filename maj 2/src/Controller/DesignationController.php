<?php

namespace App\Controller;

use App\Entity\Designation;
use App\Form\DesignationType;
use App\Repository\DesignationRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/designation')]
class DesignationController extends AbstractController
{
    #[Route('/', name: 'app_designation')]
    public function index(DesignationRepository $designationRepository): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $user = $this->getUser();
        $designation = $designationRepository->findBy(['deletedAt' => NULL]);
        return $this->render('designation/index.html.twig', [
            'designation' => $designation,
        ]);
    }
    #[Route('/designation', name: 'app_designation_create', methods: ['GET', 'POST'])]
    public function create (Request $request, EntityManagerInterface $em): Response
     {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $designation = new Designation();
        $form = $this->createForm(DesignationType::class, $designation);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('message', 'Enregistrement avec succès');
            $designation->setCreatedAt(new DateTime("now"));
            $designation->setIdentreprise($this->getUser()->getEntreprise()->getId());
            $em->persist($designation);
            $em->flush();
            return $this->redirectToRoute('app_designation', [], Response::HTTP_SEE_OTHER);
        }
         return $this->render('designation/create.html.twig', [
            'form' => $form->createview(),
         ]);
         
     }

     #[Route('/edit/{id}', name: 'app_designation_edit', methods: ['GET', 'POST'])]
     public function edit (Request $request, $id, DesignationRepository $designationrepository, EntityManagerInterface $em): Response
     {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $designation = $designationrepository->find($id);
        if (!$designation) {
            throw $this->createNotFoundException('Aucune designation trouvé '. $id);
        }
        $form = $this->createForm(DesignationType::class, $designation);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isvalid()){
            $em->flush();
            return$this->redirectToRoute('app_designation');
        }
        return $this->render('designation/edit.html.twig',[
            'form'=> $form->createview(),
        ]);
     }
    
     #[Route('/delete/{id}', name: 'app_designation_delete', methods: ['GET','POST'])]
     public function delete(Request $request, $id, Designation $designation, DesignationRepository $designationRepository, EntityManagerInterface $em): Response
     {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $designation = $designationRepository->find($id);
        $designation->setdeletedAt( new DateTime ("now"));
             $em->flush();
     
         return $this->redirectToRoute('app_designation', [], Response::HTTP_SEE_OTHER);
     }
}
