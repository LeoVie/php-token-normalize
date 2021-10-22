<?php

declare(strict_types=1);

namespace LeoVie\PhpTokenNormalize\TokenNormalizer;

use PhpToken;

interface TokenNormalizer
{
    public function supports(PhpToken $token): bool;
    public function reset(): self;
    public function normalizeToken(PhpToken $token): PhpToken;
}