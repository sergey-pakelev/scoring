<?php

namespace App\Service\ScoreCalculator;

use App\Entity\Client;

class ConsentProcessingPersonalDataScoreCalculator implements ScoreCalculatorInterface
{
    private const HAS_CONSENT_SCORE = 4;
    private const HAS_NOT_CONSENT_SCORE = 0;

    public function calculate(Client $client): int
    {
        return $client->hasConsentProcessingPersonalData() ? self::HAS_CONSENT_SCORE : self::HAS_NOT_CONSENT_SCORE;
    }
}
