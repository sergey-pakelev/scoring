<?php

namespace App\Service\ScoreCalculator\Config;

class DomainScore
{
    public function __construct(private readonly string $name, private readonly int $score)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getScore(): int
    {
        return $this->score;
    }
}
