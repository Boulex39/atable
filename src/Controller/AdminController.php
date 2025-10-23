<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin_dashboard')]
    public function index(CategoryRepository $categoryRepository, UserRepository $userRepository): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'users' => $userRepository->findAll(),
        ]);
    }
}

