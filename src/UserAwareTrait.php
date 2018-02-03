<?php

/*
 * (c) Vaidas Lažauskas <vaidas@notrix.lt>
 */

namespace App;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * App\UserAwareTrait
 */
trait UserAwareTrait
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @return TokenInterface|null
     */
    protected function getAuthenticatedToken()
    {
        $token = $this->tokenStorage->getToken();
        if (!$token instanceof TokenInterface || !$token->isAuthenticated()) {
            return null;
        }

        return $token instanceof TokenInterface && $token->isAuthenticated() ? $token : null;
    }

    /**
     * @return UserInterface|null
     */
    protected function getCurrentUser()
    {
        $token = $this->getAuthenticatedToken();

        return $token ? $token->getUser() : null;
    }
}
