<?php

namespace App\Tests\Service\ScoreCalculator;

use App\Entity\Client;
use App\Exception\InvalidConsentProcessingPersonalDataScoreConfigException;
use App\Service\ScoreCalculator\ConsentProcessingPersonalDataScoreCalculator;
use PHPUnit\Framework\TestCase;

class ConsentProcessingPersonalDataScoreCalculatorTest extends TestCase
{
    public function testInvalidConfigThrowException(): void
    {
        $config = [];

        $calculator = new ConsentProcessingPersonalDataScoreCalculator($config);
        $client = (new Client())->setConsentProcessingPersonalData(true);

        $this->expectException(InvalidConsentProcessingPersonalDataScoreConfigException::class);
        $calculator->calculate($client);
    }

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
    public function testCalculate(Client $client, int $expectedScore): void
    {
        $config = [
            'score_with_consent' => 4,
            'score_without_consent' => 0,
        ];

        $calculator = new ConsentProcessingPersonalDataScoreCalculator($config);

        $score = $calculator->calculate($client);

        $this->assertEquals($expectedScore, $score);
    }
}
