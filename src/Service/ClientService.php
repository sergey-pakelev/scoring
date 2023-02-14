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
        $client = (new Client())
            ->setFirstName($request->getFirstName())
            ->setLastName($request->getLastName())
            ->setPhoneNumber($request->getPhoneNumber())
            ->setEmail($request->getEmail())
            ->setEducation($request->getEducation())
            ->setConsentProcessingPersonalData($request->hasConsentProcessingPersonalData())
        ;

        $score = $this->clientScoreService->calculateScore($client);
        $client->setScore($score);

        $this->clientRepository->save($client);

        return $client->getId();
    }
}
