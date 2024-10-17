<?php

namespace App\Tests\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class UserAdminTest extends BaseAdminTest
{
    public function testSuccessfulPages(): void
    {
        foreach (self::provideSuccessfulUrls() as $url) {
            $this->kernelBrowserClient->request(Request::METHOD_GET, $url);
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
        foreach (self::provideNotFoundUrls() as $url) {
            $this->kernelBrowserClient->request(Request::METHOD_GET, $url);
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
