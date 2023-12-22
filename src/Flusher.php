<?php

declare(strict_types=1);

namespace App;

use Doctrine\ORM\EntityManagerInterface;

class Flusher
{
    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function __construct(readonly private EntityManagerInterface $em)
    {
    }

    public function flush(): void
    {
        $this->em->flush();
    }
}
