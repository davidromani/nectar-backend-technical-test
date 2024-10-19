<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Request;

class BaseApiTest extends ApiTestCase
{
    protected ?KernelBrowser $kernelBrowserClient = null;
    protected ?EntityManagerInterface $em = null;

    public function setUp(): void
    {
        if (is_null($this->kernelBrowserClient)) {
            $kernel = static::bootKernel();
            $client = $kernel->getContainer()->get('test.client');
            $this->kernelBrowserClient = static::getClient($client);
            $this->em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        }
    }

    public function testGet(): void
    {
        $this->kernelBrowserClient->request(Request::METHOD_GET, '/api/docs');
        self::assertResponseIsSuccessful();
    }
}
