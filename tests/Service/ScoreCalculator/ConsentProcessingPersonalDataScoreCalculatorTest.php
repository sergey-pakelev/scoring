<?php

namespace App\Tests\Service\ScoreCalculator;

use App\Entity\Client;
use App\Service\ScoreCalculator\ConsentProcessingPersonalDataScoreCalculator;
use PHPUnit\Framework\TestCase;

class ConsentProcessingPersonalDataScoreCalculatorTest extends TestCase
{
    public function calculateProvider(): array
    {
        return [
            [(new Client())->setConsentProcessingPersonalData(true), 4],
            [(new Client())->setConsentProcessingPersonalData(false), 0],
        ];
    }

    /**
     * @dataProvider calculateProvider
     */
    public function testCalculate(Client $client, int $expectedScore)
    {
        $calculator = new ConsentProcessingPersonalDataScoreCalculator();

        $score = $calculator->calculate($client);

        $this->assertEquals($score, $expectedScore);
    }
}
