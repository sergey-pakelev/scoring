<?php

namespace App\Tests\Service\ScoreCalculator;

use App\Entity\Client;
use App\Service\ScoreCalculator\EmailScoreCalculator;
use PHPUnit\Framework\TestCase;

class EmailScoreCalculatorTest extends TestCase
{
    public function calculateProvider(): array
    {
        return [
            [(new Client())->setEmail('test@gmail.com'), 10],
            [(new Client())->setEmail('test@yandex.ru'), 8],
            [(new Client())->setEmail('test@mail.ru'), 6],
            [(new Client())->setEmail('test@other.com'), 3],
        ];
    }

    /**
     * @dataProvider calculateProvider
     */
    public function testCalculate(Client $client, int $expectedScore): void
    {
        $emailScoreCalculator = new EmailScoreCalculator();

        $score = $emailScoreCalculator->calculate($client);

        $this->assertEquals($expectedScore, $score);
    }
}
