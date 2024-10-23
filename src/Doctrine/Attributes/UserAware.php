<?php

namespace App\Doctrine\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class UserAware
{
    public function __construct(public string $userFieldName = 'user_id') {}
}
