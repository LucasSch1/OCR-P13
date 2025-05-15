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
        $user = $this->getUser();

        $orders = $user->getOrders();

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

        $entityManager->remove($user);
        $entityManager->flush();

        $this->container->get('security.token_storage')->setToken(null);
        $request->getSession()->invalidate();

        return $this->redirectToRoute('app_home');


    }
    #[Route('/utilisateur/api/activer', name: 'app_user_activate_api')]
    public function activateApiAccess(Request $request,EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $user->setApiAccess(true);
        $entityManager->flush();

        return $this->redirectToRoute('app_user_account');
    }

    #[Route('/utilisateur/api/desactiver', name: 'app_user_deactivate_api')]
    public function deactivateApiAccess(Request $request,EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $user->setApiAccess(false);
        $entityManager->flush();

        return $this->redirectToRoute('app_user_account');
    }
}
