<?php

declare(strict_types=1);

namespace LeoVie\PhpTokenNormalize\Service;

class RandomGenerator
{
    public function generate(): int
    {
        return rand(100, 200);
    }
}