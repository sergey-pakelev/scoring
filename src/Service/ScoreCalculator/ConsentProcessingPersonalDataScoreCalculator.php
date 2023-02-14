<?php

namespace App\Service\ScoreCalculator;

use App\Entity\Client;
use App\Service\ScoreCalculator\Config\ConsentProcessingPersonalDataScoreConfig;

class ConsentProcessingPersonalDataScoreCalculator implements ScoreCalculatorInterface
{
    private ?ConsentProcessingPersonalDataScoreConfig $config = null;

    public function __construct(private readonly array $consentProcessingPersonalDataScoreConfig)
    {
    }

    public static function getName(): string
    {
        return 'Consent processing personal data score';
    }

    public function calculate(Client $client): int
    {
        $config = $this->getConfig();

        return $client->hasConsentProcessingPersonalData()
            ? $config->getScoreWithConsent()
            : $config->getScoreWithoutConsent()
        ;
    }

    private function getConfig(): ConsentProcessingPersonalDataScoreConfig
    {
        if (!$this->config) {
            $config = ConsentProcessingPersonalDataScoreConfig::fromArray($this->consentProcessingPersonalDataScoreConfig);
            $this->config = $config;
        }

        return $this->config;
    }
}
