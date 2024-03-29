<?php

declare(strict_types=1);

namespace LeoVie\PhpTokenNormalize\TokenNormalizer;

use PhpToken;

class LNumberNormalizer implements TokenNormalizer
{
    public function supports(PhpToken $token): bool
    {
        return $token->id === T_LNUMBER;
    }

    /** @param PhpToken[] $prevTokens */
    public function normalizeToken(array $prevTokens, PhpToken $token): PhpToken
    {
        return new PhpToken(T_LNUMBER, '1', $token->line, $token->pos);
    }
}