<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Entity\Photo;
use App\Form\PhotoType;
use App\Repository\PhotoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @Route("/photo")
 */
class PhotoController extends AbstractController
{
   
    /**
     * @Route("/{id}", name="photo_show", methods={"GET"})
     */
    public function show(Photo $photo): Response
    {
        return $this->render('photo/show.html.twig', [
            'photo' => $photo,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="photo_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Photo $photo): Response
    {
        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('photo_show',['id' => $photo->getId()]);
        }

        return $this->render('photo/edit.html.twig', [
            'photo' => $photo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="photo_delete", methods={"POST"})
     */
    public function delete(Request $request, Photo $photo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$photo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($photo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('photo_show',['id' => $photo->getId()]);
    }

    /**
     * @Route("/{id}/feature", name="photo_featured", methods={"GET","POST"})
     */
    public function featuredPhoto(Photo $photo, PhotoRepository $photoRepository)
    {
        
     $check = $photoRepository->findBy(['featured' => 1,'trip' => $photo->getTrip()]);

     if ($check) {

        foreach ($check as $checks) {
            $checks->setFeatured(false);
        }
        
     }

     $photo->setFeatured(true);

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($photo);
    $entityManager->flush();

    $trip = $photo->getTrip();

    return $this->redirectToRoute('trip_edit',['id' => $trip->getId()]);

    }
}
