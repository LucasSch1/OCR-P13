<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\PanierProduits;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
            return $this->render('panier/panier_vide.html.twig');
        }

        $panierProduits = $panier->getPanierProduits();
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

    #[Route('/add-to-cart/{id}', name: 'app_add_to_cart')]
    public function addProductToCart(int $id, ProduitRepository $produitRepository, PanierRepository $panierRepository, EntityManagerInterface $entityManager): RedirectResponse
    {
        // Récupérer l'utilisateur connecté
        $utilisateur = $this->getUser();

        // Vérifier si l'utilisateur a un panier actif
        $panier = $panierRepository->findOneBy(['utilisateur' => $utilisateur, 'statut' => 'en_cours']);

        if (!$panier) {
            // Si aucun panier en cours, créer un nouveau panier
            $panier = new Panier();
            $panier->setUtilisateur($utilisateur);
            $panier->setStatut('en_cours');
            $entityManager->persist($panier);
            $entityManager->flush();
        }

        // Récupérer le produit par son ID
        $produit = $produitRepository->find($id);

        if (!$produit) {
            $this->addFlash('error', 'Le produit n\'existe pas');
            return $this->redirectToRoute('app_home');
        }

        // Vérifier si le produit est déjà dans le panier
        $panierProduit = $entityManager->getRepository(PanierProduits::class)->findOneBy(['panier' => $panier, 'produit' => $produit]);

        if ($panierProduit) {
            // Si le produit existe déjà dans le panier, augmenter la quantité
            $panierProduit->setQuantite($panierProduit->getQuantite() + 1);
        } else {
            // Sinon, ajouter le produit avec une quantité de 1
            $panierProduit = new PanierProduits();
            $panierProduit->setPanier($panier);
            $panierProduit->setProduit($produit);
            $panierProduit->setQuantite(1);  // Par défaut, on ajoute 1 unité du produit
            $panierProduit->setPrixUnitaire($produit->getPrixProduit());
            $entityManager->persist($panierProduit);
        }

        // Sauvegarder les modifications dans la base de données
        $entityManager->flush();

        // Ajouter un message flash pour informer l'utilisateur
        $this->addFlash('success', 'Produit ajouté au panier');
        return $this->redirectToRoute('app_cart');
    }

}
