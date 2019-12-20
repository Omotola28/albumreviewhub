<?php

namespace AlbumReview\AlbumReviewBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AlbumReview\AlbumReviewBundle\Entity\AlbumEntry;
use AlbumReview\AlbumReviewBundle\Form\AlbumEntryType;
use Symfony\Component\HttpFoundation\Request;

class ReviewController extends Controller
{
    public function viewAction($id)
    {
        // Get the doctrine Entity manager
        $em = $this->getDoctrine()->getManager();
        // Use the entity manager to retrieve the Entry entity for the id
        // that has been passed
        $reviewEntry = $em->getRepository('AlbumReviewAlbumReviewBundle:AlbumEntry')->find($id);
        // Pass the entry entity to the view for displaying
        return $this->render('AlbumReviewAlbumReviewBundle:Review:view.html.twig',
            ['entry' => $reviewEntry]);
    }

    public function createAction(Request $request)
    {

        // Create an new (empty) AlbumEntry entity
        $reviewEntry = new AlbumEntry();

        // Create a form from the EntryType class to be validated
        // against the AlbumEntry entity and set the form action attribute
        // to the current URI
        $form = $this->createForm(AlbumEntryType::class, $reviewEntry,[
            'action' => $request->getUri()
        ]);

        // If the request is post it will populate the form
        $form->handleRequest($request);
        // validates the form
        if($form->isValid()) {
            // Retrieve the doctrine entity manager
            $em = $this->getDoctrine()->getManager();
            // manually set the author to the current user
            $reviewEntry->setAuthor($this->getUser());
            $reviewEntry->setReviewer($this->getUser());
            // tell the entity manager we want to persist this entity
            $em->persist($reviewEntry);
            // commit all changes
            $em->flush();

            return $this->redirect($this->generateUrl('review_view',
                ['id' => $reviewEntry->getId()]));
        }

        return $this->render('AlbumReviewAlbumReviewBundle:Review:create.html.twig',
            ['form' => $form->createView()]);

    }

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $reviewEntry = $em->getRepository('AlbumReviewAlbumReviewBundle:AlbumEntry')->find($id);
        $form = $this->createForm(AlbumEntryType::class, $reviewEntry, [
            'action' => $request->getUri()
        ]);
        $form->handleRequest($request);
        if($form->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('review_view',
                ['id' => $reviewEntry->getId()]));
        }
        return $this->render('AlbumReviewAlbumReviewBundle:Review:edit.html.twig',
            ['form' => $form->createView(),
                'entry' => $reviewEntry]);
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $reviewEntry = $em->getRepository('AlbumReviewAlbumReviewBundle:AlbumEntry')->find($id);
        $em->remove($reviewEntry);
        $em->flush();

        return $this->redirect(
            $this->generateUrl('AlbumReviewAlbumReviewBundle_index'));
    }

}
