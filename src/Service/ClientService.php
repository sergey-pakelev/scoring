<?php

namespace App\Service;

use App\DTO\ClientEditPayload;
use App\DTO\ClientsPage;
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

    public function getClientEditPayloadById(int $id): ClientEditPayload
    {
        $client = $this->clientRepository->findById($id);

        return ClientMapper::entityToPayload($client);
    }

    public function create(ClientEditPayload $payload): int
    {
        $client = new Client();
        ClientMapper::payloadToEntity($payload, $client);

        $score = $this->clientScoreService->calculateScore($client);
        $client->setScore($score);

        $this->clientRepository->saveAndFlush($client);

        return $client->getId();
    }

    public function update(int $id, ClientEditPayload $payload): void
    {
        $client = $this->clientRepository->findById($id);
        ClientMapper::payloadToEntity($payload, $client);
        $this->clientRepository->saveAndFlush($client);
    }
}
