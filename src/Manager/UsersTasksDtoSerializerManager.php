<?php

namespace App\Manager;

use App\Model\Dto\UserTasksListDto;
use Symfony\Component\Serializer\SerializerInterface;

final readonly class UsersTasksDtoSerializerManager
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function getDeserializedResults(array $data): array
    {
        $results = [];
        foreach ($data as $value) {
            $results[] = $this->serializer->denormalize($value, UserTasksListDto::class);
        }

        return $results;
    }
}
