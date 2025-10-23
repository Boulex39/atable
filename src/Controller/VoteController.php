<?php

namespace App\Controller;

use App\Entity\Vote;
use App\Entity\Recipe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/vote')]
class VoteController extends AbstractController
{
    #[Route('/{id}/{value}', name: 'app_vote_add', methods: ['POST'])]
    public function add(Recipe $recipe, int $value, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        // 🔒 Sécurité sur la valeur
        if ($value < 1 || $value > 5) {
            $this->addFlash('error', 'Note invalide.');
            return $this->redirectToRoute('app_recipe_show', ['id' => $recipe->getId()]);
        }

        // 🔁 Vérifie si l'utilisateur a déjà voté cette recette
        $existingVote = $em->getRepository(Vote::class)->findOneBy([
            'user' => $user,
            'recipe' => $recipe,
        ]);

        if ($existingVote) {
            $existingVote->setValue($value);
            $this->addFlash('info', 'Votre note a été mise à jour.');
        } else {
            $vote = new Vote();
            $vote->setUser($user);
            $vote->setRecipe($recipe);
            $vote->setValue($value);
            $em->persist($vote);
            $this->addFlash('success', 'Merci pour votre note !');
        }

        $em->flush();

        return $this->redirectToRoute('app_recipe_show', ['id' => $recipe->getId()]);
    }
}

