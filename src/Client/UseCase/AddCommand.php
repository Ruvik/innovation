<?php

declare(strict_types=1);

namespace App\Client\UseCase;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "AddCommand",
    description: "Command for adding a new client",
    required: ["emailVerified", "isBirthday"],
    properties: [
        new OA\Property(
            property: "emailVerified",
            description: "Flag indicating if the client's email is verified",
            type: "boolean"
        ),
        new OA\Property(
            property: "isBirthday",
            description: "Flag indicating if today is the client's birthday",
            type: "boolean"
        )
    ],
    type: "object"
)]
readonly class AddCommand
{
    public function __construct(
        public bool $emailVerified = false,
        public bool $isBirthday = false
    ) {
    }
}
