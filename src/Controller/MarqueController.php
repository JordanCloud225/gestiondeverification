<?php

namespace App\Controller;

use App\Entity\Marque; 
use App\Form\MarqueType;
use App\Repository\MarqueRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/marque')]
class MarqueController extends AbstractController
{
    #[Route('/', name: 'app_marque')]
    public function index(MarqueRepository $marqueRepository): Response
    {
        $user = $this->getUser();
        $marque = $marqueRepository->findBy(['deletedAt' => NULL]);
        return $this->render('marque/index.html.twig', [
            'marque' => $marque,
        ]);
    }
    #[Route('/marque', name: 'app_marque_create', methods: ['GET', 'POST'])]
    public function create (Request $request, EntityManagerInterface $em): Response
     {
        $marque = new Marque();
        $form = $this->createForm(MarqueType::class, $marque);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('message', 'Enregistrement avec succès');
            $em->persist($marque);
            $em->flush();
            return $this->redirectToRoute('app_marque', [], Response::HTTP_SEE_OTHER);
        }
         return $this->render('marque/create.html.twig', [
            'form' => $form->createview(),
         ]);
         
     }

     #[Route('/edit/{id}', name: 'app_marque_edit', methods: ['GET', 'POST'])]
     public function edit (Request $request, $id, MarqueRepository $marquerepository, EntityManagerInterface $em): Response
     {
        $marque = $marquerepository->find($id);
        if (!$marque) {
            throw $this->createNotFoundException('Aucune marque trouvé '. $id);
        }
        $form = $this->createForm(MarqueType::class, $marque);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isvalid()){
            $em->flush();
            return$this->redirectToRoute('app_marque');
        }
        return $this->render('marque/edit.html.twig',[
            'form'=> $form->createview(),
        ]);
     }
    
     #[Route('/delete/{id}', name: 'app_marque_delete', methods: ['GET','POST'])]
     public function delete(Request $request, $id, Marque $marque, MarqueRepository $marqueRepository, EntityManagerInterface $em): Response
     {
        $marque = $marqueRepository->find($id);
        $marque->setdeletedAt( new DateTime ("now"));
             $em->flush();
     
         return $this->redirectToRoute('app_marque', [], Response::HTTP_SEE_OTHER);
     }
}
