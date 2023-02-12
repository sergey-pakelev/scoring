<?php

namespace App\Service\ScoreCalculator;

use App\Entity\Client;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(name: 'app.score_calculator')]
interface ScoreCalculatorInterface
{
    public function calculate(Client $client): int;
}
