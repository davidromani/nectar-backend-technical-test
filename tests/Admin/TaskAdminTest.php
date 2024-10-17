<?php

namespace App\Tests\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskAdminTest extends WebTestCase
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
            '/admin/task/list',
            '/admin/task/create',
            '/admin/task/1/edit',
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
            '/admin/task/101/edit',
            '/admin/task/1/show',
            '/admin/task/1/delete',
            '/admin/task/batch',
        ];
    }
}
