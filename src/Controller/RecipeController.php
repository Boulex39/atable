<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/recipe')]
final class RecipeController extends AbstractController
{
    #[Route(name: 'app_recipe_index', methods: ['GET'])]
    public function index(RecipeRepository $recipeRepository): Response
    {
        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipeRepository->findAll(),
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/new', name: 'app_recipe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $recipe = new Recipe();

        // Si ton entité possède ces setters, on initialise proprement
        if (method_exists($recipe, 'setCreatedAt') && null === $recipe->getCreatedAt()) {
            $recipe->setCreatedAt(new \DateTimeImmutable());
        }
        if (method_exists($recipe, 'setAuthor') && $this->getUser()) {
            $recipe->setAuthor($this->getUser());
        }

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($recipe);
            $entityManager->flush();

            $this->addFlash('success', 'Recette créée avec succès.');
            // UX: redirige vers la fiche de la recette
            return $this->redirectToRoute('app_recipe_show', ['id' => $recipe->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recipe/new.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recipe_show', methods: ['GET'])]
    public function show(Recipe $recipe): Response
    {
        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recipe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recipe $recipe, EntityManagerInterface $entityManager): Response
    {
        // Propriétaire ou admin uniquement
        $user = $this->getUser();
        $isAdmin = $this->isGranted('ROLE_ADMIN');
        $isOwner = method_exists($recipe, 'getAuthor')
            && $recipe->getAuthor()
            && $user
            && $recipe->getAuthor()->getId() === $user->getId();

        if (!$isAdmin && !$isOwner) {
            throw new AccessDeniedException('Vous ne pouvez pas modifier cette recette.');
        }

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Recette mise à jour.');
            return $this->redirectToRoute('app_recipe_show', ['id' => $recipe->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recipe_delete', methods: ['POST'])]
    public function delete(Request $request, Recipe $recipe, EntityManagerInterface $entityManager): Response
    {
        // Propriétaire ou admin uniquement
        $user = $this->getUser();
        $isAdmin = $this->isGranted('ROLE_ADMIN');
        $isOwner = method_exists($recipe, 'getAuthor')
            && $recipe->getAuthor()
            && $user
            && $recipe->getAuthor()->getId() === $user->getId();

        if (!$isAdmin && !$isOwner) {
            throw new AccessDeniedException('Vous ne pouvez pas supprimer cette recette.');
        }

        if ($this->isCsrfTokenValid('delete' . $recipe->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($recipe);
            $entityManager->flush();
            $this->addFlash('success', 'Recette supprimée.');
        }

        return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
    }
}
