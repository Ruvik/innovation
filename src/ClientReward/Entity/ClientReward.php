<?php

declare(strict_types=1);

namespace App\ClientReward\Entity;

use App\ClientReward\Repository\PostgresClientRewardRepository;
use DateTimeImmutable;
use DateTimeInterface;
use OpenApi\Attributes as OA;
use Doctrine\ORM\Mapping as ORM;

/**
 * @psalm-suppress all
 */
#[ORM\Entity(repositoryClass: PostgresClientRewardRepository::class)]
#[ORM\UniqueConstraint(name: "unique_client_bonus", columns: ["client_id", "bonus_id"])]
#[OA\Schema(schema: "ClientReward", description: "Associates a bonus with a client")]
class ClientReward
{
    #[ORM\Id, ORM\Column(type: "client_reward_id")]
    #[OA\Property(ref: "#/components/schemas/UuidSchema", description: "Unique identifier of the client reward")]
    private Id $id;

    #[ORM\Column(type: "client_id")]
    #[OA\Property(ref: "#/components/schemas/UuidSchema", description: "Identifier of the client")]
    private \App\Client\Entity\Id $clientId;

    #[ORM\Column(type: "bonus_id")]
    #[OA\Property(ref: "#/components/schemas/UuidSchema", description: "Identifier of the bonus")]
    private \App\Bonus\Entity\Id $bonusId;

    #[ORM\Column(name: "created_at", type: "datetime_immutable", options: ["default" => "CURRENT_TIMESTAMP"])]
    #[OA\Property(description: "The date and time when the client reward was created", type: "string", format: "date-time")]
    private DateTimeInterface $createdAt;
    public function __construct(Id $id, \App\Client\Entity\Id $clientId, \App\Bonus\Entity\Id $bonusId)
    {
        $this->id = $id;
        $this->clientId = $clientId;
        $this->bonusId = $bonusId;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getBonusId(): \App\Bonus\Entity\Id
    {
        return $this->bonusId;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}
