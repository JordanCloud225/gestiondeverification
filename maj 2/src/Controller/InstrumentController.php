<?php

namespace App\Controller;

use App\Entity\Certification;
use App\Entity\Instrument;
use App\Entity\Intervention;
use App\Entity\Presence;
use App\Form\InstrumentType;
use App\Form\InterventionForm;
use App\Form\PresenceForm;
use App\Repository\InstrumentRepository;
use App\Repository\InterventionRepository;
use App\Repository\PresenceRepository;
use App\Repository\TechnicienRepository;
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
    $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
    $user = $this->getUser();
    $instrument = $instrumentRepository->findBy(['deletedAt' => NULL]);
    return $this->render('instrument/index.html.twig', [
        'instrument' => $instrument,
    ]);
}
#[Route('/create', name: 'app_instrument_create', methods: ['GET', 'POST'])]
public function create (Request $request, EntityManagerInterface $em,InstrumentRepository $instrumentRepository): Response
 {
     $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
     $ide = $this->getUser()->getEntreprise()->getId();
     $instrument = new Instrument();
     $form = $this->createForm(InstrumentType::class,$instrument,['ide' => $ide]);
     $form->handleRequest($request);
     
     if ($form->isSubmitted()) {
        $instruments = $instrumentRepository->findOneBy([],['id'=>'DESC']);

        if (!$instruments) {
            $incrementation = 1;
        } else {
            $incrementation = $instruments->getId() + 1;
        }

        $dates = new DateTime();
        $datesnum = $dates->format('mY');
        $numero = 'CVG2B'.$incrementation.'-'.$datesnum;
        $instrument->setIdentreprise($this->getUser()->getEntreprise()->getId());
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
    $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
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
    $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
    $instrument = $instrumentRepository->find($id);
    $instrument->setdeletedAt( new DateTime("now"));
         $em->flush();
 
     return $this->redirectToRoute('app_instrument', [], Response::HTTP_SEE_OTHER);
 }
 
 #[Route('/suivant', name: 'app_instrument_suivant', methods: ['GET', 'POST'])]
 public function suivant (Request $request, EntityManagerInterface $em): Response
  {
    $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
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



#[Route('/rapportdintervention', name: 'app_instrument_ficheintervention', methods: ['GET', 'POST'])]
public function rapportdintervention (Request $request, EntityManagerInterface $em,InterventionRepository $interventionRepository): Response
 {
    $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
    $ide = $this->getUser()->getEntreprise()->getId();
    $form = $this->createForm(InterventionForm::class, null, ['ide' => $ide]);
    $form->handleRequest($request);

    if ($form->isSubmitted()) {
        $data = $form->getData();
        $intervention = $interventionRepository->findall();
        $numintervention = $intervention ? end($intervention)->getNumintervention() + 1 : 1 ;

        $fichedintervention = new Intervention();
        $fichedintervention->setNumintervention($numintervention);
        $fichedintervention->setClient($data->getCLient());
        $fichedintervention->setAdresse($data->getAdresse());
        $fichedintervention->setSite($data->getSite());
        $fichedintervention->setInterlocuteur($data->getInterlocuteur());
        $fichedintervention->setContactinterlocuteur($data->getContactinterlocuteur());
        $fichedintervention->setTypedequipement($data->getTypedequipement());
        $fichedintervention->setMarque($data->getMarque());
        $fichedintervention->setModele($data->getModele());
        $fichedintervention->setNumserie($data->getNumserie());
        $fichedintervention->setPorteemaxi($data->getPorteemaxi());
        $fichedintervention->setPorteemini($data->getPorteemini());
        $fichedintervention->setEchelonunite($data->getEchelonunite());
        $fichedintervention->setHeure($data->getHeure());
        $fichedintervention->setDateintervention($data->getDateintervention());
        $fichedintervention->setDemandetravaux($data->getDemandetravaux());
        $fichedintervention->setDetailtravaux($data->getDetailtravaux());
        $fichedintervention->setObservationclient($data->getObservationclient());
        $fichedintervention->setEquipement($data->getEquipement());
        $fichedintervention->setQuantiteequipement($data->getQuantiteequipement());
        $fichedintervention->setSignataire($data->getSignataire());
        $fichedintervention->setSignature($data->getSignature());
        $em->persist($fichedintervention);
        $em->flush();
        $this->addFlash('message', 'Enregistrement avec succès');
        return $this->redirectToRoute('app_instrument_intervention',[],Response::HTTP_SEE_OTHER);
    }
     return $this->render('intervention/fichedintervention.html.twig', [
        'form' => $form->createview(),
     ]);
     
 }
 #[Route('/editintervention/{id}', name: 'app_instrument_edit_intervention', methods: ['GET', 'POST'])]
 public function editintervention (Request $request, $id,  InterventionRepository $interventionRepository, EntityManagerInterface $em): Response
 {
    $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
    $intervention = $interventionRepository->find($id);
    if (!$intervention) {
        throw $this->createNotFoundException('Aucune instrument trouvé '. $id);
    }
    $form = $this->createForm(InterventionForm::class,$intervention);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isvalid()){
        $em->flush();
        return$this->redirectToRoute('app_instrument_edit_presence', ['id' => $intervention->getId()]);
    }
    return $this->render('intervention/editintervention.html.twig',[
        'form'=> $form->createview(),
        'intervention' => $intervention,
    ]);
 }
#[Route('/interventionliste', name: 'app_instrument_intervention', methods: ['GET', 'POST'])]
public function indexintervention(Request $request, EntityManagerInterface $em,InterventionRepository $interventionRepository): Response
{
    $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
    $interventions = $interventionRepository->findby(['deletedAt' => NULL]);
    return $this->render('intervention/listeintervention.html.twig', [
        'interventions' => $interventions,
    ]);
}
 #[Route('/deleteintervention/{id}', name: 'app_instrument_intervention_delete', methods: ['GET','POST'])]
 public function deleteintervention(Request $request, $id, InterventionRepository $interventionRepository, EntityManagerInterface $em): Response
 {
    $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
    $intervention = $interventionRepository->find($id);
    $intervention->setdeletedAt( new DateTime("now"));
    $intervention->setdeletedBy( $this->getUser()->getId());
         $em->flush();
     return $this->redirectToRoute('app_instrument_intervention', [], Response::HTTP_SEE_OTHER);
 }



#[Route('/presence', name: 'app_instrument_presence', methods: ['GET', 'POST'])]
public function presence(Request $request, EntityManagerInterface $em, InterventionRepository $interventionRepository, TechnicienRepository $technicienRepository): Response
{
    $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
    if ($request->isMethod('POST')) {
        $data = $request->request->all();
        $intervertion = null;
        $technicien = null;
        $intervertion = $interventionRepository->find($data['intervention']);
        foreach ($data['technicien'] as $key => $value) {
            $technicien = $technicienRepository->find($value);
            $presence = new Presence();
            $presence->setIntervention($intervertion);
            $presence->setTechnicien($technicien);
            $presence->setPresent(true);
            $em->persist($presence);
            $em->flush();
        }  
        return $this->redirectToRoute('app_instrument', [], Response::HTTP_SEE_OTHER);
    }
    $interventions = $interventionRepository->findBy(['deletedAt' => null]);
    $techniciens = $technicienRepository->findBy(['deletedAt' => null]);
    return $this->render('intervention/presence.html.twig', [
        'interventions' => $interventions,
        'techniciens' => $techniciens,
    ]);
}
#[Route('/editpresence/{id}', name: 'app_instrument_edit_presence', methods: ['GET', 'POST'])]
public function editpresence(Request $request,$id, EntityManagerInterface $em, PresenceRepository $presenceRepository, InterventionRepository $interventionRepository, TechnicienRepository $technicienRepository): Response
{
    $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
    $dataedit = $presenceRepository->findOneBy(['id' => $id]);
    $interventionpresence = $dataedit->getIntervention();
    $presenceedit = $presenceRepository->findBy(['intervention' => $interventionpresence->getId()]);
    $technicien = $technicienRepository->findAll();
    if ($request->isMethod('POST')) {
        $data = $request->request->all();
        $intervertion = $interventionRepository->find($data['intervention']);
        foreach ($data['technicien'] as $key => $value) {
            $presencetechno = $presenceRepository->findOneBy(['technicien' => $value, 'intervention' => $intervertion->getId()]);
            if ($presencetechno) {
                $this->addFlash('message', 'Technicien déjà présent pour cette intervention');
            } else {
                $technicien = $technicienRepository->find($value);
                $presence = new Presence();
                $presence->setIntervention($intervertion);
                $presence->setTechnicien($technicien);
                $presence->setPresent(true);
                $em->persist($presence);
                $em->flush();
            }
            
        }  
        return $this->redirectToRoute('app_instrument', [], Response::HTTP_SEE_OTHER);
    }
    return $this->render('intervention/editpresence.html.twig', [
        'intervention' => $interventionpresence,
        'techniciens' => $technicien,
        'presences' => $presenceedit,
    ]);
}

#[Route('/presenceliste', name: 'app_instrument_presenceliste', methods: ['GET', 'POST'])]
public function indexpresence(Request $request, EntityManagerInterface $em,PresenceRepository $presenceRepository): Response
{
    $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
    $presences = $presenceRepository->findPresenceListe();
    return $this->render('intervention/listepresence.html.twig', [
        'presences' => $presences,
    ]);
}
 #[Route('/deletepresence/{id}', name: 'app_instrument_presence_delete', methods: ['GET','POST'])]
 public function deletepresence(Request $request, $id, Instrument $instrument, PresenceRepository $presenceRepository, EntityManagerInterface $em): Response
 {
    $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
    $presence = $presenceRepository->find($id);
    $presence->setdeletedAt( new DateTime("now"));
    $presence->setdeletedBy( $this->getUser()->getId());
         $em->flush();
 
     return $this->redirectToRoute('app_instrument_presenceliste', [], Response::HTTP_SEE_OTHER);
 }
    
}
