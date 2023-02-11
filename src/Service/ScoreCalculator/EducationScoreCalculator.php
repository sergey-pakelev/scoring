<?php

namespace App\Service\ScoreCalculator;

use App\Entity\Client;
use App\Enum\EducationEnum;
use App\Exception\EducationScoreNotDefinedException;
use App\Service\ScoreCalculator\Config\EducationScoreConfig;

class EducationScoreCalculator implements ScoreCalculatorInterface
{
    private ?EducationScoreConfig $config = null;

    public function __construct(private readonly array $educationScoreConfig)
    {
    }

    public function calculate(Client $client): int
    {
        $education = $client->getEducation();
        return $this->getScoreByEducation($education);
    }

    private function getScoreByEducation(EducationEnum $education): int
    {
        foreach ($this->getConfig()->getEducationScores() as $educationScore) {
            if ($education === $educationScore->getEducation()) {
                return $educationScore->getScore();
            }
        }

        throw new EducationScoreNotDefinedException("Score for education $education->name is not defined");
    }

    private function getConfig(): EducationScoreConfig
    {
        if (!$this->config) {
            $config = EducationScoreConfig::fromArray($this->educationScoreConfig);
            $this->config = $config;
        }

        return $this->config;
    }
}
