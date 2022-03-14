<?php

declare(strict_types=1);

namespace LeoVie\PhpTokenNormalize\Service;

use LeoVie\PhpTokenNormalize\Model\TokenSequence;
use LeoVie\PhpTokenNormalize\TokenNormalizer\TokenNormalizer;
use LeoVie\PhpTokenNormalize\TokenNormalizer\TokenNormalizerCollection;

class TokenSequenceNormalizer
{
    public function __construct(
        private TokenNormalizerCollection $tokenNormalizerCollection,
    )
    {
    }

    public function normalizeLevel1(TokenSequence $tokenSequence): TokenSequence
    {
        return $tokenSequence
            ->withoutOpenTag()
            ->withoutCloseTag()
            ->withoutAccessModifiers()
            ->withoutWhitespaces()
            ->withoutComments()
            ->withoutDocComments()
            ->filter();
    }

    public function normalizeLevel2(TokenSequence $tokenSequence): TokenSequence
    {
        $this->tokenNormalizerCollection->walk(fn(TokenNormalizer $tn) => $tn->reset());

        $normalizedTokens = [];
        foreach ($tokenSequence->getTokens() as $token) {
            $normalizedToken = $this->tokenNormalizerCollection->findMatching($token)
                ->normalizeToken($normalizedTokens, $token);
            $normalizedTokens[] = $normalizedToken;
        }

        return $this->normalizeLevel1(TokenSequence::create($normalizedTokens));
    }

    public function normalizeLevel4(TokenSequence $tokenSequence): TokenSequence
    {
        return $this->normalizeLevel1($tokenSequence)
            ->withoutFunctionStatic()
            ->withoutOutputs()
            ->filter();
    }
}