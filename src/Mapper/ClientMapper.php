<?php

namespace App\Mapper;

use App\DTO\ClientDTO;
use App\DTO\ClientEditPayload;
use App\Entity\Client;

class ClientMapper
{
    public static function entityToDTO(Client $client): ClientDTO
    {
        return (new ClientDTO())
            ->setId($client->getId())
            ->setFirstName($client->getFirstName())
            ->setLastName($client->getLastName())
            ->setScore($client->getScore())
        ;
    }

    public static function payloadToEntity(ClientEditPayload $payload, ?Client $client = null): Client
    {
        $client = $client ?? (new Client());

        return $client
            ->setFirstName($payload->getFirstName())
            ->setLastName($payload->getLastName())
            ->setPhoneNumber($payload->getPhoneNumber())
            ->setEmail($payload->getEmail())
            ->setEducation($payload->getEducation())
            ->setConsentProcessingPersonalData($payload->hasConsentProcessingPersonalData())
        ;
    }

    public static function entityToPayload(Client $client): ClientEditPayload
    {
        return (new ClientEditPayload())
            ->setFirstName($client->getFirstName())
            ->setLastName($client->getLastName())
            ->setPhoneNumber($client->getPhoneNumber())
            ->setEmail($client->getEmail())
            ->setEducation($client->getEducation())
            ->setConsentProcessingPersonalData($client->hasConsentProcessingPersonalData())
        ;
    }
}
