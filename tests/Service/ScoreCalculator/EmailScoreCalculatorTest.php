<?php

namespace App\Tests\Service\ScoreCalculator;

use App\Entity\Client;
use App\Exception\InvalidEmailDomainScoreConfigException;
use App\Service\ScoreCalculator\EmailDomainScoreCalculator;
use PHPUnit\Framework\TestCase;

class EmailScoreCalculatorTest extends TestCase
{
    public function testInvalidConfigThrowException(): void
    {
        $invalidConfig = ['test' => 'test'];

        $calculator = new EmailDomainScoreCalculator($invalidConfig);
        $client = (new Client())->setEmail('test@test.com');

        $this->expectException(InvalidEmailDomainScoreConfigException::class);
        $calculator->calculate($client);
    }

    public function calculateProvider(): array
    {
        return [
            [(new Client())->setEmail('test@gmail.com'), 10],
            [(new Client())->setEmail('test@other.com'), 3],
        ];
    }

    /**
     * @dataProvider calculateProvider
     */
    public function testCalculate(Client $client, int $expectedScore): void
    {
        $config = [
            'default_score' => 3,
            'domain_scores' => [
                [
                    'name' => 'gmail',
                    'score' => 10,
                ],
            ],
        ];

        $calculator = new EmailDomainScoreCalculator($config);

        $score = $calculator->calculate($client);

        $this->assertEquals($expectedScore, $score);
    }
}
