<?php


namespace AlbumReview\AlbumReviewBundle\Controller;

use AlbumReview\AlbumReviewBundle\Entity\ReviewEntry;
use AlbumReview\AlbumReviewBundle\Form\ReviewEntryAPIType;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserAlbumReviewAPIController extends AbstractFOSRestController
{

    /**
     * @param Request $request
     * @param $userId
     * @param $albumId
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     *
     * Post a review by a SPECIFIC User on a SPECIFIC Album
     * /api/v1/users/{userid}/albums/{album_id}/reviews
     */
    public function postUsersAlbumsReviewsAction(Request $request, $userId, $albumId){

        $reviewEntry = new ReviewEntry();
        $form = $this->createForm(ReviewEntryAPIType::class, $reviewEntry);

        if($request->getContentType() != 'json') {
            return $this->handleView($this->view(null, 400));
        }

        // json_decode the request content and pass it to the form
        $form->submit(json_decode($request->getContent(), true));
        $user = $this->getUser();

        if($user->getId() !== intval($userId)){
            return new  JsonResponse([
                'error' => 'User token does not match userid'
            ], 401);
           // return $this->handleView($this->view(null, 404));
        }
        else{
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $album = $em->getRepository('AlbumReviewAlbumReviewBundle:AlbumEntry')->find($albumId);
                $reviewEntry->setAlbum($album);
                $reviewEntry->setUser($user);
                $reviewEntry->setAlbumReviewer($user);
                $reviewEntry->setTimestamp(new \DateTime());
                // tell the entity manager we want to persist this entity
                $em->persist($reviewEntry);
                // commit all changes
                $em->flush();

                return $this->handleView($this->view(null, 201));

            }
            else{
                return $this->handleView($this->view($form, 400));
            }
        }
    }

    /**
     * @param $userId
     * @param $albumId
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * Get a review for a SPECIFIC album by a SPECIFIC user
     * /api/v1/users/{userid}/albums/{album_id}/reviews
     */
    public function getUsersAlbumsReviewsAction($userId, $albumId)
    {

        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        if($user->getId() !== intval($userId)) {
            return new  JsonResponse([
                'error' => 'User token does not match userid'
            ], 401);

        }
        else{
            $albumReviews = $em->getRepository('AlbumReviewAlbumReviewBundle:ReviewEntry')
                ->getUserSpecificAblumReviews($user->getId(), $albumId);

            if(empty($albumReviews)){
                return new  JsonResponse([
                    'error' => 'No reviews found for this user'
                ], 404);
            }
            else{
                return $this->handleView($this->view($albumReviews));
            }
        }
    }

    /**
     * @param Request $request
     * @param $userId
     * @param $albumId
     * @param $reviewId
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * Editing a specific albums review
     * /api/v1/users/{userid}/albums/{albumid}/reviews/{id}
     */
    public function putUsersAlbumsReviewsAction(Request $request, $userId, $albumId, $reviewId)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $reviewEntry = $em->getRepository('AlbumReviewAlbumReviewBundle:ReviewEntry')->find($reviewId);

        if($user->getId() !== intval($userId)){
            return new  JsonResponse([
                'error' => 'User token does not match userid'
            ], 401);
            // return $this->handleView($this->view(null, 404));
        }

        if($request->getContentType() != 'json') {
            return $this->handleView($this->view(null, 400));
        }
        //Check if the review we want to modify is owned by the user and also belongs to album
        $review = $em->getRepository('AlbumReviewAlbumReviewBundle:ReviewEntry')
            ->checkForReview($reviewId, $albumId, $user->getId());

        if(empty($review)){
            return new  JsonResponse([
                'error' => 'Review not found'
            ], 404);
        }

        $reviewForm = $this->createForm(ReviewEntryAPIType::class, $reviewEntry);

        // json_decode the request content and pass it to the form
        $reviewForm->submit(json_decode($request->getContent(), true));

        if($reviewForm->isValid()) {
            $em->flush();

            return new  JsonResponse([
                'message' => 'Successfully Updated'
            ], 204);
        }
        else{
            return $this->handleView($this->view($reviewForm, 400));
        }
    }


    /**
     * @param $userId
     * @param $albumId
     * @param $reviewId
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * Deleting a specific album's review
     * /api/v1/users/{userid}/albums/{albumid}/reviews/{id}
     */
    public function deleteUsersAlbumsReviewsAction( $userId, $albumId, $reviewId)
    {
        $em = $this->getDoctrine()->getManager();
        $entry = $em->getRepository('AlbumReviewAlbumReviewBundle:ReviewEntry')->find($reviewId);
        $user = $this->getUser();

        if($user->getId() !== intval($userId)){
            return new  JsonResponse([
                'error' => 'User token does not match userid'
            ], 401);
        }

        $review = $em->getRepository('AlbumReviewAlbumReviewBundle:ReviewEntry')
            ->checkForReview($reviewId, $albumId, $user->getId());


        if(empty($review)){
            return new  JsonResponse([
                'error' => 'Review not found'
            ], 404);
        }
        else{
            $em->remove($entry);
            $em->flush();

            return new  JsonResponse([
                'message' => 'Successfully Deleted'
            ], 204);
        }
    }

    /**
     * @param $userId
     * @param $albumId
     * @param $reviewId
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * Get a specific album's review
     * /api/v1/users/{userid}/albums/{albumid}/reviews/{id}
     */
    public function getUsersAlbumReviewAction( $userId, $albumId, $reviewId){
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if($user->getId() !== intval($userId)) {
            return new  JsonResponse([
                'error' => 'User token does not match userid'
            ], 401);

        }

        if(empty($review)){
            return new  JsonResponse([
                'error' => 'Review not found'
            ], 404);
        }
        else{
            $review = $em->getRepository('AlbumReviewAlbumReviewBundle:ReviewEntry')
                ->checkForReview($reviewId, $albumId, $user->getId());

            return $this->handleView($this->view($review));
        }
    }
}
