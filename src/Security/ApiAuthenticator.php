<?php
declare(strict_types=1);

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

/**
 * ApiAuthenticator
 *
 * @author Jean-Claude <jeanyao@ymail.com>
 *
 * @license https://github.com/jeandev84/laventure-framework/blob/master/LICENSE
 *
 * @package  App\Security
*/
class ApiAuthenticator extends AbstractAuthenticator
{

    /**
     * @inheritDoc
    */
    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization')
               && str_contains($request->headers->get('Authorization'), 'Bearer ');
    }




    /**
     * @inheritDoc
    */
    public function authenticate(Request $request): Passport
    {
        $identifier = str_replace('Bearer ', '', $request->headers->get('Authorization'));

        return new SelfValidatingPassport(
           new UserBadge($identifier)
        );
    }




    /**
     * @inheritDoc
    */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
         return null;
    }



    /**
     * @inheritDoc
    */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
         return new JsonResponse([
             'message' => $exception->getMessage()
         ], Response::HTTP_UNAUTHORIZED);
    }
}