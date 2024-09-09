<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Entity\Solde;
use App\Entity\User;
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
        return $this->render('registration/index.html.twig', [
                    'users' => $userRepository->findAll(),
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

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $brochureFile */
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
                            $this->getParameter('brochure_directory'),
                            $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $user->setBrochureFilename($newFilename);
            }
            $user->setPassword(
                    $userPasswordHasher->hashPassword(
                            $user,
                            $form->get('plainPassword')->getData()
                    )
            );
            $entityManager->persist($user);
                        $this->addFlash('message', 'Création du compte avec succès.');

            $entityManager->flush();

            return $this->redirectToRoute('registration_success');
        }

        return $this->render('registration/register.html.twig', [
                    'user' => $user,
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

    #[Route('/{id}/deleteuser', name: 'app_registration_delete', methods: ['POST'])]
    public function deleteUser(Request $request, User $user, EntityManagerInterface $entityManager): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès réfusé, vous n\'avez pas les droits d\'accès ici!');
        }
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {


            $user->setEtat(0);

            $user->setDeletedAt(new DateTime("now"));

            $this->addFlash('message', 'Fermeture du compte avec succès.');
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_registration_user');
    }
    
     #[Route('/{id}/modifuser', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function editUser(Request $request, UserPasswordHasherInterface $userPasswordHasher, User $user, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès réfusé, vous n\'avez pas les droits d\'accès ici!');
        }

        $form = $this->createForm(UpdateuserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $brochureFile2 = $form->get('brochure')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile2) {
                $originalFilename2 = pathinfo($brochureFile2->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename2 = $slugger->slug($originalFilename2);
                $newFilename2 = $safeFilename2 . '-' . uniqid() . '.' . $brochureFile2->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile2->move(
                            $this->getParameter('images_directory'),
                            $newFilename2
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $user->setBrochureFilename($newFilename2);
            }
      

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

            return $this->redirectToRoute('app_registration_user', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('registration/update.html.twig', [
                    'user' => $user,
                    'form' => $form,
        ]);
    }

}
