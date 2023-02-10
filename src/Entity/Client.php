<?php

namespace App\Entity;

use App\Enum\EducationEnum;
use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    private string $firstName;

    #[ORM\Column]
    private string $lastName;

    #[ORM\Column(length: 12)]
    private string $phoneNumber;

    #[ORM\Column]
    private string $email;

    #[ORM\Column]
    private bool $consentProcessingPersonalData;

    #[ORM\Column]
    private int $score;

    #[ORM\Column(enumType: EducationEnum::class)]
    private EducationEnum $education;

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function hasConsentProcessingPersonalData(): bool
    {
        return $this->consentProcessingPersonalData;
    }

    public function setConsentProcessingPersonalData(bool $consentProcessingPersonalData): self
    {
        $this->consentProcessingPersonalData = $consentProcessingPersonalData;
        return $this;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;
        return $this;
    }

    public function getEducation(): EducationEnum
    {
        return $this->education;
    }

    public function setEducation(EducationEnum $education): self
    {
        $this->education = $education;
        return $this;
    }
}
