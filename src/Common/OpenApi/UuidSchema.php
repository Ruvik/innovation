<?php

declare(strict_types=1);

namespace App\Common\OpenApi;

use OpenApi\Attributes as OA;

/**
 * @psalm-suppress UnusedClass
 */
#[OA\Schema(
    schema: "UuidSchema",
    description: "A universally unique identifier (UUID)",
    type: "string",
    format: "uuid",
    example: "123e4567-e89b-12d3-a456-426614174000"
)]
class UuidSchema
{
    // This class only serves as a placeholder for OpenAPI documentation and is not meant to be instantiated.
}
