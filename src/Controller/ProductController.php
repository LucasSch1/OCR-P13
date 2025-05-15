<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class ProductController extends AbstractController
{
    #[Route('/produit/{id}', name: 'app_view_detail_product', methods: ['GET'])]
    public function getDetailProduct(ProductRepository $produitRepository, int $id, \App\Repository\CartRepository $cartRepository, \App\Repository\CartProductsRepository $cartProductsRepository): Response
    {
        $selectedProduct = $produitRepository->find($id);
        $imagePath = $this->getParameter('image_product_path');

        $user = $this->getUser();
        $quantity = 1;
        $inCart = false;

        if ($user) {
            $cart = $cartRepository->findOneBy(['user' => $user, 'status' => 'en_cours']);
            if ($cart) {
                $cartProducts = $cartProductsRepository->findOneBy(['cart' => $cart, 'product' => $selectedProduct]);
                if ($cartProducts) {
                    $quantity = $cartProducts->getQuantity();
                    $inCart = true;
                }
            }
        }

        return $this->render('product/detail_product.html.twig', [
            'controller_name' => 'ProductController',
            'product' => $selectedProduct,
            'imagePath' => $imagePath,
            'quantity' => $quantity,
            'inCart' => $inCart,
        ]);
    }


    #[Route('/api/products', name: 'app_listes_produits', methods: ['GET'])]
    public function afficherToutLesProduits(ProductRepository $productRepository, SerializerInterface $serializer, AuthorizationCheckerInterface $authChecker): JsonResponse
    {


        $user = $this->getUser();

        if (!$authChecker->isGranted('user.isApiAccess',$user)){
            return new JsonResponse(['message' => 'Accès API non activé'],Response::HTTP_UNAUTHORIZED);
        }

        $allProductsList = $productRepository->findAll();

        if (empty($allProductsList)) {
            return new JsonResponse(['error' => "Aucun produit n'est disponible."],Response::HTTP_NOT_FOUND);
        }

        $jsonProductsList = $serializer->serialize($allProductsList, 'json',['groups' => 'getProducts']);
        return new JsonResponse($jsonProductsList,Response::HTTP_OK);



    }
}
