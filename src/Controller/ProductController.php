<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class ProductController extends AbstractController
{
    #[Route('/produit/{id}', name: 'app_view_detail_product', methods: ['GET'])]
    public function getDetailProduct(ProduitRepository $produitRepository, int $id, \App\Repository\PanierRepository $panierRepository, \App\Repository\PanierProduitsRepository $panierProduitsRepository): Response
    {
        $selectedProduct = $produitRepository->find($id);
        $imagePath = $this->getParameter('image_product_path');

        $user = $this->getUser();
        $quantite = 1;
        $inCart = false;

        if ($user) {
            $panier = $panierRepository->findOneBy(['utilisateur' => $user, 'statut' => 'en_cours']);
            if ($panier) {
                $panierProduit = $panierProduitsRepository->findOneBy(['panier' => $panier, 'produit' => $selectedProduct]);
                if ($panierProduit) {
                    $quantite = $panierProduit->getQuantite();
                    $inCart = true;
                }
            }
        }

        return $this->render('product/detail_product.html.twig', [
            'controller_name' => 'ProductController',
            'product' => $selectedProduct,
            'imagePath' => $imagePath,
            'quantite' => $quantite,
            'inCart' => $inCart,
        ]);
    }


    #[Route('/api/products', name: 'app_listes_produits', methods: ['GET'])]
    public function afficherTousLesProduits(ProduitRepository $produitRepository,SerializerInterface $serializer): JsonResponse{

        $allProductsList = $produitRepository->findAll();

        if (empty($allProductsList)) {
            return new JsonResponse(['error' => "Aucun produit n'est disponible."],Response::HTTP_NOT_FOUND);
        }

        $jsonProductsList = $serializer->serialize($allProductsList, 'json',['groups' => 'getProducts']);
        return new JsonResponse($jsonProductsList,Response::HTTP_OK);



    }
}
