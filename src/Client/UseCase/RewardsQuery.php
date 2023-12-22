<?php

declare(strict_types=1);

namespace App\Client\UseCase;

use App\Client\Entity\Id;
use Webmozart\Assert\Assert;

readonly class RewardsQuery
{
    public function __construct(public Id $id, public int $limit = 10, public int $page = 1)
    {
        Assert::positiveInteger($this->limit);
        Assert::lessThan($this->limit, 1000);
        Assert::positiveInteger($this->page);
    }
}
