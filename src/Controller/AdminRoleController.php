<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin/user')]
class AdminRoleController extends AbstractController
{
    #[Route('/{id}/role', name: 'app_admin_change_role', methods: ['POST'])]
    public function changeRole(
        User $user,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        // Vérifie le token CSRF
        if (!$this->isCsrfTokenValid('change_role' . $user->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Jeton CSRF invalide.');
            return $this->redirectToRoute('app_admin_dashboard');
        }

        // Récupère la valeur du rôle sélectionné
        $newRole = $request->request->get('role');

        // Vérifie si le rôle est valide
        $allowedRoles = ['ROLE_USER', 'ROLE_ADMIN'];
        if (!in_array($newRole, $allowedRoles, true)) {
            $this->addFlash('error', 'Rôle invalide.');
            return $this->redirectToRoute('app_admin_dashboard');
        }

        // Applique le nouveau rôle
        $user->setRoles([$newRole]);
        $em->flush();

        $roleLabel = $newRole === 'ROLE_ADMIN' ? 'Administrateur' : 'Utilisateur';
        $username = $user->getUsername() ?: '(sans pseudo)';
        $this->addFlash('success', sprintf('Le rôle de %s a été mis à jour en %s.', $username, $roleLabel));


        return $this->redirectToRoute('app_admin_dashboard');
    }
}
