<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Entity\User;
use App\Entity\Photo;
use App\Form\TripType;
use App\Entity\FeaturedImage;
use App\Services\InsertFiles;
use App\Services\ImageOptimizer;

use App\Repository\TripRepository;
use App\Repository\PhotoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
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
     * @Route("/public", name="trip_public_index")
     */
    public function publicIndex(TripRepository $tripRepository): Response
    {

        // if (!$this->getUser()) {
        //     return $this->redirectToRoute('app_login');
        // }

        return $this->render('trip/public.html.twig', [
            'controller_name' => 'IndexController',
            'trip' => $tripRepository->findAll()

        ]);
    }

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


        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            //'image' dans les add de l'entity
            $files = $form->get('image')->getData();

            $unicFolder = $user->getFolder() . '/trip/' . md5(uniqid() . '/');
            $where = $this->getParameter('images_directory') . $unicFolder;

            $services = new InsertFiles;
            $services->CreateFolder($where);

            foreach ($files as $image) {
                $filename =  md5(uniqid()) . "." . $image->guessExtension();

                if ($image) {

                    try {

                        $image->move(
                            $where,
                            $filename
                        );

                        // $resizeImg = new ImageOptimizer;
                        // $resizeImg->resize('img'.'/'.$unicFolder.'/'.$filename);

                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
                }

                $photo = new Photo;
                $photo->setSource($unicFolder . '/' . $filename);
                $trip->addPhoto($photo);
                $entityManager->persist($photo);
            }

            $trip->setUser($user);
            $trip->setFolder($unicFolder);
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
     * @Route("/{id}/public", name="trip_show_public", methods={"GET"})
     */
    public function showPublic(Trip $trip): Response
    {
        // if (!$this->getUser()) {
        //     return $this->redirectToRoute('app_login');
        // }

        return $this->render('trip/publicShow.html.twig', [
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
            $folder = $trip->getFolder();
            $where =  $this->getParameter('images_directory') . $folder;

            foreach ($files as $image) {
                $filename =  md5(uniqid()) . "." . $image->guessExtension();
                if ($image) {
                    try {
                        $image->move(
                            $where,
                            $filename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
                }
                $photo = new Photo;
                $photo->setSource($folder . '/' . $filename);
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
    public function delete(Request $request, Trip $trip): Response
    {
        if ($this->isCsrfTokenValid('delete' . $trip->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

            $filesystem = new Filesystem();
            $filesystem->remove([$this->getParameter('images_directory') . $trip->getFolder()]);

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

    /**
     * @Route("/{id}/feature", name="photo_featured", methods={"GET","POST"})
     */
    public function featuredPhoto(Photo $photo, EntityManagerInterface $entityManager)
    {

        $trip = $photo->getTrip();

        if ($trip->getFeaturedImage()) {
            return $this->redirectToRoute('trip_edit', ['id' => $trip->getId()]);
        }

        $featuredImage = new FeaturedImage;
        $featuredImage->setSource($photo->getSource());
        $featuredImage->setTrip($trip);

        $entityManager->persist($featuredImage);
        $entityManager->flush();
        return $this->redirectToRoute('trip_edit', ['id' => $trip->getId()]);
    }
}
