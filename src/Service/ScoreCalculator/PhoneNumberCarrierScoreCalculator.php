<?php

namespace App\Service\ScoreCalculator;

use App\Entity\Client;
use App\Service\ScoreCalculator\Config\PhoneNumberCarrierScoreConfig;

class PhoneNumberCarrierScoreCalculator implements ScoreCalculatorInterface
{
    private ?PhoneNumberCarrierScoreConfig $config = null;

    public function __construct(private readonly array $phoneNumberCarrierScoreConfig)
    {
    }

    public function calculate(Client $client): int
    {
        $phoneNumber = $client->getPhoneNumber();
        $carrierCode = $this->getPhoneNumberCarrierCode($phoneNumber);
        return $this->getScoreByCarrierCode($carrierCode);
    }

    private function getPhoneNumberCarrierCode(string $phoneNumber): string
    {
        return substr($phoneNumber, 2, 3);
    }

    private function getScoreByCarrierCode(string $carrierCode): int
    {
        $config = $this->getConfig();

        foreach ($config->getCarrierScores() as $carrierScore) {
            if (in_array($carrierCode, $carrierScore->getCodes())) {
                return $carrierScore->getScore();
            }
        }

        return $config->getDefaultScore();
    }

    private function getConfig(): PhoneNumberCarrierScoreConfig
    {
        if (!$this->config) {
            $config = PhoneNumberCarrierScoreConfig::fromArray($this->phoneNumberCarrierScoreConfig);
            $this->config = $config;
        }

        return $this->config;
    }
}
