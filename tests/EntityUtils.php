<?php

namespace App\Tests;

trait EntityUtils
{
    /**
     * @throws \ReflectionException
     */
    public function setEntityId(object $entity, int $value, $idField = 'id'): void
    {
        $class = new \ReflectionClass($entity);
        $property = $class->getProperty($idField);
        $property->setAccessible(true);
        $property->setValue($entity, $value);
        $property->setAccessible(false);
    }
}
