<?php

namespace App\Tests\Api;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

final class UserApiTest extends BaseApiTest
{
    public const string BASE_URL = '/api/users';

    public function testPost(): void
    {
        $email = sprintf('%s@test.com', uniqid('', true));
        $totalUsersAmount = $this->em->getRepository(User::class)->getTotalUsersAmount();
        self::assertEquals(100, $totalUsersAmount);
        $this->kernelBrowserClient->jsonRequest(
            Request::METHOD_POST,
            self::BASE_URL,
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
        self::assertArrayHasKey('id', $jsonResponse);
        self::assertArrayHasKey('name', $jsonResponse);
        self::assertArrayHasKey('email', $jsonResponse);
        self::assertArrayHasKey('password', $jsonResponse);
        self::assertEquals($email, $jsonResponse['email']);
        self::assertEquals($totalUsersAmount + 1, $jsonResponse['id']);
    }

    public function testBadPost(): void
    {
        $this->kernelBrowserClient->jsonRequest(
            Request::METHOD_POST,
            self::BASE_URL,
            [
                'name' => 'Bad User Test API 2',
            ],
        );
        self::assertResponseIsUnprocessable();
        $this->kernelBrowserClient->jsonRequest(
            Request::METHOD_POST,
            self::BASE_URL,
            [
                'name' => 'User Test API 3',
                'email' => 'invalid email',
                'password' => '1234',
            ],
        );
        self::assertResponseIsUnprocessable();
    }
}
