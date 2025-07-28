<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Entity\Solde;
use App\Entity\User;
use App\Form\EntrepriseForm;
use App\Form\EntrepriseType;
use App\Form\RegistrationFormType;
use App\Form\UpdateuserType;
use App\Form\UserType;
use App\Repository\EntrepriseRepository;
use App\Repository\UserRepository;
use App\Traits\ClientIp;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class RegistrationController extends AbstractController {

    use ClientIp;

    #[Route('/user', name: 'app_register_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $identreprise = $this->getUser()->getEntreprise()->getId();
        $user = $userRepository->findBy(['entreprise' => $identreprise, 'deletedAt' => NULL]);
        // dd($user);
        return $this->render('registration/index.html.twig', [
                    'users' => $user,
        ]);
    }


    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
     {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $brochureFile2 = $form->get('brochure2')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            $brochureFile = $form->get('brochure')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                            $this->getParameter('images_directory'),
                            $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
 
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $user->setBrochureFilename($newFilename);
            }
// encode the plain password
            $user->setPassword(
                    $userPasswordHasher->hashPassword(
                            $user,
                            $form->get('plainPassword')->getData()
                    )
            );
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('registration_success', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('registration/register.html.twig', [
                    'user' => $user,
                    'form' => $form,
        ]);
    }

    #[Route('/listeuser', name: 'app_registration_user', methods: ['GET'])]
    public function listeUser(User $user): Response {
           $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès réfusé, vous n\'avez pas les droits d\'accès ici!');
        }

        return $this->render('registration/user.html.twig', [
                    'users' => $user,
        ]);
    }

    #[Route('/newuser', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher, SluggerInterface $slugger): Response {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            /** @var UploadedFile $brochureFile */
            // $brochureFile = $form->get('brochure')->getData();

            // // this condition is needed because the 'brochure' field is not required
            // // so the PDF file must be processed only when a file is uploaded
            // if ($brochureFile) {
            //     $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
            //     // this is needed to safely include the file name as part of the URL
            //     $safeFilename = $slugger->slug($originalFilename);
            //     $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

            //     // Move the file to the directory where brochures are stored
            //     try {
            //         $brochureFile->move(
            //                 $this->getParameter('brochure_directory'),
            //                 $newFilename
            //         );
            //     } catch (FileException $e) {
            //         // ... handle exception if something happens during file upload
            //     }

            //     // updates the 'brochureFilename' property to store the PDF file name
            //     // instead of its contents
            //     $user->setBrochureFilename($newFilename);
            // }
            $entreprise = $this->getUser()->getEntreprise();
            $user->setEntreprise($entreprise);
            $user->setPassword(
                    $userPasswordHasher->hashPassword(
                            $user,
                            $form->get('plainPassword')->getData()
                    )
            );
            $entityManager->persist($user);
                        $this->addFlash('message', 'Création du compte avec succès.');

            $entityManager->flush();

        return $this->redirectToRoute('app_register_index');
        }

        return $this->render('registration/new.html.twig', [
                    'form' => $form,
        ]);
    }

    #[Route('/success', name: 'registration_success', methods: ['GET', 'POST'])]
    public function success(): Response {

        return $this->render('registration/success.html.twig', [
        ]);
    }


    #[Route('/{id}/uservalidate/', name: 'app_registration_active', methods: ['POST'])]
    public function validateUser(Request $request, User $user, EntityManagerInterface $entityManager): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès réfusé, vous n\'avez pas les droits d\'accès ici!');
        }
        if ($this->isCsrfTokenValid('active' . $user->getId(), $request->request->get('_token'))) {


            $user->setEtat(1);

            $user->setDeletedAt(NULL);

            $this->addFlash('message', 'Activation du compte avec succès.');
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_registration_user');
    }

    #[Route('/deleteuser/{id}', name: 'app_registration_delete', methods: ['GET', 'POST'])]
    public function deleteUser(Request $request, $id, UserRepository $userRepository, EntityManagerInterface $entityManager): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès réfusé, vous n\'avez pas les droits d\'accès ici!');
        }
            $user = $userRepository->find($id);

            $user->setDeletedAt(new DateTimeImmutable("now"));

            $this->addFlash('message', 'Fermeture du compte avec succès.');
            $entityManager->flush();
        

        return $this->redirectToRoute('app_registration_index');
    }
    
     #[Route('/modifuser/{id}', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function editUser(Request $request,$id, UserPasswordHasherInterface $userPasswordHasher, UserRepository $userRepository, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès réfusé, vous n\'avez pas les droits d\'accès ici!');
        }
        $user = $userRepository->find($id);

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
      
            // encode the plain password
            $user->setPassword(
                    $userPasswordHasher->hashPassword(
                            $user,
                            $form->get('plainPassword')->getData()
                    )
            );
            $entityManager->persist($user);
            $this->addFlash('message', 'Modification avec succès');
            $entityManager->flush();

            return $this->redirectToRoute('app_register_index', [], Response::HTTP_SEE_OTHER);        }

        return $this->render('registration/edituser.html.twig', [
                    'users' => $user,
                    'form' => $form,
        ]);
    }



        #[Route('/entreprise', name: 'app_entreprise_index', methods: ['GET', 'POST'])]
    public function indexentreprise(EntrepriseRepository $entrepriseRepository): Response {
           $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès réfusé, vous n\'avez pas les droits d\'accès ici!');
        }
        $entreprise = $entrepriseRepository->findBy(['deletedAt' => NULL]);
        return $this->render('registration/entreprise.html.twig', [
                    'entreprise' => $entreprise,
        ]);
    }

        #[Route('/newentreprise', name: 'app_entreprise_new', methods: ['GET', 'POST'])]
    public function newentreprise(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response {
        $entreprise = new Entreprise();
        $form = $this->createForm(EntrepriseForm::class, $entreprise);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('logo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                            $this->getParameter('brochure_directory'),
                            $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $entreprise->setLogo($newFilename);
            }
            $entityManager->persist($entreprise);
             $this->addFlash('message', 'Création du compte avec succès.');

            $entityManager->flush();

            return $this->redirectToRoute('app_entreprise_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('registration/newentreprise.html.twig', [
                    'form' => $form,
        ]);
    }

         #[Route('/editentreprise/{id}', name: 'app_entreprise_edit', methods: ['GET', 'POST'])]
    public function editentreprise(Request $request, $id, EntrepriseRepository $entrepriseRepository, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès réfusé, vous n\'avez pas les droits d\'accès ici!');
        }
        $entreprise = $entrepriseRepository->find($id);

        $form = $this->createForm(EntrepriseForm::class, $entreprise);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $brochureFile = $form->get('logo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                            $this->getParameter('brochure_directory'),
                            $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $entreprise->setLogo($newFilename);
            }
      

            $entityManager->persist($entreprise);
            $this->addFlash('message', 'Modification avec succès');
            $entityManager->flush();

            return $this->redirectToRoute('app_entreprise_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('registration/editentreprise.html.twig', [
                    'form' => $form,
        ]);
    }

    #[Route('/deleteentreprise/{id}', name: 'app_entreprise_delete', methods: ['GET','POST'])]
     public function deleteentreprise($id, EntrepriseRepository $entrepriserepository, EntityManagerInterface $em): Response
     {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $entreorise = $entrepriserepository->find($id);
        $entreorise->setdeletedAt( new DateTime ("now"));
        $em->flush();
     
         return $this->redirectToRoute('app_entreprise_index', [], Response::HTTP_SEE_OTHER);
     }
}
