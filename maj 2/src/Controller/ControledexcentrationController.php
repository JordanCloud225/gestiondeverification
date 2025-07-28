<?php

namespace App\Controller;

use App\Entity\Controledexcentration;
use App\Form\ControledexcentrationType;
use App\Repository\ControledexcentrationRepository;
use App\Repository\InstrumentRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/controledexcentration')]
class ControledexcentrationController extends AbstractController
{
    #[Route('/', name: 'app_controledexcentration')]
    public function index(ControledexcentrationRepository $controledexcentrationRepository): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $user = $this->getUser();
        $controledexcentration = $controledexcentrationRepository->findBy(['deletedAt' => NULL]);
        return $this->render('controledexcentration/index.html.twig', [
            'controledexcentration' => $controledexcentration,
        ]);
    }
    #[Route('/create', name: 'app_controledexcentration_create', methods: ['GET', 'POST'])]
public function create (Request $request, InstrumentRepository $instrumentRepository, EntityManagerInterface $em): Response
 {
    $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");  
    $ide = $this->getUser()->getEntreprise()->getId();
    $instrument = $instrumentRepository->findBy([],['id'=>'DESC']);
    $form = $this->createForm(ControledexcentrationType::class,NULL,['instrument' => $instrument, 'ide' => $ide]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $idinstrument = $request->request->all()['controledexcentration']['instrument'];
        $instrument = $instrumentRepository->find($idinstrument);
        $data = $request->request->all()['data'];
            $this->addFlash('message', 'Enregistrement avec succès');
        $tolerance = $instrument->getTolerance()->getValeur();

            foreach ($data as $dataItem) {
                $ecart = (int)$dataItem['valeurnominale'] - (int)$dataItem['indication'];
                $excentrationcorrecte = ($ecart <= $tolerance && $ecart >= -$tolerance) ? 'oui' : 'non';
                $controledexcentration = new Controledexcentration();
                $controledexcentration->setInstrument($instrument);
                $controledexcentration->setNumposition((int)$dataItem['numposition']);  
                $controledexcentration->setValeurnominale((int)$dataItem['valeurnominale']);  
                $controledexcentration->setIndicationlue((int)$dataItem['indication']);  
                $controledexcentration->setIndicationsurcharge((int)$dataItem['IndSurSen']);  
                $controledexcentration->setEcartreleve((int)$dataItem['ecartreleve']);  
                $controledexcentration->setExcentrationcorrecte($excentrationcorrecte);
                $controledexcentration->setIdentreprise($ide);
                $controledexcentration->setCreatedAt(new DateTime("now"));
                $controledexcentration->setCreatedBy($this->getUser()->getId());
                $em->persist($controledexcentration);
                $em->flush();
            }
        return $this->redirectToRoute('app_controledexcentration');
    }
     return $this->render('controledexcentration/create.html.twig', [
        'form' => $form->createview(),
     ]);
     
 }
 #[Route('/edit/{id}', name: 'app_controledexcentration_edit', methods: ['GET', 'POST'])]
 public function edit (Request $request, $id,  ControledexcentrationRepository $controledexcentrationrepository, EntityManagerInterface $em): Response
 {
    $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
    $controledexcentration = $controledexcentrationrepository->find($id);
    if (!$controledexcentration) {
        throw $this->createNotFoundException('Aucun controledexcentration trouvé '. $id);
    }
    $numposition = $controledexcentration->getNumposition();
    $instrument = $controledexcentration->getInstrument();
    $valeurnominale = $controledexcentration->getValeurnominale();
    $indicationlue = $controledexcentration->getIndicationlue();
    $indicationsurcharge = $controledexcentration->getIndicationsurcharge();
    $ecartreleve = $controledexcentration->getEcartreleve();
    $form = $this->createForm(ControledexcentrationType::class, $controledexcentration);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isvalid()){
        $tolerance = $instrument->getTolerance()->getValeur();

        
        $data = $request->request->all()['data'];
        foreach ($data as $dataItem) {
            $ecart = (int)$dataItem['valeurnominale'] - (int)$dataItem['indication'];
            $excentrationcorrecte = ($ecart <= $tolerance && $ecart >= -$tolerance) ? 'oui' : 'non';
            $controledexcentration->setNumposition((int)$dataItem['numposition']);  
            $controledexcentration->setValeurnominale((int)$dataItem['valeurnominale']);  
            $controledexcentration->setIndicationlue((int)$dataItem['indication']);  
            $controledexcentration->setIndicationsurcharge((int)$dataItem['IndSurSen']);  
            $controledexcentration->setEcartreleve((int)$dataItem['ecartreleve']);  
            $controledexcentration->setExcentrationcorrecte($excentrationcorrecte);
            $controledexcentration->setUpdatedAt(new DateTime("now"));
            $controledexcentration->setUpdatedBy($this->getUser()->getId());
            $em->persist($controledexcentration);
            $em->flush();
            }
        return$this->redirectToRoute('app_controledexcentration');
    }
    return $this->render('controledexcentration/edit.html.twig',[
        'form'=> $form->createview(),
        'controledexcentration' => $controledexcentration,
        'numposition' => $numposition,
        'instrument' => $instrument,
        'valeurnominale' => $valeurnominale,
        'indicationlue' => $indicationlue,
        'indicationsurcharge' => $indicationsurcharge,
        'ecartreleve' => $ecartreleve,
    ]);
 }
 #[Route('/delete/{id}', name: 'app_controledexcentration_delete', methods: ['GET','POST'])]
 public function delete(Request $request,$id, Controledexcentration $controledexcentration,ControledexcentrationRepository $controledexcentrationrepository, EntityManagerInterface $em): Response
 {
    $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
    $controledexcentration = $controledexcentrationrepository->find($id);
    $controledexcentration->setdeletedAt( new DateTime("now"));
         $em->flush();

 
     return $this->redirectToRoute('app_controledexcentration', [], Response::HTTP_SEE_OTHER);
 }
 #[Route('/suivant', name: 'app_controledexcentration_suivant', methods: ['GET', 'POST'])]
 public function suivant (Request $request,InstrumentRepository $instrumentRepository ,EntityManagerInterface $em): Response
  {
    $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
     $controledexcentration = new Controledexcentration();
     $instrument = $instrumentRepository->findBy([],['id'=>'DESC'],1);
     $form = $this->createForm(ControledexcentrationType::class, $controledexcentration,['instrument' => $instrument]);
     $form->handleRequest($request);
 
     if ($form->isSubmitted() && $form->isValid()) {
         $this->addFlash('message', 'Enregistrement avec succès');
         $correcte = $form['excentrationcorrecte']->getData();
         $controledexcentration->setExcentrationcorrecte($correcte);
         $em->persist($controledexcentration);
         $em->flush();
         return $this->redirectToRoute('app_instrument', [], Response::HTTP_SEE_OTHER);
     }
      return $this->render('controledexcentration/create.html.twig', [
         'form' => $form->createview(),
      ]);
      
  }
}
