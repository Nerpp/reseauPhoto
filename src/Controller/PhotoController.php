<?php

namespace App\Controller;

use App\Entity\Photo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PhotoController extends AbstractController
{
    /**
     * @Route("/photo/{id}", name="app_photo_show")
     */
    public function index(Photo $photo): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('photo/index.html.twig', [
            'controller_name' => 'PhotoController',
            'photos' => $photo
        ]);
    }
}
