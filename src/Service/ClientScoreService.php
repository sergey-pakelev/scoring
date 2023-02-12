<?php

namespace App\Service;

use App\Entity\Client;
use App\Service\ScoreCalculator\ScoreCalculatorInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class ClientScoreService
{
    /**
     * @param iterable<ScoreCalculatorInterface> $calculators
     */
    public function __construct(
        #[TaggedIterator(tag: 'app.score_calculator')] private readonly iterable $calculators,
    ) {
    }

    public function calculateScore(Client $client): int
    {
        $score = 0;

        foreach ($this->calculators as $calculator) {
            $score += $calculator->calculate($client);
        }

        return $score;
    }
}
