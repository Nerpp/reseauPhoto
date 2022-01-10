<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Trip;
use App\Entity\User;

use App\Entity\Photo;
use App\Entity\Profile;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Filesystem\Filesystem;
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
                'name' => 'Regi',
                'surname' =>'Aurélien',
                'displayName' => 'Aurélien',
                'email'    => 'regiaurelien@gmail.com',
                'profile' => 'null',
                'role' => array('ROLE_SUPERADMIN','ROLE_ADMIN')
            ],
            [
                'name' => 'Dark',
                'surname' =>'Vador',
                'displayName' => 'Dark Vador',
                'email'    => 'darkvador@gmail.com',
                'profile' => 'null',
                'role' => ['USER']
            ],
            [
                'surname' => 'Des batignolles',
                'name' => 'Marie-thérése',
                'displayName' => 'Marie-thérése Des batignolles',
                'email'    => 'batignolles@gmail.com',
                'profile' => 'tn_mtherese.jpg',
                'role' => ['USER']
            ],
          
        ];

        $listFolder = [
            [
                'folder' => '/trip/'
            ],
            [
                'folder' => '/profile/'
            ]
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
           

            $filesystem = new Filesystem();
            $userFolder = $userListed['name'].md5(uniqid());
            $where = "C:\wamp64\www/reseauPhoto/public/img/";
           
            if (!$filesystem->exists($where.$userFolder)){
                $filesystem->mkdir($where.$userFolder, 0777);
            }
            $userFolderAll[] = $userFolder;

            foreach ($listFolder as $folderListed) {
                $folderType = $userFolder.$folderListed['folder'];
                if (!$filesystem->exists($where.$folderType)){
                    $filesystem->mkdir($where.$folderType, 0777);
                }
                $folderTypeAll[] =  $folderType;
            }

            $profile = new Profile;
            $profile->setSource($folderTypeAll[1].$userListed['profile']);
            $manager->persist($profile);

            $user = new User;
            $user->setEmail($userListed['email']);
            $user->setName($userListed['name']);
            $user->setSurname($userListed['surname']);
            $user->setDisplayName($userListed['displayName']);
            $user->setRoles($userListed['role']);
            $user->setPassword($this->encoder->hashPassword($user,"123456"));
            $user->setCreatedAt(new \DateTime('now'));
            $user->setProfile($profile);

            for ($i=0; $i < count($userFolderAll) ; $i++) { 
                $user->setFolder($userFolderAll[$i]);
            }


            $manager->persist($user);
            $allUser[] = $user;



        }
        $manager->flush();


        foreach ($listTrip as $tripListed) {

            $filesystem = new Filesystem();

            $tripFolder = $folderTypeAll[0].md5(uniqid());
 
            $trip = new Trip;
            $trip->setName($tripListed['trip']);
            $trip->setDescription($tripListed['description']);
            $trip->setUser($allUser[0]);
            $trip->setFolder($tripFolder);
            $manager->persist($trip);
            $allTrip[] = $trip;

            if (!$filesystem->exists($where.$tripFolder)){
                $filesystem->mkdir($where.$tripFolder, 0777);
            }
            $tripFolderAll[] = $tripFolder;

        }
        $manager->flush();
        
        foreach ($listTatooine as $listedTatooin) {
            $photo = new Photo;
            $photo->setSource($tripFolderAll[1].'/'.$listedTatooin['photo']);
            
            $photo->setDescription($listedTatooin['description']);
            $photo->setTrip($allTrip[0]);
            $manager->persist($photo);
        }

        foreach ($listAlderande as $alderandeListed) {
            $photo = new Photo;
            $photo->setSource($tripFolderAll[0].'/'.$alderandeListed['photo']);
            $photo->setDescription($alderandeListed['description']);
            $photo->setTrip($allTrip[1]);
            $manager->persist($photo);
        }
        $manager->flush();
    }
}
