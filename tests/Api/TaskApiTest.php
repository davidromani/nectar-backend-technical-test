<?php

namespace App\Tests\Api;

use App\Enum\TaskStatusEnum;
use Symfony\Component\HttpFoundation\Request;

class TaskApiTest extends BaseApiTest
{
    public function testGet(): void
    {
        $this->kernelBrowserClient->request(Request::METHOD_GET, '/api/tasks?page=1&status='.TaskStatusEnum::PENDING->value);
        self::assertResponseIsSuccessful();
        $content = $this->kernelBrowserClient->getResponse()->getContent();
        self::assertJson($content);
        $jsonResponse = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        self::assertArrayHasKey('totalItems', $jsonResponse);
        self::assertEquals(50, $jsonResponse['totalItems']);
//        dd($jsonResponse);
        $this->kernelBrowserClient->request(Request::METHOD_GET, '/api/tasks?page=1&status='.TaskStatusEnum::COMPLETED->value);
        self::assertResponseIsSuccessful();
        $content = $this->kernelBrowserClient->getResponse()->getContent();
        self::assertJson($content);
        $jsonResponse = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        self::assertArrayHasKey('totalItems', $jsonResponse);
        self::assertEquals(50, $jsonResponse['totalItems']);
    }
}
