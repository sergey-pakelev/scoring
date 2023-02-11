<?php

namespace App\Service\ScoreCalculator\Config;

use App\Enum\EducationEnum;

class EducationScore
{
    public function __construct(
        private readonly EducationEnum $education,
        private readonly int $score,
    ) {
    }

    public function getEducation(): EducationEnum
    {
        return $this->education;
    }

    public function getScore(): int
    {
        return $this->score;
    }
}
