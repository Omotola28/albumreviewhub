<?php

namespace AlbumReview\AlbumReviewBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $albumEntries = $em->getRepository('AlbumReviewAlbumReviewBundle:AlbumEntry')
            ->getLatest(10, 0);

        return $this->render('AlbumReviewAlbumReviewBundle:Page:index.html.twig',
            ['entries' => $albumEntries]);
    }

    public function aboutAction()
    {
        return $this->render('AlbumReviewAlbumReviewBundle:Page:about.html.twig', array(
            //..
        ));
    }

}
