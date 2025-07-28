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
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $user = $this->getUser();
        $controlejustesse = $controlejustesseRepository->findBy(['deletedAt' => NULL]);
        return $this->render('controlejustesse/index.html.twig', [
            'controlejustesse' => $controlejustesse,
        ]);
    }
    #[Route('/create', name: 'app_controlejustesse_create', methods: ['GET', 'POST'])]
public function create (Request $request,InstrumentRepository $instrumentRepository, EntityManagerInterface $em): Response
 {
    $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
    $ide = $this->getUser()->getEntreprise()->getId();
    $instrument = $instrumentRepository->findBy([],['id'=>'DESC']);
    $form = $this->createForm(ControlejustesseType::class,NULL,['instrument' => $instrument, 'ide' => $ide]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $this->addFlash('message', 'Enregistrement avec succès');
            $idinstrument = $request->request->all()['controlejustesse']['instrument'];
            $data = $request->request->all()['data'];
            $instrument = $instrumentRepository->find($idinstrument);
            $tolerance = $instrument->getTolerance()->getValeur();

            foreach ($data as $dataItem) {
            $ecartmoins = (int)$dataItem['valeurnominale'] - (int)$dataItem['valeurmonte'];
            $ecartplus = (int)$dataItem['valeurnominale'] - (int)$dataItem['valeurdescent'];
            $justessecorrecte = ($ecartplus <= $tolerance && $ecartmoins >= -$tolerance) ? 'oui' : 'non';
            $controlejustesse = new Controlejustesse();
            $controlejustesse->setInstrument($instrument);
            $controlejustesse->setIdentreprise($ide);
            $controlejustesse->setPointsdessai((int)$dataItem['pointsdessai']);  
            $controlejustesse->setValeurnominale((int)$dataItem['valeurnominale']);  
            $controlejustesse->setValeurmonte((int)$dataItem['valeurmonte']);  
            $controlejustesse->setValeurdescente((int)$dataItem['valeurdescent']);  
            $controlejustesse->setIndicationsurchage((int)$dataItem['IndSurSen']);  
            $controlejustesse->setEcartreleve((int)$dataItem['ecartreleve']);
            $controlejustesse->setJustessecorrecte($justessecorrecte);  
            $controlejustesse->setCreatedAt(new DateTime("now"));
            $controlejustesse->setCreatedBy($this->getUser()->getId());
            $em->persist($controlejustesse);
            $em->flush();
        }       
        return $this->redirectToRoute('app_controlejustesse');
    }
     return $this->render('controlejustesse/create.html.twig', [
        'form' => $form->createview(),
     ]);
     
 }
 #[Route('/edit/{id}', name: 'app_controlejustesse_edit', methods: ['GET', 'POST'])]
 public function edit (Request $request, $id,  ControlejustesseRepository $controlejustesserepository, EntityManagerInterface $em): Response
 {
    $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
    $controlejustesse = $controlejustesserepository->find($id);
    if (!$controlejustesse) {
        throw $this->createNotFoundException('Aucun controle de justesse trouvé '. $id);
    }
    $pointsdessai = $controlejustesse->getPointsdessai();
    $valeurnominale = $controlejustesse->getValeurnominale();
    $valeurmonte = $controlejustesse->getValeurmonte();
    $valeurdescente = $controlejustesse->getValeurdescente();
    $indicationsurchage = $controlejustesse->getIndicationsurchage();
    $ecartreleve = $controlejustesse->getEcartreleve();
    $instrument = $controlejustesse->getInstrument();

    $form = $this->createForm(ControlejustesseType::class, $controlejustesse);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isvalid()){
        $data = $request->request->all()['data'];
        $tolerance = $instrument->getTolerance()->getValeur();
         foreach ($data as $dataItem) {
                $ecartplus = (int)$dataItem['valeurnominale'] - (int)$dataItem['valeurmonte'];
                $ecartmoins = (int)$dataItem['valeurnominale'] - (int)$dataItem['valeurdescent'];
                $justessecorrecte = ($ecartmoins <= $tolerance && $ecartplus >= -$tolerance) ? 'oui' : 'non';
                $controlejustesse->setPointsdessai((int)$dataItem['pointsdessai']);  
                $controlejustesse->setValeurnominale((int)$dataItem['valeurnominale']);  
                $controlejustesse->setValeurmonte((int)$dataItem['valeurmonte']);  
                $controlejustesse->setValeurdescente((int)$dataItem['valeurdescent']);  
                $controlejustesse->setIndicationsurchage((int)$dataItem['IndSurSen']);  
                $controlejustesse->setEcartreleve((int)$dataItem['ecartreleve']); 
                $controlejustesse->setJustessecorrecte($justessecorrecte); 
                $controlejustesse->setUpdatedAt(new DateTime("now"));
                $controlejustesse->setUpdatedBy($this->getUser()->getId());
                $em->persist($controlejustesse);
                $em->flush();
            }       
            return$this->redirectToRoute('app_controlejustesse');
    }
    return $this->render('controlejustesse/edit.html.twig',[
        'form'=> $form->createview(),
        'pointsdessai' => $pointsdessai,
        'valeurnominale' => $valeurnominale,
        'valeurnominale' => $valeurmonte,
        'valeurdescente' => $valeurdescente,
        'indicationsurchage' => $indicationsurchage,
        'ecartreleve' => $ecartreleve,

    ]);
 }
 #[Route('/delete/{id}', name: 'app_controlejustesse_delete', methods: ['GET','POST'])]
 public function delete(Request $request,$id, Controlejustesse $controlejustesse, ControlejustesseRepository $controlejustesseRepository, EntityManagerInterface $em): Response
 {
    $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
    $controlejustesse = $controlejustesseRepository->find($id);
    $controlejustesse->setdeletedAt( new DateTime("now"));
         $em->flush();
 
     return $this->redirectToRoute('app_controlejustesse', [], Response::HTTP_SEE_OTHER);
 }
 
 #[Route('/suivant', name: 'app_controlejustesse_suivant', methods: ['GET', 'POST'])]
 public function suivant (Request $request,InstrumentRepository $instrumentRepository, EntityManagerInterface $em): Response
  {
    $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
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
