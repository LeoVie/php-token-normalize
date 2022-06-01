<?php

declare(strict_types=1);

namespace LeoVie\PhpTokenNormalize\TokenNormalizer;

use PhpToken;

class ConstantEncapsedStringNormalizer implements TokenNormalizer
{
    public function supports(PhpToken $token): bool
    {
        return $token->id === T_CONSTANT_ENCAPSED_STRING;
    }

    public function reset(): self
    {
        return $this;
    }

    /** @param PhpToken[] $prevTokens */
    public function normalizeToken(array $prevTokens, PhpToken $token): PhpToken
    {
        return new PhpToken(T_CONSTANT_ENCAPSED_STRING, "'constant_encapsed_string'", $token->line, $token->pos);
    }
}