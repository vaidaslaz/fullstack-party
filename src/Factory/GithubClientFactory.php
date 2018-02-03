<?php

/*
 * (c) Vaidas Lažauskas <vaidas@notrix.lt>
 */

namespace App\Factory;

use App\UserAwareTrait;
use Github\Client;
use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * App\Factory\GithubClientFactory
 */
class GithubClientFactory
{
    use UserAwareTrait;

    /**
     * Class constructor
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        $client = new Client();

        if ($token = $this->getUserToken()) {
            $client->authenticate($token, null, Client::AUTH_HTTP_TOKEN);
        }

        return $client;
    }

    /**
     * @return string|null
     */
    protected function getUserToken()
    {
        $token = $this->getAuthenticatedToken();

        return $token instanceof OAuthToken ? $token->getAccessToken() : null;
    }
}
