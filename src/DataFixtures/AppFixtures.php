<?php

namespace App\DataFixtures;


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
        

     
    }
}
