<?php

namespace App\Service\ScoreCalculator\Config;

use App\Exception\InvalidPhoneNumberCarrierScoreConfig;

class PhoneNumberCarrierScoreConfig
{
    private int $defaultScore;

    /**
     * @var CarrierScore[]
     */
    private array $carrierScores;

    public static function fromArray(array $phoneNumberCarrierScoreConfig): self
    {
        $config = new self();

        try {
            $config->setDefaultScore($phoneNumberCarrierScoreConfig['default_score']);

            foreach ($phoneNumberCarrierScoreConfig['carrier_scores'] as $carrierScoreArray) {
                $carrierScore = new CarrierScore(
                    $carrierScoreArray['name'],
                    $carrierScoreArray['score'],
                    $carrierScoreArray['codes'],
                );

                $config->addCarrierScore($carrierScore);
            }
        } catch (\Throwable $e) {
            throw new InvalidPhoneNumberCarrierScoreConfig(previous: $e);
        }

        return $config;
    }

    public function getDefaultScore(): int
    {
        return $this->defaultScore;
    }

    public function setDefaultScore(int $defaultScore): self
    {
        $this->defaultScore = $defaultScore;

        return $this;
    }

    public function getCarrierScores(): array
    {
        return $this->carrierScores;
    }

    public function addCarrierScore(CarrierScore $carrierScore): self
    {
        $this->carrierScores[] = $carrierScore;

        return $this;
    }
}
