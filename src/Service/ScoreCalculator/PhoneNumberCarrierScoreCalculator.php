<?php

namespace App\Service\ScoreCalculator;

use App\Entity\Client;
use App\Service\ScoreCalculator\Config\PhoneNumberCarrierScoreConfig;

class PhoneNumberCarrierScoreCalculator implements ScoreCalculatorInterface
{
    private PhoneNumberCarrierScoreConfig $config;

    public function __construct(array $phoneNumberCarrierScoreConfig)
    {
        $this->config = PhoneNumberCarrierScoreConfig::fromArray($phoneNumberCarrierScoreConfig);
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
        foreach ($this->config->getCarrierScores() as $carrierScore) {
            if (in_array($carrierCode, $carrierScore->getCodes())) {
                return $carrierScore->getScore();
            }
        }

        return $this->config->getDefaultScore();
    }
}
