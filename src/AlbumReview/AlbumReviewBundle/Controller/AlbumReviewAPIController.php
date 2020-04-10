<?php

namespace AlbumReview\AlbumReviewBundle\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;

class AlbumReviewAPIController extends AbstractFOSRestController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * Get all reviews
     * /api/v1/albums/reviews
     */
    public function getAlbumsReviewsAction() {

       // $this->denyAccessUnlessGranted('ROLE_API');

        $em = $this->getDoctrine()->getManager();
        $albumEntries = $em->getRepository('AlbumReviewAlbumReviewBundle:ReviewEntry')
            ->findAll();

        if(empty($albumEntries)) {
            //Request was successful but no reviews currently exist.
            return $this->handleView($this->view(null, 204));
        }

        return $this->handleView($this->view($albumEntries));
    }

    /**
     * @param $albumId
     * @param $reviewId
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * Get all the reviews of a SPECIFIC album
     * /api/v1/albums/{album_id}/reviews
     */
    public function getAlbumReviewAction($albumId, $reviewId){
        $em = $this->getDoctrine()->getManager();
        $albumReviews = $em->getRepository('AlbumReviewAlbumReviewBundle:ReviewEntry')
            ->getAlbumSpecificReview($albumId, $reviewId);

        if(empty($albumReviews)) {
            //Request was successful but no reviews currently exist.
            return $this->handleView($this->view('Album does not contain reviews', 204));
        }

        return $this->handleView($this->view($albumReviews));
    }

    /**
     * @param $albumId
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * We are getting a SPECIFIC review for a SPECIFIC album
     * /api/v1/albums/{album_id}/reviews/{review_id}
     */
    public function getAlbumReviewsAction($albumId)
    {
        $em = $this->getDoctrine()->getManager();
        $albumReviews = $em->getRepository('AlbumReviewAlbumReviewBundle:ReviewEntry')->getAlbumReviews($albumId);

        if(empty($albumReviews)) {
            //Request was successful but no reviews currently exist.
            return $this->handleView($this->view(null, 204));
        }

        return $this->handleView($this->view($albumReviews));
    }
}
