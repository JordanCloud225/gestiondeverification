<?php

namespace App\Controller;

use App\Entity\Typeinstrument;
use App\Form\TypeinstrumentForm;
use App\Repository\TypeinstrumentRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/typeinstrument')]
class TypeinstrumentController extends AbstractController
{
    #[Route('/', name: 'app_typeinstrument', methods: ['GET', 'POST'])]
    public function index(TypeinstrumentRepository $typeinstrumentRepository): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $typeinstrument = $typeinstrumentRepository->findBy(['deletedAt' => NULL]);
        return $this->render('typeinstrument/index.html.twig', [
            'typeinstrument' => $typeinstrument,
        ]);
    }

    #[Route('/new', name: 'app_typeinstrument_new', methods: ['GET', 'POST'])]
    public function newtypeinstrument(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $typeinstrument = new Typeinstrument();
        $form = $this->createForm(TypeinstrumentForm::class,$typeinstrument);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $typeinstrument->setCreatedAt(new DateTime("now"));
            $typeinstrument->setIdentreprise($this->getUser()->getEntreprise()->getId());
            $em->persist($typeinstrument);
            $em->flush();
            return $this->redirectToRoute('app_typeinstrument', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('typeinstrument/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_typeinstrument_edit', methods: ['GET', 'POST'])]
    public function edit (Request $request, $id, TypeinstrumentRepository $typeinstrumentRepository, EntityManagerInterface $em): Response
    {
       $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
       $typeinstrument = $typeinstrumentRepository->find($id);
       if (!$typeinstrument) {
           throw $this->createNotFoundException('Aucun type d\'instrument trouvé '. $id);
       }
       $form = $this->createForm(TypeinstrumentForm::class, $typeinstrument);
   
       $form->handleRequest($request);
   
       if ($form->isSubmitted() && $form->isvalid()){
           $em->flush();
           return $this->redirectToRoute('app_typeinstrument', [], Response::HTTP_SEE_OTHER);
        }
       return $this->render('typeinstrument/edit.html.twig',[
           'form'=> $form->createview(),
       ]);
    }

    #[Route('/delete/{id}', name: 'app_typeinstrument_delete', methods: ['GET','POST'])]
    public function delete(Request $request, $id, Typeinstrument $typeinstrument, TypeinstrumentRepository $typeinstrumentRepository, EntityManagerInterface $em): Response
    {
       $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
       $marque = $typeinstrumentRepository->find($id);
       $typeinstrument->setdeletedAt( new DateTime("now"));
            $em->flush();
            return $this->redirectToRoute('app_typeinstrument', [], Response::HTTP_SEE_OTHER);
        }
}
