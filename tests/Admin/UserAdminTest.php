<?php

namespace App\Tests\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

    public function testNotFoundPages(): void
    {
        $client = static::createClient();
        foreach (self::provideNotFoundUrls() as $url) {
            $client->request(Request::METHOD_GET, $url);
            self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        }
    }

    public static function provideNotFoundUrls(): array
    {
        return [
            '/admin/user/101/edit',
            '/admin/user/1/show',
            '/admin/user/1/delete',
            '/admin/user/batch',
        ];
    }
}
