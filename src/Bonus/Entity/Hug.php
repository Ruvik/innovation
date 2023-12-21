<?php

declare(strict_types=1);

namespace App\Bonus\Entity;

use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;

#[ORM\Entity]
#[OA\Schema(schema: "Hug", allOf: [new OA\Schema(ref: "#/components/schemas/Bonus")])]
class Hug extends Bonus
{
    public function __construct(Id $id, string $name)
    {
        parent::__construct($id, $name, Type::HUG);
    }

    public function getTypeName(): string
    {
        return 'Hug';
    }
}
