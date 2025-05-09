<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\CommandeProduit;
use App\Entity\Panier;
use App\Entity\PanierProduits;
use App\Repository\PanierProduitsRepository;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CartController extends AbstractController
{

    #[Route('/panier', name: 'app_cart')]
    public function showCart(PanierRepository $panierRepository): Response
    {
//        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $utilisateur = $this->getUser();
        $panier = $panierRepository->findOneBy(['utilisateur' => $utilisateur, 'statut' => 'en_cours']);

        if (!$panier) {
            return $this->render('cart/empty.html.twig');
        }

        $panierProduits = $panier->getPanierProduits();

        if (!$panierProduits || count($panierProduits) === 0) {
            return $this->render('cart/empty.html.twig');
        }

        $imagePath = $this->getParameter('image_product_path');

        $total = 0;
        foreach ($panierProduits as $panierProduit) {
            $total += $panierProduit->getPrixUnitaire() * $panierProduit->getQuantite();
        }

        return $this->render('cart/cart.html.twig', [
            'controller_name' => 'CartController',
            'panier' => $panier,
            'panierProduits' => $panierProduits,
            'total' => $total,
            'imagePath' => $imagePath,
        ]);
    }

    #[Route('/panier/ajouter/{id}', name: 'app_add_to_cart')]
    public function addProductToCart(int $id, ProduitRepository $produitRepository, PanierRepository $panierRepository, PanierProduitsRepository $panierProduitsRepository, EntityManagerInterface $entityManager,Request $request): RedirectResponse
    {
        $utilisateur = $this->getUser();
        $quantite = (int) $request->request->get('quantite', 1);

        $panier = $panierRepository->findOneBy(['utilisateur' => $utilisateur, 'statut' => 'en_cours']);
        if (!$panier) {
            $panier = new Panier();
            $panier->setUtilisateur($utilisateur);
            $panier->setStatut('en_cours');
            $entityManager->persist($panier);
            $entityManager->flush();
        }

        $produit = $produitRepository->find($id);
        if (!$produit) {
            $this->addFlash('error', 'Produit introuvable');
            return $this->redirectToRoute('app_home');
        }

        $panierProduit = $panierProduitsRepository->findOneBy(['panier' => $panier, 'produit' => $produit]);

        if ($panierProduit) {
            if ($quantite > 0) {
                $panierProduit->setQuantite($quantite);
                $this->addFlash('success', 'Quantité mise à jour');
            } else {
                $entityManager->remove($panierProduit);
                $this->addFlash('success', 'Produit retiré du panier');
            }
        } else {
            if ($quantite > 0) {
                $panierProduit = new PanierProduits();
                $panierProduit->setPanier($panier);
                $panierProduit->setProduit($produit);
                $panierProduit->setQuantite($quantite);
                $panierProduit->setPrixUnitaire($produit->getPrixProduit());
                $entityManager->persist($panierProduit);
                $this->addFlash('success', 'Produit ajouté au panier');
            } else {
                $this->addFlash('info', 'Quantité invalide');
            }
        }

        $entityManager->flush();
        return $this->redirectToRoute('app_cart');
    }


    #[Route('/panier/supprimer', name: 'app_empty_cart')]
    public function emptyCart(PanierRepository $panierRepository,PanierProduitsRepository $panierProduitsRepository, EntityManagerInterface $entityManager): RedirectResponse
    {
        $utilisateur = $this->getUser();
        $panier = $panierRepository->findOneBy(['utilisateur' => $utilisateur, 'statut' => 'en_cours']);

        if (!$panier) {
            return $this->redirectToRoute('app_show_empty_cart');
        }

        $panierProduits = $panierProduitsRepository->findBy(['panier' => $panier]);

        foreach ($panierProduits as $panierProduit) {
            $entityManager->remove($panierProduit);
        }

        $entityManager->flush();

        $this->addFlash('success','Le panier à été vidé avec succès.');

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/panier/valider', name: 'app_validate_cart')]
    public function validateCommande(PanierRepository $panierRepository,PanierProduitsRepository $panierProduitsRepository, EntityManagerInterface $entityManager): RedirectResponse
    {
        $utilisateur = $this->getUser();
        $panier = $panierRepository->findOneBy(['utilisateur' => $utilisateur, 'statut' => 'en_cours']);

        if (!$panier) {
            $this->addFlash('error', 'Aucun panier en cours');
            return $this->redirectToRoute('app_cart');
        }

        $commande = new Commande();
        $commande->setIdUtilisateur($utilisateur);
        $commande->setDateCommande(new \DateTime());
        $commande->setEstValidee(false);
        $commande->setPrixCommande($panier->getPrixTotal());

        foreach ($panier->getPanierProduits() as $panierProduit) {
            $produit = $panierProduit->getProduit();
            $quantite = $panierProduit->getQuantite();
            $prixUnitaire = $panierProduit->getPrixUnitaire();

            $commandeProduit = new CommandeProduit();
            $commandeProduit->setCommande($commande);
            $commandeProduit->setProduit($produit);
            $commandeProduit->setQuantite($quantite);
            $commandeProduit->setPrixUnitaire($prixUnitaire);
            $entityManager->persist($commandeProduit);
        }
        $entityManager->persist($commande);
        $entityManager->flush();

        $panier->setStatut('finalisé');
        $entityManager->flush();

        $commande->setEstValidee(true);
        $entityManager->flush();

        $this->addFlash('success', 'Votre commande a été validée avec succès.');

        return $this->redirectToRoute('app_cart');
    }


    #[Route('/panier/vide', name: 'app_show_empty_cart')]
    public function showEmptyCart(): Response
    {
        return $this->render('cart/empty.html.twig');
    }

}
