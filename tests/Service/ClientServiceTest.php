<?php

namespace App\Tests\Service;

use App\DTO\ClientUpdateRequest;
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

    public function testCreate()
    {
        $request = $this->createUpdateRequest();
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

        $this->assertEquals($expectedClientId, $this->createService()->create($request));
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

        $request = $this->createUpdateRequest();

        $this->createService()->update($clientId, $request);
    }

    private function createUpdateRequest(): ClientUpdateRequest
    {
        return (new ClientUpdateRequest())
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
