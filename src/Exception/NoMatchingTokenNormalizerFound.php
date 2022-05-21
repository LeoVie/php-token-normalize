<?php

declare(strict_types=1);

namespace LeoVie\PhpTokenNormalize\Exception;

use Exception;

class NoMatchingTokenNormalizerFound extends Exception
{
    private function __construct(string $tokenType)
    {
        parent::__construct(sprintf('No matching token normalizer found for token type %s.', $tokenType));
    }

    public static function create(string $tokenType): self
    {
        return new self($tokenType);
    }
}