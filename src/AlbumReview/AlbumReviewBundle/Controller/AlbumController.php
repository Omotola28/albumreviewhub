<?php

namespace AlbumReview\AlbumReviewBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AlbumReview\AlbumReviewBundle\Entity\AlbumEntry;
use AlbumReview\AlbumReviewBundle\Form\AlbumEntryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class AlbumController extends Controller
{
    public function viewAction($id)
    {
        // Get the doctrine Entity manager
        $em = $this->getDoctrine()->getManager();
        // Use the entity manager to retrieve the Entry entity for the id
        // that has been passed
        $albumEntry = $em->getRepository('AlbumReviewAlbumReviewBundle:AlbumEntry')->find($id);

        $reviewEntry = $em->getRepository('AlbumReviewAlbumReviewBundle:ReviewEntry')->getAssociatedReviews($id);
        // Pass the entry entity to the view for displaying
        return $this->render('AlbumReviewAlbumReviewBundle:Album:view.html.twig',
            ['entry' => $albumEntry, 'reviews' => $reviewEntry]);
    }

    public function createAction(Request $request)
    {

        // Create an new (empty) AlbumEntry entity
        $albumEntry = new AlbumEntry();

        // Create a form from the EntryType class to be validated
        // against the AlbumEntry entity and set the form action attribute
        // to the current URI
        $form = $this->createForm(AlbumEntryType::class, $albumEntry,[
            'action' => $request->getUri()
        ]);

        // If the request is post it will populate the form
        $form->handleRequest($request);
        // validates the form
        if($form->isValid()) {
            // Retrieve the doctrine entity manager
            $em = $this->getDoctrine()->getManager();
            // manually set the author to the current user
            $albumEntry->setAuthor($this->getUser());
            $albumEntry->setReviewer($this->getUser());
            $albumEntry->setTimestamp(new \DateTime());
            // tell the entity manager we want to persist this entity
            $em->persist($albumEntry);
            // commit all changes
            $em->flush();

            return $this->redirect($this->generateUrl('album_view',
                ['id' => $albumEntry->getId()]));
        }

        return $this->render('AlbumReviewAlbumReviewBundle:Album:create.html.twig',
            ['form' => $form->createView()]);

    }

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $albumEntry = $em->getRepository('AlbumReviewAlbumReviewBundle:AlbumEntry')->find($id);
        $form = $this->createForm(AlbumEntryType::class, $albumEntry, [
            'action' => $request->getUri()
        ]);
        $form->handleRequest($request);
        if($form->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('album_view',
                ['id' => $albumEntry->getId()]));
        }
        return $this->render('AlbumReviewAlbumReviewBundle:Album:edit.html.twig',
            ['form' => $form->createView(),
                'entry' => $albumEntry]);
    }

    public function deleteAction($id)
    {
        //Get roles associated with user
        $array_roles = $this->getUser()->getRoles();

        $em = $this->getDoctrine()->getManager();

        //For if the delete operation does not happen
        $albumEntries = $em->getRepository('AlbumReviewAlbumReviewBundle:AlbumEntry')
            ->getLatest(10, 0);

        //album that the user would like to delete
        $albumEntry = $em->getRepository('AlbumReviewAlbumReviewBundle:AlbumEntry')->find($id);

        if(in_array("ROLE_ADMIN", $array_roles) || $albumEntry->getAuthor() == $this->getUser())
        {

            $em->remove($albumEntry);
            $em->flush();

            return $this->redirect(
                $this->generateUrl('index', ['message' => 'successfully deleted']));
        }
        else
        {
            return $this->render('AlbumReviewAlbumReviewBundle:Page:index.html.twig',
                ['entries' => $albumEntries, 'message' => 'You have to have admin privileges to delete this album']);
        }

    }

    public function searchBarAction()
    {
        $form = $this->createFormBuilder(null)
            ->add('search', TextType::class)
            ->getForm();

        return $this->render('AlbumReviewAlbumReviewBundle:Page:search.html.twig',
            ['form' => $form->createView()]);
    }

    public function handleSearchAction(Request $request)
    {
        $queryString = $request->request->get('form');

        $em = $this->getDoctrine()->getManager();

        //Get search results from query
        $searchResult = $em->getRepository('AlbumReviewAlbumReviewBundle:AlbumEntry')
            ->getSearchResults($queryString['search']);

        //If no results were gotten alert the user
        if(count($searchResult) == 0)
            return $this->render('AlbumReviewAlbumReviewBundle:Page:noresult.html.twig');

        //If all is well show user the results of search.
        return $this->render('AlbumReviewAlbumReviewBundle:Page:index.html.twig',
            ['entries' => $searchResult]);
    }

}
