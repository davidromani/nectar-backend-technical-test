<?php

namespace App\Tests\Admin;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class BaseAdminTest extends WebTestCase
{
    protected const string ADMIN_FIREWALL_CONTEXT = 'admin';

    protected ?KernelBrowser $kernelBrowserClient = null;

    public function setUp(): void
    {
        if (is_null($this->kernelBrowserClient)) {
            $kernel = static::bootKernel();
            $passwd = $kernel->getContainer()->getParameter('app_admin_user_password');
            $client = $kernel->getContainer()->get('test.client');
            $client->setServerParameters([
                'PHP_AUTH_USER' => 'nectar',
                'PHP_AUTH_PW'   => $passwd,
            ]);
            $this->kernelBrowserClient = static::getClient($client);
        }
    }

    public function testSuccessfulPages(): void
    {
        $this->kernelBrowserClient->request(Request::METHOD_GET, '/admin/login');
        self::assertResponseIsSuccessful();
    }
}
