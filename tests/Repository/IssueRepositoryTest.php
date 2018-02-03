<?php

/*
 * (c) Vaidas Lažauskas <vaidas@notrix.lt>
 */

namespace App\Tests\Repository;

use App\Repository\IssueRepository;
use Github\Api\Search;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUser;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * App\Tests\Repository\IssueRepositoryTest
 */
class IssueRepositoryTest extends TestCase
{
    /**
     * @var MockObject|Search
     */
    protected $searchApi;

    /**
     * @var MockObject|TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var IssueRepository
     */
    protected $repository;

    /**
     * Test countOpenIssues with no logged user
     *
     * @expectedException \App\Exception\UserNotFoundException
     */
    public function testCountOpenIssuesWithNoUser()
    {
        $token = $this->createMock(TokenInterface::class);
        $this->tokenStorage->expects($this->once())
            ->method('getToken')
            ->willReturn($token);

        $this->repository->countOpenIssues();
    }

    /**
     * Test countClosedIssues with valid user
     */
    public function testCountClosedIssuesWithValidUser()
    {
        $this->mockUser();

        $expected = 123;
        $this->searchApi->expects($this->once())
            ->method('issues')
            ->willReturn(['total_count' => $expected]);

        $result = $this->repository->countClosedIssues();

        $this->assertEquals($expected, $result);
    }

    /**
     * Test getList with optimistic scenario
     */
    public function testGetList()
    {
        $this->mockUser();

        $page = 4;
        $state = 'open';

        $this->searchApi->expects($this->once())
            ->method('setPage')
            ->with($page);

        $this->searchApi->expects($this->once())
            ->method('issues')
            ->willReturn([]);

        $this->repository->getList($state, $page);
    }

    /**
     * @throws \ReflectionException
     */
    protected function mockUser()
    {
        $token = $this->createMock(TokenInterface::class);
        $user = new OAuthUser('test');

        $token->expects($this->any())
            ->method('isAuthenticated')
            ->willReturn(true);

        $token->expects($this->any())
            ->method('getUser')
            ->willReturn($user);

        $this->tokenStorage->expects($this->once())
            ->method('getToken')
            ->willReturn($token);
    }

    /**
     * @throws \ReflectionException
     */
    protected function setUp()
    {
        $this->searchApi = $this->getMockBuilder(Search::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);

        $this->repository = new IssueRepository($this->searchApi, $this->tokenStorage);
    }
}
