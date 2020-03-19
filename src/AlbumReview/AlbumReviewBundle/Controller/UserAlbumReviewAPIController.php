<?php


namespace AlbumReview\AlbumReviewBundle\Controller;

use AlbumReview\AlbumReviewBundle\Entity\AlbumEntry;
use AlbumReview\AlbumReviewBundle\Entity\ReviewEntry;
use AlbumReview\AlbumReviewBundle\Form\AlbumEntryAPIType;
use AlbumReview\AlbumReviewBundle\Form\ReviewEntryAPIType;
use AlbumReview\AlbumReviewBundle\Form\ReviewEntryType;
use FOS\RestBundle\Controller\AbstractFOSRestController;
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
    public function postUserAlbumsReviewsAction(Request $request, $userId, $albumId){
        $em = $this->getDoctrine()->getManager();
        $reviewEntry = new ReviewEntry();

        $form = $this->createForm(ReviewEntryAPIType::class, $reviewEntry);


        if($request->getContentType() != 'json') {
            return $this->handleView($this->view(null, 400));
        }

        // json_decode the request content and pass it to the form
        $form->submit(json_decode($request->getContent(), true));


        $user = $em->getRepository('AlbumReviewAlbumReviewBundle:User')->find($userId);


        if(is_null($user)){
            return $this->handleView($this->view(null, 404));
        }
        else{
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $album = $em->getRepository('AlbumReviewAlbumReviewBundle:AlbumEntry')->find($albumId);
                $reviewEntry->setAlbum($album);
                $reviewEntry->setAlbumReviewer($user);
                $reviewEntry->setTimestamp(new \DateTime());
                // tell the entity manager we want to persist this entity
                $em->persist($reviewEntry);
                // commit all changes
                $em->flush();


                return $this->handleView($this->view(null, 201)
                    ->setLocation(
                        $this->generateUrl('api_user_album_review_post_user_albums_reviews',
                            ['id' => $reviewEntry->getId(), 'userId' => $user->getId(), 'albumId' => $album->getId()])));
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
    public function getUserAlbumsReviewsAction($userId, $albumId)
    {

        $em = $this->getDoctrine()->getManager();
        $albumReviews = $em->getRepository('AlbumReviewAlbumReviewBundle:ReviewEntry')
            ->getUserSpecificAblumReviews($userId, $albumId);

        if(empty($albumReviews)){
            return $this->handleView($this->view(null, 404));  //NOT FOUND
        }
        else{
            return $this->handleView($this->view($albumReviews));
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
     * /api/v1/users/{userid}/albums/reviews/{id}
     */
    public function putUserAlbumsReviewsAction(Request $request, $userId, $albumId, $reviewId)
    {
        $em = $this->getDoctrine()->getManager();
        $reviewEntry = $em->getRepository('AlbumReviewAlbumReviewBundle:ReviewEntry')->find($reviewId);
        $album = $em->getRepository('AlbumReviewAlbumReviewBundle:AlbumEntry')->find($albumId);
        $user = $em->getRepository('AlbumReviewAlbumReviewBundle:User')->find($userId);

        //Check if the review we want to modify is in the album folder
        $reviewExsist = $em->getRepository('AlbumReviewAlbumReviewBundle:ReviewEntry')
            ->checkForReviewInAlbum($reviewId, $albumId);

        $reviewForm = $this->createForm(ReviewEntryAPIType::class, $reviewEntry);

        if($request->getContentType() != 'json') {
            return $this->handleView($this->view(null, 400));
        }

        // json_decode the request content and pass it to the form
        $reviewForm->submit(json_decode($request->getContent(), true));

        if(is_null($user)){
            return $this->handleView($this->view('User not found', 404));
        }
        elseif(empty($reviewExsist)){
            return $this->handleView($this->view('Review not found in album', 404));
        }
        else{
            if($reviewForm->isValid()) {
                $em->flush();

                return $this->handleView($this->view(null, 201)
                    ->setLocation(
                        $this->generateUrl('api_user_album_review_put_user_albums_reviews',
                            ['reviewId' => $reviewEntry->getId(), 'userId' => $user->getId(), 'albumId' => $album->getId()])));
            }
            else{
                return $this->handleView($this->view($reviewForm, 400));
            }
        }

    }


    /**
     * @param $userId
     * @param $albumId
     * @param $reviewId
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * Deleting a specific album's review
     * /api/v1/users/{userid}/albums/reviews/{id}
     */
    public function deleteUserAlbumsReviewsAction( $userId, $albumId, $reviewId)
    {
        $em = $this->getDoctrine()->getManager();
        $entry = $em->getRepository('AlbumReviewAlbumReviewBundle:ReviewEntry')->find($reviewId);
        $album = $em->getRepository('AlbumReviewAlbumReviewBundle:AlbumEntry')->find($albumId);
        $user = $em->getRepository('AlbumReviewAlbumReviewBundle:User')->find($userId);

        $reviewExsist = $em->getRepository('AlbumReviewAlbumReviewBundle:ReviewEntry')
            ->checkForReviewInAlbum($reviewId, $albumId);

        if(is_null($user)){
            return $this->handleView($this->view('User not found', 404));
        }
        elseif(empty($reviewExsist)){
            return $this->handleView($this->view('Review not found in album', 404));
        }
        else{
            $em->remove($entry);
            $em->flush();

            return $this->handleView($this->view('Successfully Deleted', 200)
                ->setLocation(
                    $this->generateUrl('api_user_album_review_put_user_albums_reviews',
                        ['reviewId' => 0, 'userId' => $user->getId(), 'albumId' => $album->getId()])));
        }
    }

    /**
     * @param $userId
     * @param $albumId
     * @param $reviewId
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * Get a specific album's review
     * /api/v1/users/{userid}/albums/reviews/{id}
     */
    public function getUserAlbumReviewAction( $userId, $albumId, $reviewId){
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AlbumReviewAlbumReviewBundle:User')->find($userId);

        $reviewExsist = $em->getRepository('AlbumReviewAlbumReviewBundle:ReviewEntry')
            ->checkForReviewInAlbum($reviewId, $albumId);

        if(is_null($user)){
            return $this->handleView($this->view('User not found', 404));
        }
        elseif(empty($reviewExsist)){
            return $this->handleView($this->view('Review not found in album', 404));
        }

        $review = $em->getRepository('AlbumReviewAlbumReviewBundle:ReviewEntry')
            ->getUserAlbumSpecificReview($reviewId, $albumId, $userId);

        return $this->handleView($this->view($review));
    }

}
