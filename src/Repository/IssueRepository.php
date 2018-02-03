<?php

/*
 * (c) Vaidas Lažauskas <vaidas@notrix.lt>
 */

namespace App\Repository;

use App\Exception\UserNotFoundException;
use App\UserAwareTrait;
use Github\Api;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUser;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * App\Repository\IssueRepository
 */
class IssueRepository
{
    use UserAwareTrait;

    const STATE_OPEN = 'open';
    const STATE_CLOSED = 'closed';

    /**
     * @var Api\Search
     */
    protected $searchApi;

    /**
     * Class constructor
     *
     * @param Api\Search            $searchApi
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(Api\Search $searchApi, TokenStorageInterface $tokenStorage)
    {
        $this->searchApi = $searchApi;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return int
     *
     * @throws UserNotFoundException
     */
    public function countOpenIssues(): int
    {
        return $this->countIssues(self::STATE_OPEN);
    }

    /**
     * @return int
     *
     * @throws UserNotFoundException
     */
    public function countClosedIssues(): int
    {
        return $this->countIssues(self::STATE_CLOSED);
    }

    /**
     * @param string $state
     * @param int    $page
     *
     * @return array
     *
     * @throws UserNotFoundException
     */
    public function getList(string $state = self::STATE_OPEN, int $page = 1): array
    {
        $currentUser = $this->getCurrentUser();
        if (!$currentUser) {
            throw new UserNotFoundException('User not logged in');
        }

        $this->searchApi->setPage($page);

        return $this->searchApi->issues(
            sprintf('assignee:%s state:%s', $currentUser->getUsername(), $state)
        );
    }

    /**
     * @param string $state
     *
     * @return int
     *
     * @throws UserNotFoundException
     */
    protected function countIssues(string $state = null): int
    {
        $currentUser = $this->getCurrentUser();
        if (!$currentUser) {
            throw new UserNotFoundException('User not logged in');
        }

        $result = $this->searchApi->issues(
            sprintf('assignee:%s state:%s', $currentUser->getUsername(), $state)
        );

        return $result['total_count'] ?? 0;
    }

    /**
     * @return OAuthUser|null
     */
    protected function getOauthUser(): ?OAuthUser
    {
        $user = $this->getCurrentUser();

        return $user instanceof OAuthUser ? $user : null;
    }
}
