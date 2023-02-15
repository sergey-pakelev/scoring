<?php

namespace App\Tests\Service;

use App\Entity\Client;
use App\Service\ClientScoreService;
use App\Service\ScoreCalculator\ScoreCalculatorInterface;
use PHPUnit\Framework\TestCase;

class ClientScoreServiceTest extends TestCase
{
    public function testCalculateScore()
    {
        $calculatorScores = [3, 8];
        $calculators = [];

        foreach ($calculatorScores as $calculatorScore) {
            $calculators[] = new class($calculatorScore) implements ScoreCalculatorInterface {
                public function __construct(private readonly int $calculatorScore)
                {
                }

                public function calculate(Client $client): int
                {
                    return $this->calculatorScore;
                }

                public static function getName(): string
                {
                    return 'test';
                }
            };
        }

        $clientScoreCalculator = new ClientScoreService($calculators);
        $totalScore = $clientScoreCalculator->calculateScore(new Client());

        $this->assertEquals(array_sum($calculatorScores), $totalScore);
    }

    public function testCalculateScoreDefaultScoreIsZero(): void
    {
        $calculators = [];

        $clientScoreCalculator = new ClientScoreService($calculators);
        $totalScore = $clientScoreCalculator->calculateScore(new Client());

        $this->assertEquals(0, $totalScore);
    }
}
