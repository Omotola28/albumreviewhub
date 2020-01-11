<?php

namespace AlbumReview\AlbumReviewBundle\Controller;


use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class PageController extends Controller
{
    public function indexAction(PaginatorInterface $paginator, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $albumEntriesQuery = $em->getRepository('AlbumReviewAlbumReviewBundle:AlbumEntry')
            ->getLatest(10, 0);

        $result = $paginator->paginate(
            $albumEntriesQuery, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            8 /*limit per page*/
        );

        return $this->render('AlbumReviewAlbumReviewBundle:Page:index.html.twig',
            ['entries' => $result]);
    }

    public function aboutAction()
    {
        return $this->render('AlbumReviewAlbumReviewBundle:Page:about.html.twig', array(
            //..
        ));
    }


}
