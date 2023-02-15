<?php

namespace App\Service;

use App\DTO\ClientsPage;
use App\DTO\ClientEditPayload;
use App\Entity\Client;
use App\Mapper\ClientMapper;
use App\Repository\ClientRepository;

class ClientService
{
    private const CLIENTS_PER_PAGE = 10;

    public function __construct(
        private readonly ClientRepository $clientRepository,
        private readonly ClientScoreService $clientScoreService,
    ) {
    }

    public function getClientsPage(int $page): ClientsPage
    {
        $totalClients = $this->clientRepository->count([]);
        $pages = ceil($totalClients / self::CLIENTS_PER_PAGE);

        $offset = max($page - 1, 0) * self::CLIENTS_PER_PAGE;
        $clients = $this->clientRepository->findPage($offset, self::CLIENTS_PER_PAGE);

        $clientsDTOs = [];

        foreach ($clients as $client) {
            $clientsDTOs[] = ClientMapper::entityToDTO($client);
        }

        return (new ClientsPage())
            ->setClients($clientsDTOs)
            ->setPage($page)
            ->setPages($pages)
        ;
    }

    public function create(ClientEditPayload $payload): int
    {
        $client = new Client();
        $this->mapPayloadToClient($payload, $client);

        $score = $this->clientScoreService->calculateScore($client);
        $client->setScore($score);

        $this->clientRepository->saveAndFlush($client);

        return $client->getId();
    }

    public function update(int $id, ClientEditPayload $payload): void
    {
        $client = $this->clientRepository->findById($id);
        $this->mapPayloadToClient($payload, $client);
        $this->clientRepository->saveAndFlush($client);
    }

    private function mapPayloadToClient(ClientEditPayload $payload, Client $client): void
    {
        $client
            ->setFirstName($payload->getFirstName())
            ->setLastName($payload->getLastName())
            ->setPhoneNumber($payload->getPhoneNumber())
            ->setEmail($payload->getEmail())
            ->setEducation($payload->getEducation())
            ->setConsentProcessingPersonalData($payload->hasConsentProcessingPersonalData())
        ;
    }
}
