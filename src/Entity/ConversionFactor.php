<?php

namespace App\Entity;

use App\Repository\ConversionFactorRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ConversionFactorRepository::class)]
#[ORM\Table(name: 'conversion_factors')]
class ConversionFactor
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 6)]
    private ?string $originUnit = null;

    #[ORM\Column(length: 6)]
    private ?string $targetUnit = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 4, options: ['unsigned' => true, 'default' => 1])]
    private ?string $factor = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): void
    {
        $this->id = $id;
    }

    public function getOriginUnit(): ?string
    {
        return $this->originUnit;
    }

    public function setOriginUnit(string $originUnit): void
    {
        $this->originUnit = $originUnit;
    }

    public function getTargetUnit(): ?string
    {
        return $this->targetUnit;
    }

    public function setTargetUnit(string $targetUnit): void
    {
        $this->targetUnit = $targetUnit;
    }

    public function getFactor(): ?float
    {
        return (float)$this->factor;
    }

    public function setFactor(string $factor): void
    {
        $this->factor = $factor;
    }
}
