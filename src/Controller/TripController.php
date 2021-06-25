<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Repository\PhotoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TripController extends AbstractController
{
    /**
     * @Route("/trip/{id}", name="app_trip")
     */
    public function index(Trip $trip): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }


        return $this->render('trip/index.html.twig', [
            'controller_name' => 'TripController',
            'trip' => $trip,
            
        ]);
    }
}
