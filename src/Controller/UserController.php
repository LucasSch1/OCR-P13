<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/utilisateur', name: 'app_user_account')]
    public function showUserAccount(): Response
    {
        $utilisateur = $this->getUser();

        $commandes = $utilisateur->getCommandes();



        return $this->render('user/account.html.twig', [
            'controller_name' => 'UserController',
            'commandes' => $commandes,
        ]);
    }
}
