<?php

namespace App\Service\ScoreCalculator;

use App\Entity\Client;

interface ScoreCalculatorInterface
{
    public function calculate(Client $client): int;
}
