<?php

namespace App\Controller;

use App\Entity\Controlefidelite;
use App\Form\ControlefideliteType;
use App\Repository\ControlefideliteRepository;
use App\Repository\InstrumentRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/controlefidelite')]
class ControlefideliteController extends AbstractController
{
    #[Route('/', name: 'app_controlefidelite')]
    public function index(ControlefideliteRepository $controlefideliteRepository): Response
    {
        $user = $this->getUser();
        $controlefidelite = $controlefideliteRepository->findBy(['deletedAt' => NULL]);
        return $this->render('controlefidelite/index.html.twig', [
            'controlefidelite' => $controlefidelite,
        ]);
    }
    #[Route('/create', name: 'app_controlefidelite_create', methods: ['GET', 'POST'])]
public function create (Request $request, InstrumentRepository $instrumentRepository,EntityManagerInterface $em): Response
    {
    $controlefidelite = new Controlefidelite();
    
    $instrument = $instrumentRepository->findBy([],['id' => 'DESC', 'deletedAt' => NULL]);
    $form = $this->createForm(ControlefideliteType::class, $controlefidelite,['instrument' => $instrument]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $this->addFlash('message', 'Enregistrement avec succès');
        $correcte = $form['fidelitecorrecte']->getData();
        $controlefidelite->setFidelitecorrecte($correcte);
        $em->persist($controlefidelite);
        $em->flush();
        return $this->redirectToRoute('app_controlefidelite_create');
    }
     return $this->render('controlefidelite/create.html.twig', [
        'form' => $form->createview(),
     ]);
    }
    #[Route('/create', name: 'app_controlefidelite_create', methods: ['GET', 'POST'])]
    public function createsimple (Request $request, InstrumentRepository $instrumentRepository,EntityManagerInterface $em): Response
        {
        $controlefidelite = new Controlefidelite();
        
        $instrument = $instrumentRepository->findBy([],['id'=>'DESC']);
        $form = $this->createForm(ControlefideliteType::class, $controlefidelite,['instrument' => $instrument]);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('message', 'Enregistrement avec succès');
            $correcte = $form['fidelitecorrecte']->getData();
            $controlefidelite->setFidelitecorrecte($correcte);
            $em->persist($controlefidelite);
            $em->flush();
            return $this->redirectToRoute('app_controlefidelite_create');
        }
         return $this->render('controlefidelite/create.html.twig', [
            'form' => $form->createview(),
         ]);
        }
 #[Route('/edit/{id}', name: 'app_controlefidelite_edit', methods: ['GET', 'POST'])]
 public function edit (Request $request, $id,  ControlefideliteRepository $controlefideliteRepository, EntityManagerInterface $em): Response
 {
    $controlefidelite = $controlefideliteRepository->find($id);
    if (!$controlefidelite) {
        throw $this->createNotFoundException('Aucun controlefidelite trouvé '. $id);
    }
    $form = $this->createForm(ControlefideliteType::class, $controlefidelite);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isvalid()){
        $em->flush();
        return$this->redirectToRoute('app_controlefidelite');
    }
    return $this->render('controlefidelite/edit.html.twig',[
        'form'=> $form->createview(),
    ]);
 }
 
 #[Route('/delete/{id}', name: 'app_controlefidelite_delete', methods: ['GET','POST'])]
 public function delete(Request $request,$id, Controlefidelite $controlefidelite, ControlefideliteRepository $controlefideliteRepository, EntityManagerInterface $em): Response
 {
    $controlefidelite = $controlefideliteRepository->find($id);
    $controlefidelite->setdeletedAt( new DateTime("now"));
         $em->flush();
 
     return $this->redirectToRoute('app_controlefidelite', [], Response::HTTP_SEE_OTHER);
 }
 #[Route('/suivant', name: 'app_controlefidelite_suivant', methods: ['GET', 'POST'])]
 public function suivant (Request $request, InstrumentRepository $instrumentRepository,EntityManagerInterface $em): Response
  {
     $controlefidelite = new Controlefidelite();
     
     $instrument = $instrumentRepository->findBy([],['id'=>'DESC'],1);
     $form = $this->createForm(ControlefideliteType::class, $controlefidelite,['instrument' => $instrument]);
     $form->handleRequest($request);
 
     if ($form->isSubmitted() && $form->isValid()) {
         $this->addFlash('message', 'Enregistrement avec succès');
         $correcte = $form['fidelitecorrecte']->getData();
         $controlefidelite->setFidelitecorrecte($correcte);
         $em->persist($controlefidelite);
         $em->flush();
         return $this->redirectToRoute('app_controlejustesse_suivant', [], Response::HTTP_SEE_OTHER);
     }
      return $this->render('controlefidelite/create.html.twig', [
         'form' => $form->createview(),
      ]);
  }
 
}
