<?php

declare(strict_types=1);

namespace App\Client\UseCase;

use App\Client\Entity\Id;

/**
 * @see UpdateHandler
 */
readonly class UpdateCommand
{
    public function __construct(
        public Id $id,
        public bool $emailVerified = false,
        public bool $isBirthday = false
    ) {
    }
}
