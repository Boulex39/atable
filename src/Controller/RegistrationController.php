<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 🔒 Récupère le mot de passe "en clair"
            $plainPassword = $form->get('plainPassword')->getData();

            // ✅ Hashage du mot de passe
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            // ✅ Ajout automatique de la date de création
            if (method_exists($user, 'setCreatedAt') && $user->getCreatedAt() === null) {
                $user->setCreatedAt(new \DateTimeImmutable());
            }

            $entityManager->persist($user);
            $entityManager->flush();

            // ✅ Redirection après inscription
            $this->addFlash('success', 'Votre compte a bien été créé. Vous pouvez maintenant vous connecter.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}

