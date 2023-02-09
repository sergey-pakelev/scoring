<?php

namespace App\Service\ScoreCalculator;

use App\Entity\Client;

class EmailScoreCalculator implements ScoreCalculatorInterface
{
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
        return match (strtolower($domain)) {
            'gmail' => 10,
            'yandex' => 8,
            'mail' => 6,
            default => 3
        };
    }
}
