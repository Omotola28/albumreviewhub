<?php

namespace AlbumReview\AlbumReviewBundle\Controller;

use AlbumReview\AlbumReviewBundle\Entity\ReviewEntry;
use AlbumReview\AlbumReviewBundle\Form\ReviewEntryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ReviewController extends Controller
{
    public function createReviewAction(Request $request)
    {
        // Create an new (empty) ReviewEntry entity
        $reviewEntry = new ReviewEntry();

        // Create a form from the EntryType class to be validated
        // against the AlbumEntry entity and set the form action attribute
        // to the current URI
        $reviewForm = $this->createForm(ReviewEntryType::class, $reviewEntry,[
            'action' => $request->getUri()
        ]);

        // If the request is post it will populate the form
        $reviewForm->handleRequest($request);
        // validates the form
        if($reviewForm->isValid()) {
            // Retrieve the doctrine entity manager
            $em = $this->getDoctrine()->getManager();
            // manually set the author to the current user
            $reviewEntry->setAuthor($this->getUser());
            $reviewEntry->setAlbumReviewer($this->getUser());
            $reviewEntry->setTimestamp(new \DateTime());
            // tell the entity manager we want to persist this entity
            $em->persist($reviewEntry);
            // commit all changes
            $em->flush();

            return $this->redirect($this->generateUrl('album_view',
                ['id' => $reviewEntry->getId()]));
        }

        return $this->render('AlbumReviewAlbumReviewBundle:Review:create_review.html.twig',
            ['form' => $reviewForm->createView()]);
    }

    public function editReviewAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $reviewEntry = $em->getRepository('AlbumReviewAlbumReviewBundle:ReviewEntry')->find($id);

        $reviewForm = $this->createForm(ReviewEntryType::class, $reviewEntry, [
            'action' => $request->getUri()
        ]);

        $reviewForm->handleRequest($request);
        if($reviewForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('album_view',
                ['id' => $reviewEntry->getId()]));
        }
        return $this->render('AlbumReviewAlbumReviewBundle:Review:edit_review.html.twig',
            ['form' => $reviewForm->createView(),
                'review' => $reviewEntry]);
    }

    public function deleteReviewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $reviewEntry = $em->getRepository('AlbumReviewAlbumReviewBundle:ReviewEntry')->find($id);
        $em->remove($reviewEntry);
        $em->flush();

        return $this->redirect(
            $this->generateUrl('AlbumReviewAlbumReviewBundle_index'));
    }

}
