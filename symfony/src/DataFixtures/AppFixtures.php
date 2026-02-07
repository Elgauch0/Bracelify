<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductImage;
use Symfony\Component\HttpFoundation\File\UploadedFile;
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {   
        $bagueCategorie =new Category();
        $bagueCategorie->setLabel('Bague');
        $manager->persist($bagueCategorie);

        $boucleCategorie =new Category();
        $boucleCategorie->setLabel('Boucle d\'oreille');
        $manager->persist($boucleCategorie);






        $bague = new Product();
        $bague->setName('Bague en or');
        $bague->setPrice(10000); // 100.00 € en centimes
        $bague->setCategory($bagueCategorie);
        $bague->setDescription('Bague en or 18 carats avec un diamant de 1 carat.');
        $this->createProductImage($bague);
        $manager->persist($bague);




        $boucle = new Product();
        $boucle->setName('Boucle d\'oreille en argent');
        $boucle->setPrice(5000); // 50.00 € en centimes
        $boucle->setCategory($boucleCategorie);
        $boucle->setDescription('Boucle d\'oreille en argent avec une perle naturelle.');
        $this->createProductImage($boucle);
        $manager->persist($boucle); 






        $manager->flush();
    }


      
private function createProductImage(Product $product): ProductImage
{
    $image = new ProductImage();
    $url = "https://picsum.photos/seed/bijou" . rand(1, 1000) . "/800/600";
    
    $tempPath = sys_get_temp_dir() . '/' . uniqid() . '.jpg';
    file_put_contents($tempPath, file_get_contents($url));

    // On crée un UploadedFile pour simuler un vrai upload de formulaire
    $file = new UploadedFile(
        $tempPath,
        'image.jpg',
        'image/jpeg',
        null,
        true // Important : mode test à true
    );

    $image->setImageFile($file);
    $product->addProductImage($image);

    return $image;
}
}
