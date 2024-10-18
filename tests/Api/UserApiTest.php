<?php

namespace App\Tests\Api;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

final class UserApiTest extends BaseApiTest
{
    public function testPost(): void
    {
        $email = sprintf('%s@test.com', uniqid('', true));
        $totalUsersAmount = $this->em->getRepository(User::class)->getTotalUsersAmount();
//        self::assertEquals(100, $totalUsersAmount);
        $this->kernelBrowserClient->jsonRequest(
            Request::METHOD_POST,
            '/api/users',
            [
                'name' => 'User Test API 1',
                'email' => $email,
                'password' => '1234',
            ],
        );
        self::assertResponseIsSuccessful();
        $content = $this->kernelBrowserClient->getResponse()->getContent();
        self::assertJson($content);
        $jsonResponse = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
//        dd($jsonResponse);
        self::assertArrayHasKey('id', $jsonResponse);
        self::assertEquals($totalUsersAmount + 1, $jsonResponse['id']);
    }
}
