<?php

declare(strict_types=1);

namespace App\Client\Entity;

use App\Client\Repository\PostgresClientRepository;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;

#[ORM\Entity(repositoryClass: PostgresClientRepository::class)]
#[OA\Schema(schema: "Client", description: "Client entity representing a user of the application.")]
class Client
{
    #[ORM\Id, ORM\Column(type: "client_id")]
    #[OA\Property(ref: "#/components/schemas/UuidSchema", description: "Unique identifier of the client")]
    private Id $id;

    #[ORM\Column(type: "boolean")]
    #[OA\Property(description: "Flag indicating if the client's email is verified", example: false)]
    private bool $emailVerified = false;

    #[ORM\Column(type: "boolean")]
    #[OA\Property(description: "Flag indicating if today is the client's birthday", example: false)]
    private bool $isBirthday = false;


    public function __construct(Id $id)
    {
        $this->id = $id;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function isEmailVerified(): bool
    {
        return $this->emailVerified;
    }

    public function markEmailVerified(): void
    {
        $this->emailVerified = true;
    }

    public function isBirthday(): bool
    {
        return $this->isBirthday;
    }

    public function markIsBirthday(): void
    {
        $this->isBirthday = true;
    }
}
