<?php

namespace App\Command;

use App\Entity\Client;
use App\Exception\ClientNotFoundException;
use App\Repository\ClientRepository;
use App\Service\ScoreCalculator\ScoreCalculatorInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

#[AsCommand(name: 'app:client-score')]
class ClientScoreCommand extends Command
{
    private SymfonyStyle $io;

    private ?int $clientId = null;

    /**
     * @param ClientRepository $clientRepository
     * @param iterable<ScoreCalculatorInterface> $calculators
     */
    public function __construct(
        private readonly ClientRepository $clientRepository,
        #[TaggedIterator(tag: 'app.score_calculator')] private readonly iterable $calculators,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
            name: 'id',
            mode: InputOption::VALUE_REQUIRED,
            description: 'Client\'s id',
        );
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $id = $input->getOption('id');

        try {
            $this->clientId = $id;
        } catch (\Throwable $e) {
            throw new \RuntimeException("$id - is invalid value for option id");
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);

        if (null !== $this->clientId) {
            $this->forSingleClient();
        } else {
            $this->forAllClients();
        }

        return Command::SUCCESS;
    }

    private function forSingleClient(): void
    {
        try {
            $client = $this->clientRepository->findById($this->clientId);
        } catch (ClientNotFoundException) {
            throw new \RuntimeException("Client with id = $this->clientId is not exist");
        }

        $this->printClientScores($client);
    }

    private function forAllClients(): void
    {
        /** @var Client $client */
        foreach ($this->clientRepository->getAllClientsGenerator() as $client) {
            $this->printClientScores($client);
        }
    }

    private function printClientScores(Client $client): void
    {
        $this->io->section("Client {$client->getId()}");
        $this->io->writeln("Current client score is {$client->getScore()}");

        $totalScore = 0;
        $rows = [];

        foreach ($this->calculators as $calculator) {
            $score = $calculator->calculate($client);
            $rows[] = [$calculator::getName(), $score];
            $totalScore += $score;
        }

        $rows[] = ['Total', $totalScore];

        $this->io->table(['Name', 'Value'], $rows);
    }
}
