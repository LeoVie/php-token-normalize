<?php

declare(strict_types=1);

namespace LeoVie\PhpTokenNormalize\TokenNormalizer;

use LeoVie\PhpTokenNormalize\Exception\NoMatchingTokenNormalizerFound;
use PhpToken;
use Safe\Exceptions\StringsException;

class TokenNormalizerCollection
{
    /** @var array<class-string<PhpToken>, TokenNormalizer> */
    private array $tokenNormalizersSupportMappingCache = [];

    /** @param iterable<TokenNormalizer> $tokenNormalizers */
    public function __construct(private iterable $tokenNormalizers)
    {
    }

    /** @return iterable<TokenNormalizer> */
    public function getAll(): iterable
    {
        return $this->tokenNormalizers;
    }

    public function walk(callable $fn): void
    {
        foreach ($this->tokenNormalizers as $tokenNormalizer) {
            $fn($tokenNormalizer);
        }
    }

    /**
     * @throws NoMatchingTokenNormalizerFound
     * @throws StringsException
     */
    public function findMatching(PhpToken $token): TokenNormalizer
    {
        if (in_array($token::class, $this->tokenNormalizersSupportMappingCache)) {
            return $this->tokenNormalizersSupportMappingCache[$token::class];
        }

        foreach ($this->tokenNormalizers as $tokenNormalizer) {
            if ($tokenNormalizer->supports($token)) {
                $this->tokenNormalizersSupportMappingCache[$token::class] = $tokenNormalizer;

                return $tokenNormalizer;
            }
        }

        /** @var string $tokenName */
        $tokenName = $token->getTokenName();

        throw NoMatchingTokenNormalizerFound::create($tokenName);
    }
}