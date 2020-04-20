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
            return new  JsonResponse([
                'error' => 'User not found'
            ], 404);
        }


        $isValid = $this->get('security.password_encoder')
            ->isPasswordValid($user, $request->request->get('password'));
        if (!$isValid) {
            return new  JsonResponse([
                'error' => 'Password is invalid'
            ], 401);
        }

        $token = $this->get('lexik_jwt_authentication.encoder')
            ->encode([
                'username' => $user->getUsername(),
                'exp' => time() + 86400, // 1 day expiration
            ]);


        return new JsonResponse(['token' => $token], 200);
    }

}
