<?php

namespace App\Doctrine\Filter;

use App\Doctrine\Attributes\UserAware;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class UserFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        try {
            $userId = $this->getParameter('id');
        } catch (\InvalidArgumentException) {
            return '';
        }

        if ($targetEntity->getReflectionClass()) {
            $attributes = $targetEntity->getReflectionClass()->getAttributes(UserAware::class, \ReflectionAttribute::IS_INSTANCEOF);
            foreach ($attributes as $attribute) {
                if (array_key_exists('userFieldName', $attribute->getArguments())) {
                    $fieldName = $attribute->getArguments()['userFieldName'];
                    break;
                }
            }
        }

        if (empty($fieldName)) {
            return '';
        }

        return sprintf('%s.%s = %s', $targetTableAlias, $fieldName, $userId);
    }
}
