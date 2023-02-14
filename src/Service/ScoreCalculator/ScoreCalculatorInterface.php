<?php

namespace App\Service\ScoreCalculator;

use App\Entity\Client;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(name: 'app.score_calculator')]
interface ScoreCalculatorInterface
{
    public static function getName(): string;

    public function calculate(Client $client): int;
}
