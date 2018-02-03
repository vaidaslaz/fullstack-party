<?php

/*
 * (c) Vaidas Lažauskas <vaidas@notrix.lt>
 */

namespace App\Tests\Factory;

use App\Factory\GithubClientFactory;
use Github\Client;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * App\Tests\Factory\GithubClientFactoryTest
 */
class GithubClientFactoryTest extends TestCase
{
    /**
     * @var MockObject|TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var GithubClientFactory
     */
    protected $factory;

    /**
     * Test factory creating valid client
     */
    public function testGetClient()
    {
        $client = $this->factory->getClient();

        $this->assertInstanceOf(Client::class, $client);
    }

    /**
     * Sets up test
     */
    protected function setUp()
    {
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);

        $this->factory = new GithubClientFactory($this->tokenStorage);
    }
}
