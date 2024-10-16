<?php

namespace App\Tests\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class UserAdminTest extends WebTestCase
{
    public function testSuccessfulPages(): void
    {
        $client = static::createClient();
        foreach (self::provideSuccessfulUrls() as $url) {
            $client->request(Request::METHOD_GET, $url);
            self::assertResponseIsSuccessful();
        }
    }

    public static function provideSuccessfulUrls(): array
    {
        return [
            '/admin/dashboard',
            '/admin/user/list',
            '/admin/user/create',
            '/admin/user/1/edit',
        ];
    }
}
