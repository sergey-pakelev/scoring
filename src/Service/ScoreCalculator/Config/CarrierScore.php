<?php

namespace App\Service\ScoreCalculator\Config;

class CarrierScore
{
    public function __construct(
        private readonly string $name,
        private readonly string $score,
        private readonly array $codes
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getScore(): string
    {
        return $this->score;
    }

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return $this->codes;
    }
}
