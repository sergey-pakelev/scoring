<?php

namespace App\Mapper;

use App\DTO\ClientDTO;
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
}
