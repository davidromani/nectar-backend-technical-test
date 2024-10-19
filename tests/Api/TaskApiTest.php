<?php

namespace App\Tests\Api;

use App\Entity\AbstractBase;
use App\Entity\Task;
use App\Entity\User;
use App\Enum\TaskStatusEnum;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

    public function testGetFilteredByUser(): void
    {
        $user = $this->em->getRepository(User::class)->find(1);
        $this->kernelBrowserClient->request(Request::METHOD_GET, self::buildUrl('?page=1&status='.TaskStatusEnum::PENDING->value.'&user='.$user->getId()));
        self::assertResponseIsSuccessful();
        $content = $this->kernelBrowserClient->getResponse()->getContent();
        self::assertJson($content);
        $jsonResponse = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        self::assertArrayHasKey('totalItems', $jsonResponse);
        self::assertEquals(1, $jsonResponse['totalItems']);
        $this->kernelBrowserClient->request(Request::METHOD_GET, self::buildUrl('?page=1&status='.TaskStatusEnum::COMPLETED->value.'&user='.$user->getId()));
        self::assertResponseIsSuccessful();
        $content = $this->kernelBrowserClient->getResponse()->getContent();
        self::assertJson($content);
        $jsonResponse = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        self::assertArrayHasKey('totalItems', $jsonResponse);
        self::assertEquals(0, $jsonResponse['totalItems']);
        $this->kernelBrowserClient->request(Request::METHOD_GET, self::buildUrl('?page=1&user='.$user->getId()));
        self::assertResponseIsSuccessful();
        $content = $this->kernelBrowserClient->getResponse()->getContent();
        self::assertJson($content);
        $jsonResponse = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        self::assertArrayHasKey('totalItems', $jsonResponse);
        self::assertEquals(1, $jsonResponse['totalItems']);
    }

    public function testPost(): void
    {
        $user = $this->em->getRepository(User::class)->find(1);
        $totalTasksAmount = $this->em->getRepository(Task::class)->getTotalTasksAmount();
        $taskTitle = 'Task Test API 1';
        self::assertEquals(100, $totalTasksAmount);
        $this->kernelBrowserClient->jsonRequest(
            Request::METHOD_POST,
            self::BASE_URL,
            [
                'user' => sprintf('%s/%s', UserApiTest::BASE_URL, $user->getId()),
                'title' => $taskTitle,
                'description' => 'Task Test API 1',
                'status' => TaskStatusEnum::PENDING->value,
                'date' => (new \DateTimeImmutable())->format(AbstractBase::DATABASE_DATE_STRING_FORMAT),
            ],
        );
        self::assertResponseIsSuccessful();
        $content = $this->kernelBrowserClient->getResponse()->getContent();
        self::assertJson($content);
        $jsonResponse = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        self::assertArrayHasKey('id', $jsonResponse);
        self::assertArrayHasKey('user', $jsonResponse);
        self::assertArrayHasKey('title', $jsonResponse);
        self::assertArrayHasKey('description', $jsonResponse);
        self::assertArrayHasKey('status', $jsonResponse);
        self::assertArrayHasKey('date', $jsonResponse);
        self::assertEquals($taskTitle, $jsonResponse['title']);
        self::assertEquals($totalTasksAmount + 1, $jsonResponse['id']);
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

    public function testPatch(): void
    {
        $task = $this->em->getRepository(Task::class)->find(1);
        $newStatusTask = $task->getStatus() === TaskStatusEnum::PENDING->value ? TaskStatusEnum::COMPLETED->value : TaskStatusEnum::PENDING->value;
        $totalTasksAmount = $this->em->getRepository(Task::class)->getTotalTasksAmount();
        $this->kernelBrowserClient->jsonRequest(
            Request::METHOD_PATCH,
            self::buildUrl(sprintf('/%s', $task->getId())),
            [
                'user' => sprintf('%s/%s', UserApiTest::BASE_URL, $task->getUser()->getId()),
                'title' => $task->getTitle(),
                'description' => $task->getDescription(),
                'status' => $newStatusTask,
                'date' => $task->getDate()->format(AbstractBase::DATABASE_DATE_STRING_FORMAT),
            ],
        );
        self::assertResponseIsSuccessful();
        $content = $this->kernelBrowserClient->getResponse()->getContent();
        self::assertJson($content);
        $jsonResponse = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        self::assertArrayHasKey('id', $jsonResponse);
        self::assertArrayHasKey('user', $jsonResponse);
        self::assertArrayHasKey('title', $jsonResponse);
        self::assertArrayHasKey('description', $jsonResponse);
        self::assertArrayHasKey('status', $jsonResponse);
        self::assertArrayHasKey('date', $jsonResponse);
        self::assertEquals($newStatusTask, $jsonResponse['status']);
        self::assertEquals($totalTasksAmount, $this->em->getRepository(Task::class)->getTotalTasksAmount());
    }

    public function testBadPatch(): void
    {
        $totalTasksAmount = $this->em->getRepository(Task::class)->getTotalTasksAmount();
        $this->kernelBrowserClient->jsonRequest(
            Request::METHOD_PATCH,
            self::buildUrl(sprintf('/%s', $totalTasksAmount + 100)),
            [
                'bad_key' => 'Bad Task Test API 1',
            ],
        );
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testDelete(): void
    {
        $totalTasksAmount = $this->em->getRepository(Task::class)->getTotalTasksAmount();
        $task = $this->em->getRepository(Task::class)->find($totalTasksAmount);
        $this->kernelBrowserClient->jsonRequest(
            Request::METHOD_DELETE,
            self::buildUrl(sprintf('/%s', $task->getId())),
        );
        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
        self::assertEquals($totalTasksAmount - 1, $this->em->getRepository(Task::class)->getTotalTasksAmount());
    }

    public function testBadDelete(): void
    {
        $totalTasksAmount = $this->em->getRepository(Task::class)->getTotalTasksAmount();
        $this->kernelBrowserClient->jsonRequest(
            Request::METHOD_DELETE,
            self::buildUrl(sprintf('/%s', $totalTasksAmount + 100)),
        );
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        self::assertEquals($totalTasksAmount, $this->em->getRepository(Task::class)->getTotalTasksAmount());
    }
}
