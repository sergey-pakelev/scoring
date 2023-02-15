<?php

namespace App\DTO;

class ExceptionConfig
{
    public function __construct(
        private readonly int $code,
        private readonly bool $hidden
    ) {
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function isHidden(): bool
    {
        return $this->hidden;
    }
}
