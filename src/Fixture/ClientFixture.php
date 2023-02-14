<?php

namespace App\Fixture;

use App\Entity\Client;
use App\Enum\EducationEnum;
use App\Service\ClientScoreService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class ClientFixture extends Fixture
{
    private ?Generator $faker = null;

    private const EMAIL_DOMAINS = [
        'gmail.com', 'yandex.ru', 'mail.ru', 'yahoo.com',
    ];

    private const PHONE_NUMBER_CARRIER_CODES = [
        '902', '900', '901', '000',
    ];

    public function __construct(
        private readonly ClientScoreService $clientScoreService,
    ) {
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; ++$i) {
            $client = $this->createClient();
            $manager->persist($client);
        }

        $manager->flush();
    }

    private function createClient(): Client
    {
        $faker = $this->getFaker();

        $firstName = $faker->firstName();
        $lastName = $faker->lastName();

        $email = strtolower("$firstName$lastName").'@'.$this->getRandomEmailDomain();

        $client = (new Client())
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setEmail($email)
            ->setPhoneNumber($this->getRandomPhoneNumber())
            ->setConsentProcessingPersonalData(1 === rand(0, 1))
            ->setEducation($this->getRandomEducation())
        ;

        $client->setScore($this->clientScoreService->calculateScore($client));

        return $client;
    }

    private function getFaker(): Generator
    {
        if (!$this->faker) {
            $this->faker = Factory::create();
        }

        return $this->faker;
    }

    private function getRandomEmailDomain(): string
    {
        return self::EMAIL_DOMAINS[array_rand(self::EMAIL_DOMAINS)];
    }

    private function getRandomPhoneNumber(): string
    {
        $phoneNumber = $this->getFaker()->e164PhoneNumber();
        $carrierCode = self::PHONE_NUMBER_CARRIER_CODES[array_rand(self::PHONE_NUMBER_CARRIER_CODES)];

        $phoneNumber = substr_replace($phoneNumber, $carrierCode, 2, 3);
        return substr_replace($phoneNumber, '7', 1, 1);
    }

    private function getRandomEducation(): EducationEnum
    {
        $cases = EducationEnum::cases();

        return $cases[array_rand($cases)];
    }
}
