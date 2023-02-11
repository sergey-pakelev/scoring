<?php

namespace App\Tests\Service\ScoreCalculator;

use App\Entity\Client;
use App\Exception\InvalidPhoneNumberCarrierScoreConfig;
use App\Service\ScoreCalculator\PhoneNumberCarrierScoreCalculator;
use PHPUnit\Framework\TestCase;

class PhoneNumberCarrierScoreCalculatorTest extends TestCase
{
    public function testInvalidConfigThrowException(): void
    {
        $config = [];

        $calculator = new PhoneNumberCarrierScoreCalculator($config);
        $client = (new Client())->setPhoneNumber('+71112324452');

        $this->expectException(InvalidPhoneNumberCarrierScoreConfig::class);
        $calculator->calculate($client);
    }

    public function calculateDataProvider(): array
    {
        return [
            [(new Client())->setPhoneNumber('+71113332244'), 10],
            [(new Client())->setPhoneNumber('+70003332244'), 1],
        ];
    }

    /**
     * @dataProvider calculateDataProvider
     */
    public function testCalculate(Client $client, int $expectedScore): void
    {
        $config = [
            'default_score' => 1,
            'carrier_scores' => [
                [
                    'name' => 'Test',
                    'score' => 10,
                    'codes' => [111],
                ],
            ],
        ];

        $calculator = new PhoneNumberCarrierScoreCalculator($config);

        $score = $calculator->calculate($client);

        $this->assertEquals($expectedScore, $score);
    }
}
