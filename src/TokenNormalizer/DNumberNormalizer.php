<?php

declare(strict_types=1);

namespace LeoVie\PhpTokenNormalize\TokenNormalizer;

use PhpToken;

class DNumberNormalizer implements TokenNormalizer
{
    public function supports(PhpToken $token): bool
    {
        return $token->id === T_DNUMBER;
    }

    /** @param PhpToken[] $prevTokens */
    public function normalizeToken(array $prevTokens, PhpToken $token): PhpToken
    {
        return new PhpToken(T_DNUMBER, '1.0', $token->line, $token->pos);
    }
}