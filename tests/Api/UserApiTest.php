<?php

namespace App\Tests\Api;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

final class UserApiTest extends BaseApiTest
{
    public function testPost(): void
    {
        $totalUsersAmount = $this->em->getRepository(User::class)->getTotalUsersAmount();
        self::assertEquals(100, $totalUsersAmount);
        $this->kernelBrowserClient->jsonRequest(
            Request::METHOD_POST,
            '/api/users',
            [
                'name' => 'User Test API 1',
                'email' => 'user1@api.com',
                'password' => '1234',
            ],
        );

//        self::assertResponseIsSuccessful();
        $content = $this->kernelBrowserClient->getResponse()->getContent();
        dd($content);
        self::assertJson($content);
        $jsonResponse = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        dd($jsonResponse);


//        self::assertArrayHasKey('totalItems', $jsonResponse);
//        self::assertEquals(50, $jsonResponse['totalItems']);
//        $this->kernelBrowserClient->request(Request::METHOD_GET, '/api/tasks?page=1&status='.TaskStatusEnum::COMPLETED->value);
//        self::assertResponseIsSuccessful();
//        $content = $this->kernelBrowserClient->getResponse()->getContent();
//        self::assertJson($content);
//        $jsonResponse = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
//        self::assertArrayHasKey('totalItems', $jsonResponse);
//        self::assertEquals(50, $jsonResponse['totalItems']);
    }
}
