<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/client')]
class ClientController extends AbstractController
{
    #[Route('/', name: 'app_client')]
    public function index(ClientRepository $clientRepository): Response
    {
        $user = $this->getUser();
        $client = $clientRepository->findBy(['deletedAt' => NULL]);
        return $this->render('client/index.html.twig', [
            'client' => $client,
        ]);
    }
    #[Route('/create', name: 'app_client_create', methods: ['GET', 'POST'])]
    public function create (Request $request, EntityManagerInterface $em): Response
     {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $client->setCreatedAt(new DateTime("now"));
            $em->persist($client);
            $em->flush();
            $this->addFlash('message', 'Enregistrement avec succès');
            return $this->redirectToRoute('app_client');
        }
         return $this->render('client/create.html.twig', [
            'form' => $form->createview(),
         ]);
         
     }

     #[Route('/edit/{id}', name: 'app_client_edit', methods: ['GET', 'POST'])]
     public function edit (Request $request, $id, ClientRepository $clientrepository, EntityManagerInterface $em): Response
     {
        $client = $clientrepository->find($id);
        if (!$client) {
            throw $this->createNotFoundException('Aucun client trouvé '. $id);
        }
        $form = $this->createForm(ClientType::class, $client);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isvalid()){
            $em->flush();
            return$this->redirectToRoute('app_client');
        }
        return $this->render('client/edit.html.twig',[
            'form'=> $form->createview(),
        ]);
     }
    
     #[Route('/delete/{id}', name: 'app_client_delete', methods: ['GET','POST'])]
     public function delete(Request $request, $id, Client $client, ClientRepository $clientRepository, EntityManagerInterface $em): Response
     {
        $client = $clientRepository->find($id);
        $client->setdeletedAt( new DateTime ("now"));
             $em->flush();
     
         return $this->redirectToRoute('app_client', [], Response::HTTP_SEE_OTHER);
     }
}
