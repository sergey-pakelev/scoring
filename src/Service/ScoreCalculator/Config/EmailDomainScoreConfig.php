<?php

namespace App\Service\ScoreCalculator\Config;

use App\Exception\InvalidEmailDomainScoreConfigException;

class EmailDomainScoreConfig
{
    private int $defaultScore;

    /**
     * @var DomainScore[]
     */
    private array $domainScores;

    public static function fromArray(array $emailDomainScoreConfig): self
    {
        $config = new self();

        try {
            $config->setDefaultScore($emailDomainScoreConfig['default_score']);

            foreach ($emailDomainScoreConfig['domain_scores'] as $domainScore) {
                $domainScore = new DomainScore($domainScore['name'], $domainScore['score']);
                $config->addDomainScore($domainScore);
            }
        } catch (\Throwable $e) {
            throw new InvalidEmailDomainScoreConfigException(previous: $e);
        }

        return $config;
    }

    public function setDefaultScore(int $defaultScore): self
    {
        $this->defaultScore = $defaultScore;
        return $this;
    }

    public function getDefaultScore(): int
    {
        return $this->defaultScore;
    }

    public function getDomainScores(): array
    {
        return $this->domainScores;
    }

    public function addDomainScore(DomainScore $domainScore): self
    {
        $this->domainScores[] = $domainScore;
        return $this;
    }
}
