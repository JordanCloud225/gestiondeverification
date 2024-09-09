<?php

namespace App\Controller;

use App\Entity\Controlejustesse;
use App\Form\ControlejustesseType;
use App\Repository\ControlejustesseRepository;
use App\Repository\InstrumentRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/controlejustesse')]
class ControlejustesseController extends AbstractController
{
    #[Route('/', name: 'app_controlejustesse')]
    public function index(ControlejustesseRepository $controlejustesseRepository): Response
    {
        $user = $this->getUser();
        $controlejustesse = $controlejustesseRepository->findBy(['deletedAt' => NULL]);
        return $this->render('controlejustesse/index.html.twig', [
            'controlejustesse' => $controlejustesse,
        ]);
    }
    #[Route('/create', name: 'app_controlejustesse_create', methods: ['GET', 'POST'])]
public function create (Request $request, EntityManagerInterface $em): Response
 {
    $controlejustesse = new Controlejustesse();
    $form = $this->createForm(ControlejustesseType::class, $controlejustesse);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $this->addFlash('message', 'Enregistrement avec succès');
        $em->persist($controlejustesse);
        $em->flush();
        return $this->redirectToRoute('app_controlejustesse_create');
    }
     return $this->render('controlejustesse/create.html.twig', [
        'form' => $form->createview(),
     ]);
     
 }
 #[Route('/edit/{id}', name: 'app_controlejustesse_edit', methods: ['GET', 'POST'])]
 public function edit (Request $request, $id,  ControlejustesseRepository $controlejustesserepository, EntityManagerInterface $em): Response
 {
    $controlejustesse = $controlejustesserepository->find($id);
    if (!$controlejustesse) {
        throw $this->createNotFoundException('Aucun controle de justesse trouvé '. $id);
    }
    $form = $this->createForm(ControlejustesseType::class, $controlejustesse);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isvalid()){
        $em->flush();
        return$this->redirectToRoute('app_controlejustesse');
    }
    return $this->render('controlejustesse/edit.html.twig',[
        'form'=> $form->createview(),
    ]);
 }
 #[Route('/delete/{id}', name: 'app_controlejustesse_delete', methods: ['GET','POST'])]
 public function delete(Request $request,$id, Controlejustesse $controlejustesse, ControlejustesseRepository $controlejustesseRepository, EntityManagerInterface $em): Response
 {
    $controlejustesse = $controlejustesseRepository->find($id);
    $controlejustesse->setdeletedAt( new DateTime("now"));
         $em->flush();
 
     return $this->redirectToRoute('app_controlejustesse', [], Response::HTTP_SEE_OTHER);
 }
 
 #[Route('/suivant', name: 'app_controlejustesse_suivant', methods: ['GET', 'POST'])]
 public function suivant (Request $request,InstrumentRepository $instrumentRepository, EntityManagerInterface $em): Response
  {
     $controlejustesse = new Controlejustesse();
     $instrument = $instrumentRepository->findBy([],['id'=>'DESC'],1);
     $form = $this->createForm(ControlejustesseType::class, $controlejustesse, ['instrument' => $instrument]);
     $form->handleRequest($request);
 
     if ($form->isSubmitted() && $form->isValid()) {
         $this->addFlash('message', 'Enregistrement avec succès');
         $em->persist($controlejustesse);
         $em->flush();
         return $this->redirectToRoute('app_controledexcentration_suivant', [], Response::HTTP_SEE_OTHER);
     }
      return $this->render('controlejustesse/create.html.twig', [
         'form' => $form->createview(),
      ]);
      
  }
}
