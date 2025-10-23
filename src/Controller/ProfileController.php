<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/profil')]
class ProfileController extends AbstractController
{
    #[Route('', name: 'app_profile')]
    public function index(RecipeRepository $recipeRepository): Response
    {
        $user = $this->getUser();

        // On récupère les recettes de l'utilisateur connecté
        $recipes = $recipeRepository->findBy(['author' => $user], ['createdAt' => 'DESC']);

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'recipes' => $recipes,
        ]);
    }
}

