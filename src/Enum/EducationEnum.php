<?php

namespace App\Enum;

enum EducationEnum: string
{
    case HIGHER = 'Higher';
    case SPECIAL = 'Special';
    case SECONDARY = 'Secondary';

    public static function fromName(string $name): self
    {
        foreach (self::cases() as $case) {
            if ($name === $case->name) {
                return $case;
            }
        }
        throw new \ValueError("$name is not a valid name for enum ".self::class);
    }
}
