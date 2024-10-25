<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class BaseApiTest extends ApiTestCase
{
    protected ?EntityManagerInterface $em = null;

    protected function getDoctrine(): EntityManagerInterface
    {
        if (is_null($this->em)) {
            static::bootKernel();
            $this->em = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        }

        return $this->em;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws \JsonException
     */
    protected static function createAuthenticatedClient(string $username = '1email@email.com', string $password = 'password1'): Client
    {
        $client = static::createClient();
        $response = $client->request(
            Request::METHOD_POST,
            '/api/login_check',
            [
                'headers' => ['Content-Type' => 'application/json'],
                'base_uri' => 'https://localhost:4443',
                'json' => [
                    'username' => $username,
                    'password' => $password
                ],
            ]
        );
        $token = json_decode($response->getContent(), false, 512, JSON_THROW_ON_ERROR)->token;
        $client->setDefaultOptions(['auth_bearer' => $token]);

        return $client;
    }
}
