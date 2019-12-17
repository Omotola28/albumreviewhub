<?php

namespace AlbumReview\AlbumReviewBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{
    public function indexAction()
    {
        return $this->render('AlbumReviewAlbumReviewBundle:Page:index.html.twig', array(
            // ...
        ));
    }

    public function aboutAction()
    {
        return $this->render('AlbumReviewAlbumReviewBundle:Page:about.html.twig', array(
            //..
        ));
    }

}
