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
use App\Entity\Comment;
use App\Form\CommentType;

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

        // Initialisation
        if (method_exists($recipe, 'setCreatedAt') && null === $recipe->getCreatedAt()) {
            $recipe->setCreatedAt(new \DateTimeImmutable());
        }
        if (method_exists($recipe, 'setAuthor') && $this->getUser()) {
            $recipe->setAuthor($this->getUser());
        }

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ✅ Gestion de l’upload
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                // nom de fichier unique
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();

                // déplace le fichier dans /public/uploads/recipes
                $imageFile->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads/recipes',
                    $newFilename
                );

                // enregistre le chemin relatif
                $recipe->setImageUrl('uploads/recipes/' . $newFilename);
            }

            $entityManager->persist($recipe);
            $entityManager->flush();

            $this->addFlash('success', 'Recette créée avec succès.');
            return $this->redirectToRoute('app_recipe_show', ['id' => $recipe->getId()]);
        }

        return $this->render('recipe/new.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_recipe_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Recipe $recipe, EntityManagerInterface $em): Response
    {
        $comments = $em->getRepository(Comment::class)
            ->findBy(['recipe' => $recipe], ['createdAt' => 'DESC']);

        $formView = null;
        if ($this->getUser()) {
            $comment = new Comment();
            $form = $this->createForm(CommentType::class, $comment);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $comment->setRecipe($recipe);
                $comment->setUser($this->getUser());

                $em->persist($comment);
                $em->flush();

                $this->addFlash('success', 'Commentaire publié.');
                return $this->redirectToRoute('app_recipe_show', ['id' => $recipe->getId()]);
            }

            $formView = $form->createView();
        }

        return $this->render('recipe/show.html.twig', [
            'recipe'   => $recipe,
            'comments' => $comments,
            'form'     => $formView,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recipe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recipe $recipe, EntityManagerInterface $entityManager): Response
    {
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

            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads/recipes',
                    $newFilename
                );
                $recipe->setImageUrl('/uploads/recipes/' . $newFilename);
            }

            $entityManager->flush();
            $this->addFlash('success', 'Recette mise à jour.');
            return $this->redirectToRoute('app_recipe_show', ['id' => $recipe->getId()]);
        }

        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recipe_delete', methods: ['POST'])]
    public function delete(Request $request, Recipe $recipe, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        // Vérifie si l’utilisateur est admin ou auteur
        $isAdmin = $this->isGranted('ROLE_ADMIN');
        $isOwner = $recipe->getAuthor() && $user && $recipe->getAuthor()->getId() === $user->getId();

        if (!$isAdmin && !$isOwner) {
            throw new AccessDeniedException('Vous ne pouvez pas supprimer cette recette.');
        }
        dump($request->getPayload()->all());
        dump($request->getPayload()->getString('_token'));
        dd($this->isCsrfTokenValid('delete' . $recipe->getId(), $request->getPayload()->getString('_token')));

        // Validation CSRF
        $token = $request->getPayload()->getString('_token');
        if ($this->isCsrfTokenValid('delete' . $recipe->getId(), $token)) {
            $em->remove($recipe);
            $em->flush();
            $this->addFlash('success', 'Recette supprimée avec succès.');
        } else {
            $this->addFlash('error', 'Échec de la suppression : jeton CSRF invalide.');
        }

        return $this->redirectToRoute('app_recipe_index');
    }
}
