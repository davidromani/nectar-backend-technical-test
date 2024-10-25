<?php

namespace App\Tests\Api;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

final class UserApiTest extends BaseApiTest
{
    public const string BASE_URL = '/api/users';

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws \JsonException
     */
    public function testPost(): void
    {
        $email = sprintf('%s@test.com', uniqid('', true));
        $totalUsersAmount = $this->em->getRepository(User::class)->getTotalUsersAmount();
        self::assertEquals(100, $totalUsersAmount);
        $response = self::createAuthenticatedClient()->request(
            Request::METHOD_POST,
            self::BASE_URL,
            [
                'json' => [
                    'name' => 'User Test API 1',
                    'email' => $email,
                    'password' => '1234',
                ],
            ],
        );
        self::assertResponseIsSuccessful();
        $content = $response->getContent();
        self::assertJson($content);
        $jsonResponse = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        self::assertArrayHasKey('id', $jsonResponse);
        self::assertArrayHasKey('name', $jsonResponse);
        self::assertArrayHasKey('email', $jsonResponse);
        self::assertArrayHasKey('password', $jsonResponse);
        self::assertEquals($email, $jsonResponse['email']);
        self::assertEquals($totalUsersAmount + 1, $jsonResponse['id']);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws \JsonException
     */
    public function testBadPost(): void
    {
        self::createAuthenticatedClient()->request(
            Request::METHOD_POST,
            self::BASE_URL,
            [
                'json' => [
                    'name' => 'Bad User Test API 2',
                ],
            ],
        );
        self::assertResponseIsUnprocessable();
        self::createAuthenticatedClient()->request(
            Request::METHOD_POST,
            self::BASE_URL,
            [
                'json' => [
                    'name' => 'User Test API 3',
                    'email' => 'invalid email',
                    'password' => '1234',
                ],
            ],
        );
        self::assertResponseIsUnprocessable();
    }
}
