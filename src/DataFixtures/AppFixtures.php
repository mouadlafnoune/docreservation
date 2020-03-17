<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Ville;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');


             // Gérer les utilisateurs
            $users = [];
            $genres = ['male' , 'female'];

            for($i=1; $i<10; $i++){
                $user = new User();
                
                //faker picture : https://randomuser.me/api/portraits/women/75.jpg
                $genre =$faker->randomElement($genres);
                
                $picture = 'https://randomuser.me/api/portraits/';
                $pictureId = $faker->numberBetween(1,99) . '.jpg';
                
                //$picture = $picture . ($genre == 'male' ? 'men/' : 'women/') . $pictureId;
                $picture.=($genre == 'male' ? 'men/' : 'women/') . $pictureId;
                
                $hash = $this->encoder->encodePassword($user ,'password');

                $user->setFirstname($faker->firstName($genre))
                     ->setLastname($faker->lastName)
                     ->setEmail($faker->email)
                     ->setIntroduction($faker->sentence())
                     ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                     ->setHash($hash)
                     ->setPicture($picture);

                     $manager->persist($user);
                     $users[] = $user;
                
            }
            
              //  créer 3 category fixture category
             
            for ($c = 0; $c <= 3; $c++) {
                $category = new Category();
                $category->setTitle($faker->sentence())
                         ->setDescription($faker->paragraph());

                $manager->persist($category);

                $ville = new Ville();
                $ville->setTitle($faker->sentence());

                $manager->persist($ville);
        
                // créer des ads pour ces category
                 
                for ($i = 1; $i <= mt_rand(4,6); $i++) {
                    $ad = new Ad();
        
                    $name = $faker->sentence();
                    $coverImage = $faker->imageUrl(800, 530);
                    $description = $faker->paragraph(2);
                    $longitude = $faker -> longitude($min = -180, $max = 180);
                    $latitude = $faker -> latitude($min = -90, $max = 90);
                    $createdAt = $faker->dateTimeBetween('-6 months');
                    // COUNT(user) - 1 = 9 
                    $user = $users[mt_rand(0, count($users) - 1)];
        
                    $ad->setName($name)
                        ->setPaiement("paiement par chéque")
                        ->setDescription($description)
                        ->setCoverImage($coverImage)
                        ->setCategory($category)
                        ->setLat($latitude)
                        ->setLongi($longitude)
                        ->setAuthor($user)
                        ->setVille($ville)
                        ->setUpdatedAt($createdAt);
        
                    /**
                     * créer fixture image
                     */
                    for ($j = 1; $j <= mt_rand(2, 5); $j++) {
                        $image = new Image();
                        $image->setUrl($faker->imageUrl())
                              ->setCaption($faker->sentence())
                              ->setAd($ad);
        
                        $manager->persist($image);
                    }
                   
                    $manager->persist($ad);
                }

            }
        
        $manager->flush();
    }
}
