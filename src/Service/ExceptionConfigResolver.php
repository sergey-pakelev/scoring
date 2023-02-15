<?php

namespace App\Service;

use App\DTO\ExceptionConfig;

class ExceptionConfigResolver
{
    /**
     * @var ExceptionConfig[]
     */
    private array $configs = [];

    public function __construct(array $configs)
    {
        foreach ($configs as $class => $mapping) {
            if (empty($mapping['code'])) {
                throw new \InvalidArgumentException('code is mandatory for class'.$class);
            }

            $this->addConfig(
                $class,
                $mapping['code'],
                $mapping['hidden'] ?? true,
            );
        }
    }

    public function resolve(string $throwableClass): ?ExceptionConfig
    {
        $foundMapping = null;

        foreach ($this->configs as $class => $config) {
            if ($throwableClass === $class || is_subclass_of($throwableClass, $class)) {
                $foundMapping = $config;
                break;
            }
        }

        return $foundMapping;
    }

    private function addConfig(string $class, int $code, bool $hidden): void
    {
        $this->configs[$class] = new ExceptionConfig($code, $hidden);
    }
}
