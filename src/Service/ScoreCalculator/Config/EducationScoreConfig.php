<?php

namespace App\Service\ScoreCalculator\Config;

use App\Enum\EducationEnum;
use App\Exception\InvalidEducationScoreConfigException;

class EducationScoreConfig
{
    /**
     * @var EducationScore[]
     */
    private array $educationScores = [];

    public static function fromArray(array $educationScoreConfig): self
    {
        $config = new self();

        try {
            foreach ($educationScoreConfig['education_scores'] as $educationScoreArray) {
                $education = EducationEnum::fromName($educationScoreArray['education']);
                $score = $educationScoreArray['score'];

                $config->addEducationScore(new EducationScore($education, $score));
            }
        } catch (\Throwable $e) {
            throw new InvalidEducationScoreConfigException(previous: $e);
        }

        return $config;
    }

    public function getEducationScores(): array
    {
        return $this->educationScores;
    }

    public function addEducationScore(EducationScore $educationScore): self
    {
        $this->educationScores[] = $educationScore;
        return $this;
    }
}
