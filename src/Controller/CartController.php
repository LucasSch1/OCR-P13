<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\Cart;
use App\Entity\CartProducts;
use App\Form\CartProductForm;
use App\Repository\CartProductsRepository;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Isgranted('IS_AUTHENTICATED')]
final class CartController extends AbstractController
{

    #[Route('/panier', name: 'app_cart')]
    public function showCart(CartRepository $cartRepository): Response
    {
        $message = "";
        $user = $this->getUser();
        // Récupère le panier associé à l'utilisateur avec le status 'current'
        $cart = $cartRepository->findOneBy(['user' => $user, 'status' => 'current']);

        if (!$cart) {
            $message = "Vous n'avez pas de panier en cours.";
            return $this->redirectToRoute('app_show_empty_cart');
        }

        // Récupère les produits dans le panier
        $cartProducts = $cart->getCartProducts();

        if (!$cartProducts || count($cartProducts) === 0) {
            $message = "Votre panier est vide.";
        }

        // Récupère le chemin stocké dans services.yaml pour afficher les images des produits
        $imagePath = $this->getParameter('image_product_path');

        $total = 0;
        // Récupère le prix et la quantité de chaque produit pour les multiplier pour faire un total
        foreach ($cartProducts as $cartProduct) {
            $total += $cartProduct->getUnitPrice() * $cartProduct->getQuantity();
        }

        return $this->render('cart/cart.html.twig', [
            'controller_name' => 'CartController',
            'cart' => $cart,
            'cartProducts' => $cartProducts,
            'total' => $total,
            'imagePath' => $imagePath,
            'message' => $message,
        ]);
    }

    #[Route('/panier/ajouter/{id}', name: 'app_add_to_cart')]
    public function addProductToCart(int $id, ProductRepository $productRepository, CartRepository $cartRepository, CartProductsRepository $cartProductsRepository, EntityManagerInterface $entityManager, Request $request): RedirectResponse
    {
        $user = $this->getUser();

        $form = $this->createForm(CartProductForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quantity = $form->get('quantity')->getData();
        }

        $cart = $cartRepository->findOneBy(['user' => $user, 'status' => 'current']);
        // Si aucun panier n'est en cours alors on en crée un nouveau
        if (!$cart) {
            $cart = new Cart();
            $cart->setUser($user);
            $cart->setStatus('current');
            $entityManager->persist($cart);
            $entityManager->flush();
        }

        $product = $productRepository->find($id);
        // Si le produit est introuvable alors on affiche une erreur
        if (!$product) {
            $this->addFlash('error', 'Produit introuvable');
            return $this->redirectToRoute('app_home');
        }

        $cartProduct = $cartProductsRepository->findOneBy(['cart' => $cart, 'product' => $product]);

        if ($cartProduct) {
            if ($quantity > 0) {
                $cartProduct->setQuantity($quantity);
                $this->addFlash('success', 'Quantité mise à jour');
            } else {
                $entityManager->remove($cartProduct);
                $this->addFlash('success', 'Produit retiré du cart');
            }
        } else {
            if ($quantity > 0) {
                $cartProduct = new CartProducts();
                $cartProduct->setCart($cart);
                $cartProduct->setProduct($product);
                $cartProduct->setQuantity($quantity);
                $cartProduct->setUnitPrice($product->getProductPrice());
                $entityManager->persist($cartProduct);
                $this->addFlash('success', 'Produit ajouté au cart');
            } else {
                $this->addFlash('info', 'Quantité invalide');
            }
        }

        $entityManager->flush();
        return $this->redirectToRoute('app_view_detail_product', [
            'id' => $id,
        ]);
    }


    #[Route('/panier/supprimer', name: 'app_empty_cart')]
    public function emptyCart(CartRepository $cartRepository, CartProductsRepository $cartProductsRepository, EntityManagerInterface $entityManager): RedirectResponse
    {
        $message = '';
        $user = $this->getUser();
        $cart = $cartRepository->findOneBy(['user' => $user, 'status' => 'current']);

        if (!$cart) {
            $message = 'Aucun panier en cours';
        }

        $cartProducts = $cartProductsRepository->findBy(['cart' => $cart]);

        // Marque chaque produit du panier pour la suppression
        foreach ($cartProducts as $cartProduct) {
            $entityManager->remove($cartProduct);
        }

        $entityManager->flush();

        $this->addFlash('success','Le panier à été vidé avec succès.');

        return $this->redirectToRoute('app_cart',[
            'message' => $message
        ]);
    }

    #[Route('/panier/valider', name: 'app_validate_cart')]
    public function validateCommande(CartRepository $cartRepository, CartProductsRepository $cartProductsRepository, EntityManagerInterface $entityManager): RedirectResponse
    {
        $user = $this->getUser();
        $cart = $cartRepository->findOneBy(['user' => $user, 'status' => 'current']);

        if (!$cart) {
            $this->addFlash('error', 'Aucun panier en cours');
            return $this->redirectToRoute('app_cart');
        }

        $order = new Order();
        $order->setUser($user);
        $order->setOrderDate(new \DateTime());
        $order->setIsValidated(false);
        $order->setOrderPrice($cart->getTotalPrice());

        foreach ($cart->getCartProducts() as $cartProduct) {
            $product = $cartProduct->getProduct();
            $quantity = $cartProduct->getQuantity();
            $unitPrice = $cartProduct->getUnitPrice();

            $orderProduct = new OrderProduct();
            $orderProduct->setOrder($order);
            $orderProduct->setProduct($product);
            $orderProduct->setQuantity($quantity);
            $orderProduct->setUnitPrice($unitPrice);
            $entityManager->persist($orderProduct);
        }
        $entityManager->persist($order);
        $entityManager->flush();

        $cart->setStatus('finalized');
        $entityManager->flush();

        $order->setIsValidated(true);
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
