<?php

namespace App\Security;

use App\Discord\DiscordProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class DiscordAuthenticator extends AbstractGuardAuthenticator
{
    private $discordProvider;

    public function __construct(DiscordProvider $discordProvider)
    {
        $this->discordProvider = $discordProvider;
    }
    public function supports(Request $request)
    {
        return $request->query->get('code');
    }

    public function getCredentials(Request $request)
    {
        return [
            'code' => $request->query->get('code')
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        //LOGIC => GET USER FROM DISCORD
        return $this->discordProvider->loadUserFromDiscord($credentials['code']);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // todo
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // todo
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        // todo
    }

    public function supportsRememberMe()
    {
        // todo
    }
}
