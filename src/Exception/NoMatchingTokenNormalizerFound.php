<?php

declare(strict_types=1);

namespace LeoVie\PhpTokenNormalize\Exception;

use Exception;
use Safe\Exceptions\StringsException;

class NoMatchingTokenNormalizerFound extends Exception
{
    /** @throws StringsException */
    private function __construct(string $tokenType)
    {
        parent::__construct(\Safe\sprintf('No matching token normalizer found for token type %s.', $tokenType));
    }

    /** @throws StringsException */
    public static function create(string $tokenType): self
    {
        return new self($tokenType);
    }
}