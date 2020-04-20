<?php


namespace AlbumReview\AlbumReviewBundle\Security;

use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class JwtTokenAuthenticator extends AbstractGuardAuthenticator
{

    /**
     * @var JWTEncoderInterface
     */
    private $jwtEncoder;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(JWTEncoderInterface $jwtEncoder, EntityManagerInterface $em)
    {
        $this->jwtEncoder = $jwtEncoder;
        $this->em = $em;
    }


    /**
     * @inheritDoc
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {

        $message = $authException ? $authException->getMessageKey() : 'Missing credentials';

        return new JsonResponse([
            'error' => $message
        ], 401);

    }

    /**
     * @inheritDoc
     */
    public function getCredentials(Request $request)
    {
        $extractor = new AuthorizationHeaderTokenExtractor(
            'Bearer',
            'Authorization'
        );

        $token = $extractor->extract($request);

        if (!$token) {
            return null;
        }

        return $token;
    }

    /**
     * @inheritDoc
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        try {
            $data = $this->jwtEncoder->decode($credentials);


            $username = $data['username'];

            return $this->em
                ->getRepository('AlbumReviewAlbumReviewBundle:User')
                ->findOneBy(['username' => $username]);

        } catch (JWTDecodeFailureException $e) {

            throw new CustomUserMessageAuthenticationException('Invalid Token');
        }
    }

    /**
     * @inheritDoc
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        //on success let the request continue
        return null;
    }

    /**
     * @inheritDoc
     */
    public function supportsRememberMe()
    {
        return false;
    }
}

