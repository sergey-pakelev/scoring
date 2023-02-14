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
        $request = (new ClientUpdateRequest())
            ->setEmail('test@test.com')
            ->setEducation(EducationEnum::HIGHER)
            ->setPhoneNumber('+71119993322')
            ->setFirstName('First name')
            ->setLastName('Last name')
            ->setConsentProcessingPersonalData(true)
        ;

        $expectedClient = (new Client())
            ->setEmail('test@test.com')
            ->setEducation(EducationEnum::HIGHER)
            ->setPhoneNumber('+71119993322')
            ->setFirstName('First name')
            ->setLastName('Last name')
            ->setConsentProcessingPersonalData(true)
        ;

        $expectedClientScore = 10;

        $this->clientScoreService->expects($this->once())
            ->method('calculateScore')
            ->with($expectedClient)
            ->willReturn($expectedClientScore)
        ;

        $expectedClientToBeSaved = (clone $expectedClient)->setScore($expectedClientScore);
        $expectedClientId = 11;

        $this->clientRepository->expects($this->once())
            ->method('save')
            ->with($expectedClientToBeSaved)
            ->will($this->returnCallback(function (Client $client) use ($expectedClientId) {
                $this->setEntityId($client, $expectedClientId);
            }))
        ;

        $this->assertEquals($expectedClientId, $this->createService()->create($request));
    }

    private function createService(): ClientService
    {
        return new ClientService($this->clientRepository, $this->clientScoreService);
    }
}
