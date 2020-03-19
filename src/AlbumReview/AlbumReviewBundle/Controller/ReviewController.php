<?php

namespace AlbumReview\AlbumReviewBundle\Controller;

use AlbumReview\AlbumReviewBundle\AlbumReviewAlbumReviewBundle;
use AlbumReview\AlbumReviewBundle\Entity\AlbumEntry;
use AlbumReview\AlbumReviewBundle\Entity\ReviewEntry;
use AlbumReview\AlbumReviewBundle\Form\ReviewEntryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ReviewController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
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
            //Retrieve the album and store the object in a variable
            $album = $em->getRepository('AlbumReviewAlbumReviewBundle:AlbumEntry')->find($request->query->get('id'));
            //Set album for review created
            $reviewEntry->setAlbum($album);
            // manually set the author to the current user
            $reviewEntry->setUser($this->getUser());
            $reviewEntry->setAlbumReviewer($this->getUser());
            $reviewEntry->setTimestamp(new \DateTime());
            // tell the entity manager we want to persist this entity
            $em->persist($reviewEntry);
            // commit all changes
            $em->flush();

            return $this->redirect($this->generateUrl('album_view',
                ['id' => $album->getId() ]));
        }

        return $this->render('AlbumReviewAlbumReviewBundle:Review:create_review.html.twig',
            ['form' => $reviewForm->createView()]);
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editReviewAction($id, Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $reviewEntry = $em->getRepository('AlbumReviewAlbumReviewBundle:ReviewEntry')->find($id);

        $reviewForm = $this->createForm(ReviewEntryType::class, $reviewEntry, [
            'action' => $request->getUri()
        ]);

        $reviewForm->handleRequest($request);

        if( $reviewEntry->getAlbumReviewer() == $this->getUser())
        {
            if($reviewForm->isValid()) {
                $em->flush();

                return $this->redirect($this->generateUrl('album_view',
                    ['id' => $reviewEntry->getAlbum()->getId()]));
            }
            return $this->render('AlbumReviewAlbumReviewBundle:Review:edit_review.html.twig',
                ['form' => $reviewForm->createView(),
                    'review' => $reviewEntry]);
        }
        else
        {
            return $this->redirect(
                $this->generateUrl('index', ['error' => 'Only review author can edit review']));
        }

    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteReviewAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $reviewEntry = $em->getRepository('AlbumReviewAlbumReviewBundle:ReviewEntry')->find($id);

        $array_roles = $this->getUser()->getRoles();


        //if the user has any of these priviliges or the actual user is currently signed in
        if($this->role_in_roles(["ROLE_ADMIN", "ROLE_MODERATOR"], $array_roles) ||
            $reviewEntry->getAlbumReviewer() == $this->getUser())
        {

            $em->remove($reviewEntry);
            $em->flush();

            return $this->redirect(
                $this->generateUrl('index', ['success' => 'successfully deleted review']));
        }
        else
        {
            return $this->redirect(
                $this->generateUrl('index', ['error' => 'No permission to delete review']));
        }
    }

    /**
     * @param $needles
     * @param $haystack
     * @return bool
     */
    public function role_in_roles($needles, $haystack)
    {
        return empty(array_diff($needles, $haystack));
    }

}
