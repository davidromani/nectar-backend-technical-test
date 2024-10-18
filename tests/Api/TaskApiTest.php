<?php

namespace App\Tests\Api;

use App\Enum\TaskStatusEnum;
use Symfony\Component\HttpFoundation\Request;

final class TaskApiTest extends BaseApiTest
{
    private const string BASE_URL = '/api/tasks';

    private static function buildUrl(string $path): string
    {
        return sprintf('%s%s', self::BASE_URL, $path);
    }

    public function testGet(): void
    {
        $this->kernelBrowserClient->request(Request::METHOD_GET, self::buildUrl('?page=1&status='.TaskStatusEnum::PENDING->value));
        self::assertResponseIsSuccessful();
        $content = $this->kernelBrowserClient->getResponse()->getContent();
        self::assertJson($content);
        $jsonResponse = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        self::assertArrayHasKey('totalItems', $jsonResponse);
        self::assertEquals(50, $jsonResponse['totalItems']);
        $this->kernelBrowserClient->request(Request::METHOD_GET, self::buildUrl('?page=1&status='.TaskStatusEnum::COMPLETED->value));
        self::assertResponseIsSuccessful();
        $content = $this->kernelBrowserClient->getResponse()->getContent();
        self::assertJson($content);
        $jsonResponse = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        self::assertArrayHasKey('totalItems', $jsonResponse);
        self::assertEquals(50, $jsonResponse['totalItems']);
    }

    public function testBadPost(): void
    {
        $this->kernelBrowserClient->jsonRequest(
            Request::METHOD_POST,
            self::BASE_URL,
            [
                'title' => 'Bad Task Test API 1',
            ],
        );
        self::assertResponseIsUnprocessable();
    }
}
