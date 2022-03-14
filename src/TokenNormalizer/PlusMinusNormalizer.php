<?php

declare(strict_types=1);

namespace LeoVie\PhpTokenNormalize\TokenNormalizer;

use LeoVie\PhpTokenNormalize\Model\Token;
use PhpToken;

class PlusMinusNormalizer implements TokenNormalizer
{
    public function supports(PhpToken $token): bool
    {
        return in_array($token->id, [
            Token::getId(Token::T_PLUS),
            Token::getId(Token::T_MINUS),
        ]);
    }

    public function reset(): self
    {
        return $this;
    }

    /** @param PhpToken[] $prevTokens */
    public function normalizeToken(array $prevTokens, PhpToken $token): PhpToken
    {
        $prevTokensReverse = array_reverse($prevTokens);
        foreach ($prevTokensReverse as $prevToken) {
            if ($prevToken->id !== T_WHITESPACE && $prevToken->getTokenName() !== '=') {
                return $token;
            }

            if ($prevToken->getTokenName() === '=') {
                return new PhpToken(T_WHITESPACE, ' ', $token->line, $token->pos);
            }
        }

        return $token;
    }
}