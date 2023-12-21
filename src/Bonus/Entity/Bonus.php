<?php

declare(strict_types=1);

namespace App\Bonus\Entity;

use App\Bonus\Repository\PostgresBonusRepository;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;

#[ORM\Entity(repositoryClass: PostgresBonusRepository::class)]
#[ORM\InheritanceType("SINGLE_TABLE")]
#[ORM\DiscriminatorColumn(name: "type", enumType: Type::class)]
#[ORM\DiscriminatorMap([Type::SMILE->value => Smile::class, Type::HUG->value => Hug::class])]
#[OA\Schema(schema: "Bonus", type: "object", description: "Abstract base class for Bonuses")]
abstract class Bonus
{
    #[ORM\Id, ORM\Column(type: "bonus_id")]
    #[OA\Property(ref: "#/components/schemas/UuidSchema", description: "Unique identifier for the Bonus")]
    protected Id $id;

    #[ORM\Column(type: "string", length: 255)]
    #[OA\Property(description: "Name of the Bonus", example: "New Year Hug")]
    protected string $name;

    protected Type $type;

    public function __construct(Id $id, string $name, Type $type)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
    }

    abstract public function getTypeName(): string;

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
