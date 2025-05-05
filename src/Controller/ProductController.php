<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    #[Route('/produit/{id}', name: 'app_view_detail_product', methods: ['GET'])]
    public function getDetailProduct(ProduitRepository $produitRepository,int $id): Response
    {
        $selectedProduct = $produitRepository->find($id);
        $imagePath = $this->getParameter('image_product_path');

        return $this->render('product/detail_product.html.twig', [
            'controller_name' => 'ProductController',
            'product' => $selectedProduct,
            'imagePath' => $imagePath,
        ]);
    }
}
