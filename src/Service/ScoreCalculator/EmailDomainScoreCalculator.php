<?php

namespace App\Service\ScoreCalculator;

use App\Entity\Client;
use App\Service\ScoreCalculator\Config\EmailDomainScoreConfig;

class EmailDomainScoreCalculator implements ScoreCalculatorInterface
{
    private ?EmailDomainScoreConfig $config = null;

    public function __construct(private readonly array $emailDomainScoreConfig)
    {
    }

    public function calculate(Client $client): int
    {
        $email = $client->getEmail();
        $domain = $this->getEmailDomain($email);

        return $this->getScoreByDomain($domain);
    }

    private function getEmailDomain(string $email): string
    {
        $parts = explode('@', $email);
        $domain = explode('.', $parts[1]);

        return $domain[0];
    }

    private function getScoreByDomain(string $domain): int
    {
        $config = $this->getConfig();

        foreach ($config->getDomainScores() as $domainScore) {
            if (strtolower($domainScore->getName()) === strtolower($domain)) {
                return $domainScore->getScore();
            }
        }

        return $config->getDefaultScore();
    }

    private function getConfig(): EmailDomainScoreConfig
    {
        if (!$this->config) {
            $config = EmailDomainScoreConfig::fromArray($this->emailDomainScoreConfig);
            $this->config = $config;
        }

        return $this->config;
    }
}
