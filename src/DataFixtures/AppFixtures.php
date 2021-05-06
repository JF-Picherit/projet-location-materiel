<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Thing;
use App\Entity\ThingInstance;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Catégories
        $data = [
            'Téléphone' => [
                'iPhone 7', 'iPhone 8'
            ],
            'Ordinateur' => [
                'Dell Latitude 5780',
                'MacBook Air',
                'Asus 8722'
            ],
            'Cable/adaptateurs' => [
                'Cable HDMI',
                'Adaptateur HDMI/VGA'
            ]
        ];

        foreach ($data as $categoryName => $things) {
            $category = new Category;
            $category->setName($categoryName);
            $manager->persist($category);

            foreach ($things as $thingName) {
                $thing = new Thing;
                $thing
                    ->setName($thingName)
                    ->setCategory($category)
                    ;
                $manager->persist($thing);

                for ($k=0; $k<mt_rand(3,10); $k++) {
                    $instance = new ThingInstance;
                    $instance
                        ->setThing($thing)
                        ->setSerial(substr(md5(mt_rand()), 0, 8))
                        ;
                    $manager->persist($instance);
                }
            }
        }     

        $manager->flush();
    }
}
