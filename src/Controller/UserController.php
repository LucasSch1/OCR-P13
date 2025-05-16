<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/utilisateur', name: 'app_user_account')]
    public function showUserAccount(): Response
    {
        // Récupère l'utilisateur connecté
        $user = $this->getUser();

        // Récupère les commandes de l'utilisateur
        $orders = $user->getOrders();

        // Récupère le statut de l'accès API de l'utilisateur
        $apiAccess = $user->isApiAccess();



        return $this->render('user/account.html.twig', [
            'controller_name' => 'UserController',
            'orders' => $orders,
            'apiAccess' => $apiAccess,
        ]);
    }

    #[Route('/utilisateur/supprimer', name: 'app_user_delete_account')]
    public function deleteUser(Request $request,EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_home');
        }
        // Prépare la suppression de l'utilisateur
        $entityManager->remove($user);
        // Applique le changement dans la base de données
        $entityManager->flush();

        // Vide le token storage pour déconnecter immédiatement l'utilisateur
        $this->container->get('security.token_storage')->setToken(null);
        // Détruit la session et supprime le cookie côté client
        $request->getSession()->invalidate();

        return $this->redirectToRoute('app_home');


    }
    #[Route('/utilisateur/api/activer', name: 'app_user_activate_api')]
    public function activateApiAccess(Request $request,EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        // Change la valeur de la propriété ApiAccess grâce au setter sur true
        $user->setApiAccess(true);
        // Applique le changement dans la base de données
        $entityManager->flush();

        return $this->redirectToRoute('app_user_account');
    }

    #[Route('/utilisateur/api/desactiver', name: 'app_user_deactivate_api')]
    public function deactivateApiAccess(Request $request,EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        // Change la valeur de la propriété ApiAccess grâce au setter sur false
        $user->setApiAccess(false);
        // Applique le changement dans la base de données
        $entityManager->flush();

        return $this->redirectToRoute('app_user_account');
    }
}
