<?php

namespace App\Service\ScoreCalculator;

use App\Entity\Client;
use App\Service\ScoreCalculator\Config\EmailDomainScoreConfig;

class EmailDomainScoreCalculator implements ScoreCalculatorInterface
{
    private EmailDomainScoreConfig $emailDomainScoreConfig;

    public function __construct(array $emailDomainScoreConfig)
    {
        $this->emailDomainScoreConfig = EmailDomainScoreConfig::fromArray($emailDomainScoreConfig);
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
        foreach ($this->emailDomainScoreConfig->getDomainScores() as $domainScore) {
            if (strtolower($domainScore->getName()) === strtolower($domain)) {
                return $domainScore->getScore();
            }
        }

        return $this->emailDomainScoreConfig->getDefaultScore();
    }
}
