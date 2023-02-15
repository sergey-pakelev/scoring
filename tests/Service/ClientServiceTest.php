<?php

namespace App\Tests\Service;

use App\DTO\ClientDTO;
use App\DTO\ClientsPage;
use App\DTO\ClientEditPayload;
use App\Entity\Client;
use App\Enum\EducationEnum;
use App\Repository\ClientRepository;
use App\Service\ClientScoreService;
use App\Service\ClientService;
use App\Tests\EntityUtils;
use PHPUnit\Framework\TestCase;

class ClientServiceTest extends TestCase
{
    use EntityUtils;

    private ClientRepository $clientRepository;

    private ClientScoreService $clientScoreService;

    protected function setUp(): void
    {
        $this->clientRepository = $this->createMock(ClientRepository::class);
        $this->clientScoreService = $this->createMock(ClientScoreService::class);
    }

    public function testGetClientsPageInvalidPage(): void
    {
        $this->clientRepository->expects($this->once())
            ->method('count')
            ->with([])
            ->willReturn(0)
        ;

        $this->clientRepository->expects($this->once())
            ->method('findPage')
            ->with(0, 10)
            ->willReturn([])
        ;

        $expectedClientsPage = (new ClientsPage())
            ->setClients([])
            ->setPages(0)
            ->setPage(-1)
        ;

        $service = $this->createService();
        $this->assertEquals($expectedClientsPage, $service->getClientsPage(-1));
    }

    /**
     * @throws \ReflectionException
     */
    public function testGetClientsPage(): void
    {
        $this->clientRepository->expects($this->once())
            ->method('count')
            ->with([])
            ->willReturn(1)
        ;

        $client = $this->createClient();
        $clientScore = 20;
        $client->setScore($clientScore);

        $clientId = 11;
        $this->setEntityId($client, $clientId);

        $expectedOffset = 0;

        $this->clientRepository->expects($this->once())
            ->method('findPage')
            ->with($expectedOffset, 10)
            ->willReturn([$client])
        ;

        $expectedClientDTO = (new ClientDTO())
            ->setId($clientId)
            ->setFirstName($client->getFirstName())
            ->setLastName($client->getLastName())
            ->setScore($clientScore)
        ;

        $expectedClientsPage = (new ClientsPage())
            ->setClients([$expectedClientDTO])
            ->setPages(1)
            ->setPage(1)
        ;

        $service = $this->createService();
        $this->assertEquals($expectedClientsPage, $service->getClientsPage(1));
    }

    public function testCreate()
    {
        $payload = $this->createEditPayload();
        $expectedClient = $this->createClient();

        $expectedClientScore = 10;

        $this->clientScoreService->expects($this->once())
            ->method('calculateScore')
            ->with($expectedClient)
            ->willReturn($expectedClientScore)
        ;

        $expectedClientToBeSaved = (clone $expectedClient)->setScore($expectedClientScore);
        $expectedClientId = 11;

        $this->clientRepository->expects($this->once())
            ->method('saveAndFlush')
            ->with($expectedClientToBeSaved)
            ->will($this->returnCallback(function (Client $client) use ($expectedClientId) {
                $this->setEntityId($client, $expectedClientId);
            }))
        ;

        $this->assertEquals($expectedClientId, $this->createService()->create($payload));
    }

    /**
     * @throws \ReflectionException
     */
    public function testUpdate(): void
    {
        $clientId = 11;
        $client = new Client();

        $this->setEntityId($client, $clientId);

        $this->clientRepository->expects($this->once())
            ->method('findById')
            ->with($clientId)
            ->willReturn($client)
        ;

        $expectedClientToBeSaved = $this->createClient();
        $this->setEntityId($expectedClientToBeSaved, $clientId);

        $this->clientRepository->expects($this->once())
            ->method('saveAndFlush')
            ->with($expectedClientToBeSaved)
        ;

        $payload = $this->createEditPayload();

        $this->createService()->update($clientId, $payload);
    }

    private function createEditPayload(): ClientEditPayload
    {
        return (new ClientEditPayload())
            ->setEmail('test@test.com')
            ->setEducation(EducationEnum::HIGHER)
            ->setPhoneNumber('+71119993322')
            ->setFirstName('First name')
            ->setLastName('Last name')
            ->setConsentProcessingPersonalData(true)
        ;
    }

    private function createClient(): Client
    {
        return (new Client())
            ->setEmail('test@test.com')
            ->setEducation(EducationEnum::HIGHER)
            ->setPhoneNumber('+71119993322')
            ->setFirstName('First name')
            ->setLastName('Last name')
            ->setConsentProcessingPersonalData(true)
        ;
    }

    private function createService(): ClientService
    {
        return new ClientService($this->clientRepository, $this->clientScoreService);
    }
}
