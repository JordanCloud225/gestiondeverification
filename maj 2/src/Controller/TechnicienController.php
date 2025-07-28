<?php

namespace App\Controller;

use App\Entity\Technicien;
use App\Form\TechnicienForm;
use App\Repository\TechnicienRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/technicien')]
class TechnicienController extends AbstractController
{
    #[Route('/', name: 'app_technicien', methods: ['GET', 'POST'])]
    public function index(TechnicienRepository $technicienRepository): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $technicien = $technicienRepository->findBy(['deletedAt' => NULL]);
        return $this->render('technicien/index.html.twig', [
            'technicien' => $technicien,
        ]);
    }

    #[Route('/new', name: 'app_technicien_new', methods: ['GET', 'POST'])]
    public function newtechnicien(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $technicien = new Technicien();
        $form = $this->createForm(TechnicienForm::class,$technicien);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $technicien->setCreatedAt(new DateTime("now"));
            $technicien->setIdentreprise($this->getUser()->getEntreprise()->getId());
            $em->persist($technicien);
            $em->flush();
            return $this->redirectToRoute('app_technicien', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('technicien/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_technicien_edit', methods: ['GET', 'POST'])]
    public function edittechnicien (Request $request, $id, TechnicienRepository $technicienRepository, EntityManagerInterface $em): Response
    {
       $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
       $technicien = $technicienRepository->find($id);
       if (!$technicien) {
           throw $this->createNotFoundException('Aucun technicien trouvé '. $id);
       }
       $form = $this->createForm(TechnicienForm::class, $technicien);
   
       $form->handleRequest($request);
   
       if ($form->isSubmitted() && $form->isvalid()){
           $em->flush();
           return $this->redirectToRoute('app_technicien', [], Response::HTTP_SEE_OTHER);
        }
       return $this->render('technicien/edit.html.twig',[
           'form'=> $form->createview(),
       ]);
    }

    #[Route('/delete/{id}', name: 'app_technicien_delete', methods: ['GET','POST'])]
    public function delete(Request $request, $id, Technicien $technicien, TechnicienRepository $technicienRepository, EntityManagerInterface $em): Response
    {
       $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
       $techniciens = $technicienRepository->find($id);
       $techniciens->setdeletedAt( new DateTime("now"));
            $em->flush();
            return $this->redirectToRoute('app_technicien', [], Response::HTTP_SEE_OTHER);
        }
}
