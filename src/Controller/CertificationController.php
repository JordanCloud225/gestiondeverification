<?php

namespace App\Controller;

use App\Entity\Certification;
use App\Entity\Client;
use App\Entity\Controlefidelite;
use App\Entity\Entreprise;
use App\Form\CertificationType;
use App\Repository\CertificationRepository;
use App\Repository\ControledexcentrationRepository;
use App\Repository\ControlefideliteRepository;
use App\Repository\ControlejustesseRepository;
use App\Repository\EntrepriseRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/certification')]

class CertificationController extends AbstractController
{
    #[Route('/', name: 'app_certification')]
    public function index(CertificationRepository $certificationRepository): Response
    {
        $user = $this->getUser();
        $certification = $certificationRepository->findBy(['deletedAt' => NULL]);
        return $this->render('certification/index.html.twig', [
            'certification' => $certification,
        ]);
    }

    #[Route('/create', name: 'app_certification_create', methods: ['GET', 'POST'])]
    public function create (Request $request, EntityManagerInterface $em): Response
     {
        $certification = new Certification();
        $form = $this->createForm(CertificationType::class, $certification);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $instruments = $form['instrument']->getData();
            $codeinstrument = $instruments->getCodeinstrument();
            $certification->setNumerocertificat($codeinstrument);
            $certification->setCreatedAt(new DateTime("now"));
            $em->persist($certification);
            $em->flush();
            return $this->redirectToRoute('app_certification');
        }
         return $this->render('certification/create.html.twig', [
            'form' => $form->createview(),
         ]);
         
     }
     #[Route('/edit/{id}', name: 'app_certification_edit', methods: ['GET', 'POST'])]
     public function edit (Request $request, $id,  CertificationRepository $certificationrepository, EntityManagerInterface $em): Response
     {
        $certification = $certificationrepository->find($id);
        if (!$certification) {
            throw $this->createNotFoundException('Aucune certification trouvé '. $id);
        }
        $form = $this->createForm(CertificationType::class, $certification);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isvalid()){
            $em->flush();
            return$this->redirectToRoute('app_certification');
        }
        return $this->render('certification/edit.html.twig',[
            'form'=> $form->createview(),
        ]);
     }

     #[Route('/delete/{id}', name: 'app_certification_delete', methods: ['GET','POST'])]
     public function delete(Request $request, $id, Certification $certification, CertificationRepository $certificationRepository, EntityManagerInterface $em): Response
     {
        $certification = $certificationRepository->find($id);
        $certification->setdeletedAt( new DateTime ("now"));
             $em->flush();
     
         return $this->redirectToRoute('app_certification', [], Response::HTTP_SEE_OTHER);
     }

     #[Route('/print/{id}', name: 'app_certification_print', methods: ['GET'])]
     public function print(int $id,EntrepriseRepository $entrepriseRepository, CertificationRepository $certificationRepository,Certification $certification,ControlefideliteRepository $controlefideliteRepository, ControlejustesseRepository $controlejustesseRepository, ControledexcentrationRepository $controledexcentrationRepository): Response
     {
        // Récupérer l'entité Certification par ID via l'EntityManager
        $certification = $certificationRepository->find($id);
        $identreprise = 1;
        $entreprise = $entrepriseRepository->find($identreprise);
        $instrument = $certification->getInstrument();
        $porteemax = $instrument->getPorteemax();
        $fidelite = $porteemax / 2;
        $excentration = round($porteemax / 3,-3);
        $tolerance = $certification->getInstrument()->getTolerance();
        $ctrlfidelite = $controlefideliteRepository->findBy(['instrument' => $instrument]);
        $ctrljustesse = $controlejustesseRepository->findBy(['instrument' => $instrument]);
        $ctrlexcentration = $controledexcentrationRepository->findBy(['instrument' => $instrument]);

        $a = [];
    foreach ($ctrlfidelite as $key => $controlefidelite) {
        $valeurNominale = $controlefidelite->getValeurNominale();
        $indicationLue = $controlefidelite->getIndicationLue();
            $margeInferieure = $valeurNominale - 10;
            $margeSuperieure = $valeurNominale + 10;
    
            if ($indicationLue >= $margeInferieure && $indicationLue <= $margeSuperieure) {
                    $a[$key] = 'oui';
            }else{
                $a[$key] = 'non';
            }
            if (in_array('non',$a)){
                $fidelitecorrecte = 'non';
            }else {
                $fidelitecorrecte = 'oui';
            }
        }
              $a = [];
    foreach ($ctrlfidelite as $key => $controlefidelite) {
        $valeurNominale = $controlefidelite->getValeurNominale();
        $indicationLue = $controlefidelite->getIndicationLue();
            $margeInferieure = $valeurNominale - 10;
            $margeSuperieure = $valeurNominale + 10;
    
            if ($indicationLue >= $margeInferieure && $indicationLue <= $margeSuperieure) {
                    $a[$key] = 'oui';
            }else{
                $a[$key] = 'non';
            }
            if (in_array('non',$a)){
                $fidelitecorrecte = 'non';
            }else {
                $fidelitecorrecte = 'oui';
            }
        }
        $b = [];
        foreach ($ctrlexcentration as $key => $controledexcentration) {
            $valeurNominale = $controledexcentration->getValeurNominale();
            $indicationLue = $controledexcentration->getIndicationLue();
                $margeInferieure = $valeurNominale - 10;
                $margeSuperieure = $valeurNominale + 10;
        
                if ($indicationLue >= $margeInferieure && $indicationLue <= $margeSuperieure) {
                        $b[$key] = 'oui';
                }else{
                    $b[$key] = 'non';
                }
                if (in_array('non',$b)){
                    $excentrationcorrecte = 'non';
                }else {
                    $excentrationcorrecte = 'oui';
                }
            }
            $c = [];
            foreach ($ctrljustesse as $key => $controlejustesse) {
                $valeurNominale = $controlejustesse->getValeurnominale();
                $indicationLue = $controlejustesse->getValeurmonte();
                    $margeInferieure = $valeurNominale - 10;
                    $margeSuperieure = $valeurNominale + 10;
            
                    if ($indicationLue >= $margeInferieure && $indicationLue <= $margeSuperieure) {
                            $c[$key] = 'oui';
                    }else{
                        $c[$key] = 'non';
                    }
                    if (in_array('non',$c)){
                        $justessecorrecte = 'non';
                    }else {
                        $justessecorrecte = 'oui';
                    }
                }
        if (!$certification) {
            throw $this->createNotFoundException('La certification n\'existe pas');
        }   
        if ($fidelitecorrecte == 'oui' && $excentrationcorrecte == 'oui' && $justessecorrecte = 'oui'){
            $conforme = 'CONFORME';
        }else{
            $conforme = 'NON CONFORME';
        }
        // Récupérer les informations du client et de l'instrument associés
        $instrument = $certification->getInstrument(); 
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);
        $package = new Package(new EmptyVersionStrategy());
        $logoPath = $package->getUrl('uploads/brochure/' . $entreprise->getLogo());
        $logoData = base64_encode(file_get_contents($this->getParameter('kernel.project_dir') . '/public/' . $logoPath));
        $logoSrc = 'data:image/jpeg;base64,' . $logoData;

        $html = $this->render('certification/print.html.twig', [
            'title' => "Welcome to our PDF Test",
            'certification' => $certification,
            'instrument' => $instrument,
            'ctrlfidelite' => $ctrlfidelite,
            'ctrljustesse' => $ctrljustesse,
            'ctrlexcentration' => $ctrlexcentration,
            'tolerance' => $tolerance,
            'fidelite' => $fidelite,
            'excentration' => $excentration,
            'entreprise' => $entreprise,
            'logoSrc' => $logoSrc,
            'fidelitecorrecte' => $fidelitecorrecte,
            'excentrationcorrecte' => $excentrationcorrecte,
            'justessecorrecte' => $justessecorrecte,
            'conforme' => $conforme,
        ]);
    

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);
        exit;
    }
}


