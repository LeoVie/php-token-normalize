<?php

declare(strict_types=1);

namespace LeoVie\PhpTokenNormalize\Exception;

use Exception;;

class NoReplacementRegistered extends Exception
{
    private function __construct(string $original)
    {
        parent::__construct(sprintf('No replacement registered for %s.', $original));
    }

    public static function create(string $original): self
    {
        return new self($original);
    }
}