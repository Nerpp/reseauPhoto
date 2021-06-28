<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\Trip;
use App\Entity\User;
use App\Form\TripType;
use App\Repository\PhotoRepository;
use App\Repository\TripRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @Route("/trip")
 */
class TripController extends AbstractController
{


    /**
     * @Route("/home", name="trip_index")
     */
    public function index(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('trip/index.html.twig', [
            'controller_name' => 'IndexController',
            'user' => $this->getUser(),

        ]);
    }


    /**
     * @Route("/new", name="trip_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {

        $trip = new Trip();
        $form = $this->createForm(TripType::class, $trip);
        $form->handleRequest($request);

        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            //'image' dans les add de l'entity
            $files = $form->get('image')->getData();


            foreach ($files as $image) {

                $filename =  md5(uniqid()) . "." . $image->guessExtension();
                if ($image) {
                    try {
                        $image->move(
                            $this->getParameter('images_directory'),
                            $filename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
                }
                $photo = new Photo;
                $photo->setSource($filename);
                $trip->addPhoto($photo);
                $entityManager->persist($photo);
            }

            $trip->setUser($user);
            $entityManager->persist($trip);
            $entityManager->flush();

            return $this->redirectToRoute('trip_index');
        }

        return $this->render('trip/new.html.twig', [
            'trip' => $trip,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="trip_show", methods={"GET"})
     */
    public function show(Trip $trip): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('trip/show.html.twig', [
            'trip' => $trip,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="trip_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Trip $trip): Response
    {
        $form = $this->createForm(TripType::class, $trip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();


            //'image' dans les add de l'entity
            $files = $form->get('image')->getData();


            foreach ($files as $image) {

                $filename =  md5(uniqid()) . "." . $image->guessExtension();
                if ($image) {
                    try {
                        $image->move(
                            $this->getParameter('images_directory'),
                            $filename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
                }
                $photo = new Photo;
                $photo->setSource($filename);
                $trip->addPhoto($photo);
                $entityManager->persist($photo);
            }
            $entityManager->flush();
            // $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('trip_index');
        }

        return $this->render('trip/edit.html.twig', [
            'trip' => $trip,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="trip_delete", methods={"POST"})
     */
    public function delete(Request $request, Trip $trip,PhotoRepository $photoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $trip->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $check = $photoRepository->findBy(['trip' => $trip->getId()]);
           
            if ($check) {
                foreach ($check as $checks) {
                    unlink($this->getParameter('images_directory') . '/' . $checks->getSource());
                }
            }

            $entityManager->remove($trip);
            $entityManager->flush();
        }

        return $this->redirectToRoute('trip_index');
    }

    /**
     * @Route("/feature/{id}", name="select_feature"):
     */
    public function selectFeature(Trip $trip): Response
    {

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('trip/feature.html.twig', [
            'trip' => $trip,
        ]);
    }
}
