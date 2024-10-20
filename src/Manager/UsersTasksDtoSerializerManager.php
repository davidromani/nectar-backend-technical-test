<?php

namespace App\Manager;

use App\Model\Dto\UserTasksListDto;
use App\Model\Dto\UserWithoutCompletedTasksListDto;
use Symfony\Component\Serializer\SerializerInterface;

final readonly class UsersTasksDtoSerializerManager
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function getUserTasksListDeserializedResults(array $data): array
    {
        $results = [];
        foreach ($data as $value) {
            $results[] = $this->serializer->denormalize($value, UserTasksListDto::class);
        }

        return $results;
    }

    public function getUserWithoutCompletedTasksListDeserializedResults(array $data): array
    {
        $results = [];
        foreach ($data as $value) {
            $results[] = $this->serializer->denormalize($value, UserWithoutCompletedTasksListDto::class);
        }

        return $results;
    }
}
