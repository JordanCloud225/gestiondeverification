<?php

namespace App\Controller;

use App\Entity\Certification;
use App\Entity\Instrument;
use App\Form\InstrumentType;
use App\Repository\InstrumentRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/instrument')]
class InstrumentController extends AbstractController
{
    #[Route('/', name: 'app_instrument')]
    public function index(InstrumentRepository $instrumentRepository): Response
    {
        $user = $this->getUser();
        $instrument = $instrumentRepository->findBy(['deletedAt' => NULL]);
        return $this->render('instrument/index.html.twig', [
            'instrument' => $instrument,
        ]);
    }
    #[Route('/create', name: 'app_instrument_create', methods: ['GET', 'POST'])]
public function create (Request $request, EntityManagerInterface $em,InstrumentRepository $instrumentRepository): Response
 {
    $instrument = new Instrument();
    $form = $this->createForm(InstrumentType::class, $instrument);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $instruments = $instrumentRepository->findOneBy([],['id'=>'DESC']);
        $id =$instruments->getId();
        if ($id == null) {
                $id = 1;
        }else {
            $incrementation = ++$id;
        }
        $dates = new DateTime();
        $datesnum = $dates->format('mY');
        $numero = 'CVG2B'.$incrementation.'-'.$datesnum;
        $instrument->setCodeinstrument($numero);
        $instrument->setCreatedAt(new DateTime("now"));
        $em->persist($instrument); 
        $em->flush();
        $this->addFlash('message', 'Enregistrement avec succès');
        return $this->redirectToRoute('app_instrument',[],Response::HTTP_SEE_OTHER);
    }
     return $this->render('instrument/create.html.twig', [
        'form' => $form->createview(),
        'instrument' => $instrument,
     ]);
     
 }
 
 #[Route('/edit/{id}', name: 'app_instrument_edit', methods: ['GET', 'POST'])]
 public function edit (Request $request, $id,  InstrumentRepository $instrumentrepository, EntityManagerInterface $em): Response
 {
    $instrument = $instrumentrepository->find($id);
    if (!$instrument) {
        throw $this->createNotFoundException('Aucune instrument trouvé '. $id);
    }
    $form = $this->createForm(InstrumentType::class, $instrument);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isvalid()){
        $em->flush();
        return$this->redirectToRoute('app_instrument');
    }
    return $this->render('instrument/edit.html.twig',[
        'form'=> $form->createview(),
    ]);
 }
 
 #[Route('/delete/{id}', name: 'app_instrument_delete', methods: ['GET','POST'])]
 public function delete(Request $request, $id, Instrument $instrument, InstrumentRepository $instrumentRepository, EntityManagerInterface $em): Response
 {
    $instrument = $instrumentRepository->find($id);
    $instrument->setdeletedAt( new DateTime("now"));
         $em->flush();
 
     return $this->redirectToRoute('app_instrument', [], Response::HTTP_SEE_OTHER);
 }
 
 #[Route('/suivant', name: 'app_instrument_suivant', methods: ['GET', 'POST'])]
 public function suivant (Request $request, EntityManagerInterface $em): Response
  {
     $instrument = new Instrument();
     $form = $this->createForm(InstrumentType::class, $instrument);
     $form->handleRequest($request);
 
     if ($form->isSubmitted() && $form->isValid()) {
         $dates = new DateTime();
         $datesnum = $dates->format('dmY');
         $marque = $form['marque']->getData();
         $numero = 'CV'.$marque.$datesnum;
         $instrument->setCodeinstrument($numero);
         $certificat = new Certification;
         $certificat->setNumerocertificat($numero);
         $certificat->setInstrument($instrument);
         $em->persist($instrument);
         $em->persist($certificat);
         $em->flush();
         $this->addFlash('message', 'Enregistrement avec succès');
         return $this->redirectToRoute('app_controlefidelite_suivant',[],Response::HTTP_SEE_OTHER);
     }
      return $this->render('instrument/create.html.twig', [
         'form' => $form->createview(),
      ]);
      
  }

}
