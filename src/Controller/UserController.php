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
    public function afficherCompteUtilisateur(): Response
    {
        $utilisateur = $this->getUser();

        $commandes = $utilisateur->getCommandes();



        return $this->render('user/account.html.twig', [
            'controller_name' => 'UserController',
            'commandes' => $commandes,
        ]);
    }

    #[Route('/utilisateur/supprimer', name: 'app_user_delete_account')]
    public function supprimerUtilisateur(Request $request,EntityManagerInterface $entityManager): Response
    {
        $utilisateur = $this->getUser();

        if (!$utilisateur) {
            return $this->redirectToRoute('app_home');
        }

        $entityManager->remove($utilisateur);
        $entityManager->flush();

        $this->container->get('security.token_storage')->setToken(null);
        $request->getSession()->invalidate();

        return $this->redirectToRoute('app_home');


    }
}
