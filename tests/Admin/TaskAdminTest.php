<?php

namespace App\Tests\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class TaskAdminTest extends BaseAdminTest
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
            '/admin/task/list',
            '/admin/task/create',
            '/admin/task/1/edit',
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
            '/admin/task/101/edit',
            '/admin/task/1/show',
            '/admin/task/1/delete',
            '/admin/task/batch',
        ];
    }
}
