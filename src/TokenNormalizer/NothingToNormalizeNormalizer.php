<?php

declare(strict_types=1);

namespace LeoVie\PhpTokenNormalize\TokenNormalizer;

use PhpToken;

class NothingToNormalizeNormalizer implements TokenNormalizer
{
    /** @codeCoverageIgnore */
    public static function getDefaultPriority(): int
    {
        return PHP_INT_MIN;
    }

    public function supports(PhpToken $token): bool
    {
        return true;
    }

    public function reset(): self
    {
        return $this;
    }

    /** @param PhpToken[] $prevTokens */
    public function normalizeToken(array $prevTokens, PhpToken $token): PhpToken
    {
        return new PhpToken($token->id, $token->text, $token->line, $token->pos);
    }
}