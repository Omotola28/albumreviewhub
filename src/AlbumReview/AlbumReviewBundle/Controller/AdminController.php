<?php

namespace AlbumReview\AlbumReviewBundle\Controller;

use AlbumReview\AlbumReviewBundle\Entity\User;
use AlbumReview\AlbumReviewBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    /**
     * @Route("/viewUsers")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewUsersAction(Request $request)
    {
        // Get the doctrine Entity manager
        $em = $this->getDoctrine()->getManager();

        //Get all users from the database
        $registeredUsers = $em->getRepository('AlbumReviewAlbumReviewBundle:User')->findAll();


        return $this->render('AlbumReviewAlbumReviewBundle:Admin:view_users.html.twig',
            ['users' => $registeredUsers]);

    }

    public function privilegeControlAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AlbumReviewAlbumReviewBundle:User')->find($id);

        $albumCount = $em->getRepository('AlbumReviewAlbumReviewBundle:AlbumEntry')->countAlbumEntryByUser($id);

        $userPermissionsForm = new User();


        $assignmentForm = $this->createForm(UserType::class, $userPermissionsForm,[
            'action' => $request->getUri()
        ]);

        // If the request is post it will populate the form
        $assignmentForm->handleRequest($request);
        // validates the form
        if($assignmentForm->isSubmitted() && $assignmentForm->isValid()) {
            // Retrieve the doctrine entity manager
            $em = $this->getDoctrine()->getManager();

            $role = $assignmentForm->get("roles_options")->getData();
            $userManager = $this->get('fos_user.user_manager');

            //If the user mistakenly tries to send the choose option
            if(is_null($role))
            {
                return $this->render('AlbumReviewAlbumReviewBundle:Admin:privilege_control.html.twig',
                      [ 'form' => $assignmentForm->createView(),
                        'user' => $user,
                        'noOfAlbums' => $albumCount,
                        'error' => 'Select valid option'
                      ]);
            }
            else
            {
                //Decide what action should be performed on the user
                $requestMsg = $assignmentForm->get('GRANT')->isClicked()
                    ? $this->handlePrivilegeRequest($user, $role, $operation = "GRANT")
                    : $this->handlePrivilegeRequest($user, $role, $operation = "REVOKE");

                $userManager->updateUser($user);
                $em->flush();

                $registeredUsers = $em->getRepository('AlbumReviewAlbumReviewBundle:User')->findAll();


                return $this->render('AlbumReviewAlbumReviewBundle:Admin:view_users.html.twig',
                    ['users' => $registeredUsers, 'message' => $requestMsg]);
            }


        }
        return $this->render('AlbumReviewAlbumReviewBundle:Admin:privilege_control.html.twig',
            ['form' => $assignmentForm->createView(), 'user' => $user, 'noOfAlbums' => $albumCount ]);
    }

    public function handlePrivilegeRequest($user, $role, $operation)
    {
        $user_roles = $user->getRoles();
        if(in_array($role, $user_roles) && $operation === 'GRANT')
        {
            return "User has been granted this role";
        }
        else if (!(in_array($role, $user_roles)) && $operation === 'REVOKE') {

            return "User has not been granted this role";
        }

        $operation === 'GRANT' ? $user->addRole($role) : $user->removeRole($role);

        return "Done!";

    }

}
