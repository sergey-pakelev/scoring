<?php

namespace App\DTO;

use App\Enum\EducationEnum;

class ClientUpdateRequest
{
    private ?string $firstName = null;

    private ?string $lastName = null;

    private ?string $phoneNumber = null;

    private ?string $email = null;

    private bool $consentProcessingPersonalData = false;

    private ?EducationEnum $education = null;

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
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

    public function getEducation(): ?EducationEnum
    {
        return $this->education;
    }

    public function setEducation(?EducationEnum $education): self
    {
        $this->education = $education;
        return $this;
    }
}
