<?php

namespace LeoVie\PhpTokenNormalize\Model;

use PhpToken;

class TokenSequence
{
    /** @var int[] */
    private array $tokenTypesToIgnore = [];

    /** @param PhpToken[] $tokens */
    private function __construct(private array $tokens)
    {
    }

    /** @param PhpToken[] $tokens */
    public static function create(array $tokens): self
    {
        return new self($tokens);
    }

    /** @return PhpToken[] */
    public function getTokens(): array
    {
        return $this->tokens;
    }

    public function length(): int
    {
        return count($this->getTokens());
    }

    public function isEmpty(): bool
    {
        return $this->length() === 0;
    }

    public function filter(): self
    {
        return new self(
            array_values(
                array_filter(
                    $this->tokens,
                    fn(PhpToken $t): bool => !in_array($t->id, $this->tokenTypesToIgnore)
                )
            ),
        );
    }

    public function equals(TokenSequence $other): bool
    {
        return $this->identity() === $other->identity();
    }

    public function identity(): string
    {
        return $this->toCode();
    }

    public function withoutAccessModifiers(): self
    {
        return $this->ignoreTokenType(T_PUBLIC)
            ->ignoreTokenType(T_PROTECTED)
            ->ignoreTokenType(T_PRIVATE);
    }

    public function withoutOpenTag(): self
    {
        return $this->ignoreTokenType(T_OPEN_TAG);
    }

    public function withoutCloseTag(): self
    {
        return $this->ignoreTokenType(T_CLOSE_TAG);
    }

    public function withoutWhitespaces(): self
    {
        return $this->ignoreTokenType(T_WHITESPACE);
    }

    public function withoutComments(): self
    {
        return $this->ignoreTokenType(T_COMMENT);
    }

    public function withoutDocComments(): self
    {
        return $this->ignoreTokenType(T_DOC_COMMENT);
    }

    public function withoutOutputs(): self
    {
        return $this->ignoreTokenType(T_PRINT)
            ->ignoreTokenType(T_ECHO);
    }

    private function ignoreTokenType(int $type): self
    {
        $this->tokenTypesToIgnore = array_merge($this->tokenTypesToIgnore, [$type]);

        return $this;
    }

    public function toCode(): string
    {
        return join(' ', $this->getTokens());
    }
}