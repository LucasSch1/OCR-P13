<?php

namespace App\Controller;

use App\Form\CartProductForm;
use App\Repository\CartProductsRepository;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class ProductController extends AbstractController
{
    #[Route('/produit/{id}', name: 'app_view_detail_product', methods: ['GET'])]
    public function getDetailProduct(Request $request, ProductRepository $produitRepository, int $id, CartRepository $cartRepository, CartProductsRepository $cartProductsRepository): Response
    {
        $selectedProduct = $produitRepository->find($id);
        $imagePath = $this->getParameter('image_product_path');

        $user = $this->getUser();
        $quantity = 1;
        // Variable boolean qui indique si le produit est déja dans le panier
        $inCart = false;

        if ($user) {
            $cart = $cartRepository->findOneBy(['user' => $user, 'status' => 'en_cours']);
            if ($cart) {
                // Récupération du produit s'il est dans le panier
                $cartProducts = $cartProductsRepository->findOneBy(['cart' => $cart, 'product' => $selectedProduct]);
                // Si la variable n'est pas null alors récupérer la quantité et mettre la variable dans inCart sur true
                if ($cartProducts) {
                    $quantity = $cartProducts->getQuantity();
                    $inCart = true;
                }
            }
        }

        $form = $this->createForm(CartProductForm::class, [
            'quantity' => $quantity,
        ], [
            'action' => $this->generateUrl('app_add_to_cart', ['id' => $selectedProduct->getId()]),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);


        return $this->render('product/detail_product.html.twig', [
            'controller_name' => 'ProductController',
            'product' => $selectedProduct,
            'imagePath' => $imagePath,
            'quantity' => $quantity,
            'inCart' => $inCart,
            'form' => $form,
        ]);
    }


    #[Route('/api/products', name: 'app_listes_produits', methods: ['GET'])]
    public function showAllProducts(ProductRepository $productRepository, SerializerInterface $serializer, AuthorizationCheckerInterface $authChecker): JsonResponse
    {

        // Récupère l'utilisateur connecté
        $user = $this->getUser();

        // Si le voter qui a comme attribut isApiAcces renvoie false alors on retourne une réponse 403 JSON
        if (!$authChecker->isGranted('user.isApiAccess',$user)){
            return new JsonResponse(['message' => 'Accès API non activé'],Response::HTTP_UNAUTHORIZED);
        }

        // Récupère tout les produits
        $allProductsList = $productRepository->findAll();

        // Si la variable est vide alors on retourne une réponse JSON not found
        if (empty($allProductsList)) {
            return new JsonResponse(['error' => "Aucun produit n'est disponible."],Response::HTTP_NOT_FOUND);
        }

        $jsonProductsList = $serializer->serialize($allProductsList, 'json',['groups' => 'getProducts']);
        return new JsonResponse($jsonProductsList,Response::HTTP_OK,[],true);



    }
}
