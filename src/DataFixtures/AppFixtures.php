<?php

namespace App\DataFixtures;

use App\Entity\Photo;
use DateTime;
use App\Entity\Trip;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class AppFixtures extends Fixture
{
    public function __construct( UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;

    }

    public function load(ObjectManager $manager)
    {
        $listUser = [
            [
                'name' => 'Dark',
                'surname' =>'Vador',
                'email'    => 'darkvador@gmail.com'
            ],
            [
                'name' => 'Des batignolles',
                'surname' => 'Marie-thérése',
                'email'    => 'batignolles@gmail.com'
            ],
          
        ];

        $listTrip = [
            [
                'trip' => 'Voyage à tatooine',
                'description' => 'besoin de vacances tranquille'
            ],
            [
                'trip' => 'Alderande',
                'description'=> 'je suis sur que c\'est sympa'
            ]
        ];

      

            $listTatooine = [
                [
                    'photo' => 'tn_concert.jpeg',
                    'description' => 'Un concert pas mal, mais ce type était relou !!'
                ],
                [
                     'photo' => 'tn_maison_tatooine.jpg',
                     'description' => 'Le prix de l\'immobilier est vraiment pas cher'
                 ],
                 [
                     'photo' => 'tn_sexy.jpg',
                     'description' => 'Des talons sur du sable faut être maso, mais ce petit boul sympa'
                 ],
                 [
                     'photo' => 'tn_souvenir.jpg',
                     'description' => 'Ca capte super bien la bas grâce a leurs petites antennes'
                 ],
                 [
                     'photo' => 'tn_tatooine.jpg',
                     'description' => 'Inutile de louer un kayak, sur du sable ça Force '
                 ],
               
            ];




       $listAlderande = [
           [
               'photo' => 'tn_Alderande.jpg',
               'description' => 'Mouais pas mal'
           ],
           [
                'photo' => 'tn_Alderande2.jpg',
                'description' => 'les routes sont pas trés fréquenté'
            ],
            [
                'photo' => 'tn_Alderande3.jpg',
                'description' => 'J\'ai tout faity exploser c\'est un attrape touriste cette planéte'
            ],
            [
                'photo' => 'tn_Alderande4.jpg',
                'description' => 'La prochaine fois ils reflechiront au prix de leurs souvenir, je suis pas radin mais il faut pas Forcer quoi !!!'
            ],
            [
                'photo' => 'tn_Alderande5.jpg',
                'description' => 'J\'avai ramené tout mes potes sur cette planétes de merde'
            ],
            [
                'photo' => 'tn_Alderande6.jpg',
                'description' => 'Faites des gosses... Cancrelat de m** alors comme ça c\'est méchant de dominer la Galaxie'
            ],
       ];

        foreach($listUser as $userListed)
        {
            $user = new User;
            $user->setEmail($userListed['email']);
            $user->setName($userListed['name']);
            $user->setSurname($userListed['surname']);
            $user->setPassword($this->encoder->hashPassword($user,"123456"));
            $user->setCreatedAt(new \DateTime('+3 days'));
            $manager->persist($user);
            $allUser[] = $user;

        }
        $manager->flush();


        foreach ($listTrip as $tripListed) {
            $trip = new Trip;
            $trip->setName($tripListed['trip']);
            $trip->setDescription($tripListed['description']);
            $trip->setUser($allUser[0]);
            $manager->persist($trip);
            $allTrip[] = $trip;
        }
        $manager->flush();

      
        foreach ($listTatooine as $listedTatooin) {
            $photo = new Photo;
            $photo->setSource($listedTatooin['photo']);
            $photo->setDescription($listedTatooin['description']);
            $photo->setTrip($allTrip[0]);
            $manager->persist($photo);
        }

        foreach ($listAlderande as $alderandeListed) {
            $photo = new Photo;
            $photo->setSource($alderandeListed['photo']);
            $photo->setDescription($alderandeListed['description']);
            $photo->setTrip($allTrip[1]);
            $manager->persist($photo);
        }
        $manager->flush();
    }
}
