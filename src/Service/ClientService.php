<?php

namespace App\Service;

use App\DTO\ClientUpdateRequest;
use App\Entity\Client;
use App\Repository\ClientRepository;

class ClientService
{
    public function __construct(
        private readonly ClientRepository $clientRepository,
        private readonly ClientScoreService $clientScoreService,
    ) {
    }

    public function create(ClientUpdateRequest $request): int
    {
        $client = new Client();
        $this->mapRequestDataToClient($request, $client);

        $score = $this->clientScoreService->calculateScore($client);
        $client->setScore($score);

        $this->clientRepository->saveAndFlush($client);

        return $client->getId();
    }

    public function update(int $id, ClientUpdateRequest $request): void
    {
        $client = $this->clientRepository->findById($id);
        $this->mapRequestDataToClient($request, $client);
        $this->clientRepository->saveAndFlush($client);
    }

    private function mapRequestDataToClient(ClientUpdateRequest $request, Client $client): void
    {
        $client
            ->setFirstName($request->getFirstName())
            ->setLastName($request->getLastName())
            ->setPhoneNumber($request->getPhoneNumber())
            ->setEmail($request->getEmail())
            ->setEducation($request->getEducation())
            ->setConsentProcessingPersonalData($request->hasConsentProcessingPersonalData())
        ;
    }
}
