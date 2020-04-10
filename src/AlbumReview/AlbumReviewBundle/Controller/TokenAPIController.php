<?php

namespace AlbumReview\AlbumReviewBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;


class TokenAPIController extends Controller
{

    /**
     *Get new tokens for users
     * @param Request $request
     * @return JsonResponse
     */
    public function postTokensAction(Request $request) {

        $user = $this->getDoctrine()
            ->getRepository('AlbumReviewAlbumReviewBundle:User')
            ->findOneBy(['username' => $request->request->get('username')]);

        if (!$user) {
            throw $this->createNotFoundException();
        }


        $isValid = $this->get('security.password_encoder')
            ->isPasswordValid($user, $request->request->get('password'));
        if (!$isValid) {
            throw new BadCredentialsException();
        }

        $token = $this->get('lexik_jwt_authentication.encoder')
            ->encode([
                'username' => $user->getUsername(),
                'exp' => time() + 86400, // 1 day expiration
                'role' => 'ROLE_API'
            ]);


        return new JsonResponse(['token' => $token]);
       /* 'headers' => [
            'Authorization' => 'Bearer '.$token
        ]*/
    }

}
