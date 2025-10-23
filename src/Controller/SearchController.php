<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/recherche')]
class SearchController extends AbstractController
{
    #[Route('', name: 'app_search', methods: ['GET'])]
    public function index(Request $request, RecipeRepository $recipeRepository): Response
    {
        $query = trim((string) $request->query->get('query', ''));

        $recipes = [];
        if ($query !== '') {
            $recipes = $recipeRepository->createQueryBuilder('r')
                ->where('r.title LIKE :q OR r.description LIKE :q OR r.ingredients LIKE :q')
                ->setParameter('q', '%' . $query . '%')
                ->orderBy('r.createdAt', 'DESC')
                ->getQuery()
                ->getResult();
        }

        return $this->render('search/index.html.twig', [
            'query' => $query,
            'recipes' => $recipes,
        ]);
    }
}


