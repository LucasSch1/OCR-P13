<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);


        $product1 = new Product();
        $product1->setName('Kit d\'hygiène recyclable');
        $product1->setLongDescription("Offrez-vous un rituel de soin respectueux de l’environnement avec notre Kit d’hygiène recyclable. Composé d'articles essentiels pour une routine zéro déchet, ce kit inclut des produits de qualité qui vous accompagnent dans votre démarche éco-responsable au quotidien. Chaque produit est conçu pour être réutilisé, réduisant ainsi la consommation de plastiques jetables. Un choix parfait pour prendre soin de vous tout en respectant la planète.");
        $product1->setShortDescription('Pour une salle de bain éco-friendly');
        $product1->setProductPrice(24.99);
        $product1->setImage('kit_hygiene.jpg');
        $manager->persist($product1);

        $product2 = new Product();
        $product2->setName('Shot Tropical');
        $product2->setLongDescription("Un véritable concentré de vitamines et d'énergie dans chaque gorgée ! Notre Shot Tropical allie des fruits frais, pressés à froid, pour vous offrir un moment de bien-être instantané. Préparé avec soin, ce mélange de saveurs exotiques est parfait pour un coup de boost dès le matin ou une pause revitalisante en journée. Un allié naturel pour une santé éclatante.");
        $product2->setShortDescription('Fruits frais, pressés à froid');
        $product2->setProductPrice(4.50);
        $product2->setImage('shot_tropical.jpg');
        $manager->persist($product2);

        $product3 = new Product();
        $product3->setName('Gourde en bois');
        $product3->setLongDescription("Dites adieu aux bouteilles en plastique et optez pour notre gourde en bois, l'accessoire écologique qui allie style et utilité. Fabriquée à partir de matériaux naturels, elle garde vos boissons fraîches ou chaudes pendant des heures. Avec son design élégant et sa capacité optimale, elle devient votre compagnon idéal, que ce soit pour le sport, le travail ou vos moments de détente. Un produit durable et respectueux de l’environnement.");
        $product3->setShortDescription('50cl, bois d’olivier');
        $product3->setProductPrice(16.90);
        $product3->setImage('gourde_en_bois.jpg');
        $manager->persist($product3);

        $product4 = new Product();
        $product4->setName('Disques Démaquillants x3');
        $product4->setLongDescription("Réduisez votre empreinte écologique tout en prenant soin de votre peau avec nos Disques Démaquillants réutilisables. Confectionnés en coton bio, ils sont doux pour la peau et extrêmement efficaces pour éliminer maquillage et impuretés. Faciles à nettoyer, ils constituent une alternative durable aux disques jetables. Un geste simple mais essentiel pour préserver la planète.");
        $product4->setShortDescription('Solution efficace pour vous démaquiller en douceur ');
        $product4->setProductPrice(19.90);
        $product4->setImage('disques_demaquillants.jpg');
        $manager->persist($product4);

        $product5 = new Product();
        $product5->setName('Bougie Lavande & Patchouli');
        $product5->setLongDescription("Laissez-vous envelopper par la douceur de notre bougie parfumée Lavande & Patchouli. Fabriquée avec des cires végétales et des huiles essentielles naturelles, cette bougie crée une ambiance apaisante et relaxante. Parfaite pour détendre l’atmosphère après une longue journée, elle diffusera une fragrance subtile qui apaisera vos sens et vous transportera dans un havre de paix. Idéale pour vos moments de méditation ou de relaxation.");
        $product5->setShortDescription("Cire naturelle");
        $product5->setProductPrice(32);
        $product5->setImage('bougie_lavande_patchouli.jpg');
        $manager->persist($product5);

        $product6 = new Product();
        $product6->setName('Brosse à dent');
        $product6->setLongDescription("Une brosse à dents en bois, au design simple et naturel, pour remplacer les brosses en plastique et préserver l’environnement. Fabriquée à partir de matériaux durables, elle offre une prise en main confortable et un nettoyage optimal. Ses poils sont doux et efficaces, garantissant un soin tout en douceur pour vos dents et vos gencives. Un choix responsable pour un sourire éclatant.");
        $product6->setShortDescription("Bois de hêtre rouge issu de forêts gérées durablement");
        $product6->setProductPrice(5.40);
        $product6->setImage('brosse_a_dent.jpg');
        $manager->persist($product6);

        $product7 = new Product();
        $product7->setName('Kit couvert en bois');
        $product7->setLongDescription("Le Kit couvert en bois est l’accessoire idéal pour vos repas en extérieur ou pour remplacer vos couverts jetables. Pratique et élégant, il se compose de couverts robustes en bois, faciles à transporter et à utiliser. Conçu pour être réutilisé, il est parfait pour ceux qui cherchent à minimiser leur consommation de plastiques tout en profitant d’un repas avec style.");
        $product7->setShortDescription("Revêtement Bio en olivier & sac de transport");
        $product7->setProductPrice(12.94);
        $product7->setImage('kit_couvert_en_bois.jpg');
        $manager->persist($product7);

        $product8 = new Product();
        $product8->setName('Nécessaire, déodorant Bio');
        $product8->setLongDescription("Dites adieu aux déodorants chimiques avec le déodorant Bio Nécessaire. Composé d’ingrédients naturels, ce déodorant offre une protection longue durée sans agresser votre peau ni l’environnement. Grâce à sa formule douce, il allie efficacité et respect de la peau, tout en éliminant les mauvaises odeurs naturellement. Une alternative parfaite aux déodorants conventionnels.");
        $product8->setShortDescription("50ml déodorant à l’eucalyptus");
        $product8->setProductPrice(8.50);
        $product8->setImage('deodorant_bio.jpg');
        $manager->persist($product8);

        $product9 = new Product();
        $product9->setName("Savon Bio");
        $product9->setLongDescription("Le savon Bio The Orange & Grittle est un véritable soin pour votre peau. Fabriqué à partir d’huiles essentielles et d’extraits de plantes, il nettoie en douceur tout en respectant l’équilibre naturel de la peau. Son parfum frais et fruité vous apportera une sensation de bien-être instantanée. Idéal pour les peaux sensibles, ce savon est un geste naturel et responsable pour une routine de soin quotidienne.");
        $product9->setShortDescription("Thé, Orange & Girofle");
        $product9->setProductPrice(18.90);
        $product9->setImage('savon_bio.jpg');
        $manager->persist($product9);



        $manager->flush();
    }
}
