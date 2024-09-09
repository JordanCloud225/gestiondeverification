<?php

namespace App\Controller;

use App\Entity\Modele; 
use App\Form\ModeleType;
use App\Repository\ModeleRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/modele')]
class ModeleController extends AbstractController
{
    #[Route('/', name: 'app_modele')]
    public function index(ModeleRepository $modeleRepository): Response
    {
        $user = $this->getUser();
        $modele = $modeleRepository->findBy(['deletedAt' => NULL]);
        return $this->render('modele/index.html.twig', [
            'modele' => $modele,
        ]);
    }
    #[Route('/modele', name: 'app_modele_create', methods: ['GET', 'POST'])]
    public function create (Request $request, EntityManagerInterface $em): Response
     {
        $modele = new Modele();
        $form = $this->createForm(ModeleType::class, $modele);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('message', 'Enregistrement avec succès');
            $em->persist($modele);
            $em->flush();
            return $this->redirectToRoute('app_modele', [], Response::HTTP_SEE_OTHER);
        }
         return $this->render('modele/create.html.twig', [
            'form' => $form->createview(),
         ]);
         
     }

     #[Route('/edit/{id}', name: 'app_modele_edit', methods: ['GET', 'POST'])]
     public function edit (Request $request, $id, ModeleRepository $modelerepository, EntityManagerInterface $em): Response
     {
        $modele = $modelerepository->find($id);
        if (!$modele) {
            throw $this->createNotFoundException('Aucun modele trouvé '. $id);
        }
        $form = $this->createForm(ModeleType::class, $modele);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isvalid()){
            $em->flush();
            return$this->redirectToRoute('app_modele');
        }
        return $this->render('modele/edit.html.twig',[
            'form'=> $form->createview(),
        ]);
     }
    
     #[Route('/delete/{id}', name: 'app_modele_delete', methods: ['GET','POST'])]
     public function delete(Request $request, $id, Modele $modele, ModeleRepository $modeleRepository, EntityManagerInterface $em): Response
     {
        $modele = $modeleRepository->find($id);
        $modele->setdeletedAt( new DateTime ("now"));
             $em->flush();
     
         return $this->redirectToRoute('app_modele', [], Response::HTTP_SEE_OTHER);
     }
}
