<?php

namespace App\Tests\Service\ScoreCalculator;

use App\Entity\Client;
use App\Enum\EducationEnum;
use App\Exception\EducationScoreNotDefinedException;
use App\Exception\InvalidEducationScoreConfigException;
use App\Service\ScoreCalculator\EducationScoreCalculator;
use PHPUnit\Framework\TestCase;

class EducationScoreCalculatorTest extends TestCase
{
    public function testInvalidConfigThrowException(): void
    {
        $config = [];

        $calculator = new EducationScoreCalculator($config);
        $client = (new Client())->setEducation(EducationEnum::HIGHER);

        $this->expectException(InvalidEducationScoreConfigException::class);
        $calculator->calculate($client);
    }

    public function testNotDefinedEducationScoreThrowException(): void
    {
        $config = [
            'education_scores' => [],
        ];

        $calculator = new EducationScoreCalculator($config);
        $client = (new Client())->setEducation(EducationEnum::HIGHER);

        $this->expectException(EducationScoreNotDefinedException::class);
        $calculator->calculate($client);
    }

    public function testCalculate(): void
    {
        $expectedScore = 10;

        $config = [
            'education_scores' => [
                [
                    'education' => 'HIGHER',
                    'score' => $expectedScore,
                ],
            ],
        ];

        $calculator = new EducationScoreCalculator($config);
        $client = (new Client())->setEducation(EducationEnum::HIGHER);

        $score = $calculator->calculate($client);
        $this->assertEquals($expectedScore, $score);
    }
}
