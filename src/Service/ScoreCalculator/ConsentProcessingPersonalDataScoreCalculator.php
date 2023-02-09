<?php

namespace App\Service\ScoreCalculator;

use App\Entity\Client;
use App\Service\ScoreCalculator\Config\ConsentProcessingPersonalDataScoreConfig;

class ConsentProcessingPersonalDataScoreCalculator implements ScoreCalculatorInterface
{
    private ConsentProcessingPersonalDataScoreConfig $config;

    public function __construct(array $consentProcessingPersonalDataScoreConfig)
    {
        $this->config = ConsentProcessingPersonalDataScoreConfig::fromArray($consentProcessingPersonalDataScoreConfig);
    }

    public function calculate(Client $client): int
    {
        return $client->hasConsentProcessingPersonalData()
            ? $this->config->getScoreWithConsent()
            : $this->config->getScoreWithoutConsent()
        ;
    }
}
