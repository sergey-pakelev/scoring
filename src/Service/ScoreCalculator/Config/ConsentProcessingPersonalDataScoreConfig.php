<?php

namespace App\Service\ScoreCalculator\Config;

use App\Exception\InvalidConsentProcessingPersonalDataScoreConfigException;

class ConsentProcessingPersonalDataScoreConfig
{
    private int $scoreWithConsent;

    private int $scoreWithoutConsent;

    public static function fromArray(array $consentProcessingPersonalDataScoreConfig): self
    {
        $config = new self();

        try {
            $config->setScoreWithConsent($consentProcessingPersonalDataScoreConfig['score_with_consent']);
            $config->setScoreWithoutConsent($consentProcessingPersonalDataScoreConfig['score_without_consent']);
        } catch (\Throwable $e) {
            throw new InvalidConsentProcessingPersonalDataScoreConfigException(previous: $e);
        }

        return $config;
    }

    public function getScoreWithConsent(): int
    {
        return $this->scoreWithConsent;
    }

    public function setScoreWithConsent(int $scoreWithConsent): self
    {
        $this->scoreWithConsent = $scoreWithConsent;
        return $this;
    }

    public function getScoreWithoutConsent(): int
    {
        return $this->scoreWithoutConsent;
    }

    public function setScoreWithoutConsent(int $scoreWithoutConsent): self
    {
        $this->scoreWithoutConsent = $scoreWithoutConsent;
        return $this;
    }
}
