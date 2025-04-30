<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Tests\Models\Enums\Product;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);


        $product1 = new Produit();
        $product1->setNom('Kit d\'hygiène recyclable');
        $product1->setDescription('Pour une salle de bain éco-friendly');
        $product1->setPrixProduit(24.99);
        $product1->setImage('kit_hygiene.jpg');
        $manager->persist($product1);

        $product2 = new Produit();
        $product2->setNom('Shot Tropical');
        $product2->setDescription('Fruits frais, pressés à froid');
        $product2->setPrixProduit(4.50);
        $product2->setImage('shot_tropical.jpg');
        $manager->persist($product2);

        $product3 = new Produit();
        $product3->setNom('Gourde en bois');
        $product3->setDescription('50cl, bois d’olivier');
        $product3->setPrixProduit(16.90);
        $product3->setImage('gourde_en_bois.jpg');
        $manager->persist($product3);

        $product4 = new Produit();
        $product4->setNom('Disques Démaquillants x3');
        $product4->setDescription('Solution efficace pour vous démaquiller en douceur ');
        $product4->setPrixProduit(19.90);
        $product4->setImage('disques_demaquillants.jpg');
        $manager->persist($product4);

        $manager->flush();
    }
}
