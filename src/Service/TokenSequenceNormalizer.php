<?php

declare(strict_types=1);

namespace LeoVie\PhpTokenNormalize\Service;

use LeoVie\PhpTokenNormalize\Exception\NoMatchingTokenNormalizerFound;
use LeoVie\PhpTokenNormalize\Model\TokenSequence;
use LeoVie\PhpTokenNormalize\TokenNormalizer\TokenNormalizer;
use PhpToken;
use Safe\Exceptions\StringsException;

class TokenSequenceNormalizer
{
    /** @param iterable<TokenNormalizer> $tokenNormalizers */
    public function __construct(
        private iterable $tokenNormalizers,
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
        foreach ($this->tokenNormalizers as $tokenNormalizer) {
            $tokenNormalizer->reset();
        }

        $normalizedTokens = [];
        foreach ($tokenSequence->getTokens() as $token) {
            $normalizedToken = $this->findMatchingTokenNormalizer($token)->normalizeToken($normalizedTokens, $token);
            $normalizedTokens[] = $normalizedToken;
        }

        return TokenSequence::create($normalizedTokens);
    }

    /**
     * @throws NoMatchingTokenNormalizerFound
     * @throws StringsException
     */
    private function findMatchingTokenNormalizer(PhpToken $token): TokenNormalizer
    {
        foreach ($this->tokenNormalizers as $tokenNormalizer) {
            if ($tokenNormalizer->supports($token)) {
                return $tokenNormalizer;
            }
        }

        /** @var string $tokenName */
        $tokenName = $token->getTokenName();

        throw NoMatchingTokenNormalizerFound::create($tokenName);
    }

    public function normalizeLevel4(TokenSequence $tokenSequence): TokenSequence
    {
        return $this->normalizeLevel1($tokenSequence)
            ->withoutFunctionStatic()
            ->withoutOutputs()
            ->filter();
    }
}