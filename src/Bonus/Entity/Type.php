<?php

namespace App\Bonus\Entity;

/**
 * @psalm-suppress UnusedClass
 */
enum Type: int
{
    case SMILE = 1;
    case HUG = 2;

    private const CLASS_MAPPING = [
        self::SMILE->value => Smile::class,
        self::HUG->value => Hug::class,
    ];

    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function getClass(): string
    {
        return self::CLASS_MAPPING[$this->value];
    }
}
