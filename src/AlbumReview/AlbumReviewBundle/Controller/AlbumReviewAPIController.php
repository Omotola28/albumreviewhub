<?php

namespace AlbumReview\AlbumReviewBundle\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;

class AlbumReviewAPIController extends AbstractFOSRestController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * Get all reviews
     * /api/v1/albums/reviews
     */
    public function getAlbumsReviewsAction() {

        $em = $this->getDoctrine()->getManager();
        $albumEntries = $em->getRepository('AlbumReviewAlbumReviewBundle:ReviewEntry')
            ->findAll();

        if(empty($albumEntries)) {
            return new  JsonResponse([
                'error' => 'No reviews found'
            ], 404);
        }

        return $this->handleView($this->view($albumEntries, 200));
    }


    /**
     * @param $albumId
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * Get all the reviews of a SPECIFIC album
     * /api/v1/albums/{album_id}/reviews
     */
    public function getAlbumReviewsAction($albumId)
    {
        $em = $this->getDoctrine()->getManager();


        $albumEntry = $em->getRepository('AlbumReviewAlbumReviewBundle:AlbumEntry')->find($albumId);

        if(empty($albumEntry)){
            return new  JsonResponse([
                'error' => 'Album does not exsist'
            ], 404);
        }

        $albumReviews = $em->getRepository('AlbumReviewAlbumReviewBundle:ReviewEntry')
                           ->getAlbumReviews($albumEntry->getId());

        if(empty($albumReviews)) {

            return new  JsonResponse([
                'error' => 'Album does not contain review'
            ], 404);

        }

        return $this->handleView($this->view($albumReviews, 200));
    }



    /**
     * @param $albumId
     * @param $reviewId
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * We are getting a SPECIFIC review for a SPECIFIC album
     * /api/v1/albums/{album_id}/reviews/{review_id}
     *
     */
    public function getAlbumReviewAction($albumId, $reviewId){
        $em = $this->getDoctrine()->getManager();
        $albumEntry = $em->getRepository('AlbumReviewAlbumReviewBundle:AlbumEntry')->find($albumId);

        if(empty($albumEntry)){
            return new  JsonResponse([
                'error' => 'Album does not exsist'
            ], 404);
        }

        $albumReviews = $em->getRepository('AlbumReviewAlbumReviewBundle:ReviewEntry')
            ->getAlbumSpecificReview($albumEntry->getId(), $reviewId);

        if(empty($albumReviews)) {
            //Request was successful but no reviews currently exist.

            return new  JsonResponse([
                'error' => 'Album does not contain review'
            ], 404);

        }

        return $this->handleView($this->view($albumReviews, 200));
    }

}
