<?php

declare(strict_types=1);

namespace LeoVie\PhpTokenNormalize\Tests\Unit\Service;

use ArrayIterator;
use Iterator;
use LeoVie\PhpTokenNormalize\Model\TokenSequence;
use LeoVie\PhpTokenNormalize\Service\TokenSequenceNormalizer;
use LeoVie\PhpTokenNormalize\TokenNormalizer\NothingToNormalizeNormalizer;
use LeoVie\PhpTokenNormalize\TokenNormalizer\TokenNormalizer;
use PhpToken;
use PHPUnit\Framework\TestCase;

class TokenSequenceNormalizerTest extends TestCase
{
    /** @dataProvider normalizeLevel1Provider */
    public function testNormalizeLevel1(TokenSequence $expected, TokenSequence $tokenSequence): void
    {
        self::assertEquals($expected, (new TokenSequenceNormalizer(
            $this->createMock(Iterator::class))
        )->normalizeLevel1($tokenSequence));
    }

    public function normalizeLevel1Provider(): array
    {
        $tokenToBeLeft = new PhpToken(T_VARIABLE, '');
        return [
            [
                'expected' => TokenSequence::create([$tokenToBeLeft]),
                'tokenSequence' => TokenSequence::create([
                    new PhpToken(T_OPEN_TAG, ''),
                    new PhpToken(T_CLOSE_TAG, ''),
                    $tokenToBeLeft,
                    new PhpToken(T_PUBLIC, ''),
                    new PhpToken(T_WHITESPACE, ''),
                    new PhpToken(T_COMMENT, ''),
                    new PhpToken(T_DOC_COMMENT, ''),
                ]),
            ],
        ];
    }

    /** @dataProvider normalizeLevel2Provider */
    public function testNormalizeLevel2(TokenSequence $expected, TokenSequence $tokenSequence): void
    {
        $variableNormalizer = $this->mockTokenNormalizer(
            fn(PhpToken $token): bool => $token->id === T_VARIABLE,
            fn(array $prevTokens, PhpToken $token) => new PhpToken($token->id, 'NORMALIZED_VARIABLE_' . $token->text),
        );

        $lNumberNormalizer = $this->mockTokenNormalizer(
            fn(PhpToken $token): bool => $token->id === T_LNUMBER,
            fn(array $prevTokens, PhpToken $token) => new PhpToken($token->id, 'NORMALIZED_LNUMBER_' . $token->text),
        );

        $nothingToNormalizeNormalizer = $this->createMock(NothingToNormalizeNormalizer::class);
        $nothingToNormalizeNormalizer->method('supports')->willReturnCallback(fn(PhpToken $token): bool => true);
        $nothingToNormalizeNormalizer->method('normalizeToken')->willReturnCallback(
            fn(array $prevTokens, PhpToken $token) => new PhpToken($token->id, 'NOT_NORMALIZED_' . $token->text)
        );

        $tokenNormalizers = new ArrayIterator([$variableNormalizer, $lNumberNormalizer, $nothingToNormalizeNormalizer]);

        self::assertEquals($expected, (new TokenSequenceNormalizer($tokenNormalizers))->normalizeLevel2($tokenSequence));
    }

    public function normalizeLevel2Provider(): array
    {
        return [
            [
                'expected' => TokenSequence::create([
                    new PhpToken(T_VARIABLE, 'NORMALIZED_VARIABLE_$a'),
                    new PhpToken(T_LNUMBER, 'NORMALIZED_LNUMBER_700'),
                ]),
                'tokenSequence' => TokenSequence::create([
                    new PhpToken(T_OPEN_TAG, '<?php'),
                    new PhpToken(T_CLOSE_TAG, '?>'),
                    new PhpToken(T_VARIABLE, '$a'),
                    new PhpToken(T_PUBLIC, 'public'),
                    new PhpToken(T_LNUMBER, '700'),
                    new PhpToken(T_WHITESPACE, ' '),
                    new PhpToken(T_COMMENT, '// foo'),
                    new PhpToken(T_DOC_COMMENT, '/** bar */'),
                ]),
            ],
        ];
    }

    private function mockTokenNormalizer(callable $supports, callable $normalizeToken): TokenNormalizer
    {
        $normalizer = $this->createMock(TokenNormalizer::class);
        $normalizer->method('supports')->willReturnCallback($supports);
        $normalizer->method('normalizeToken')->willReturnCallback($normalizeToken);

        return $normalizer;
    }

    /** @dataProvider normalizeLevel4Provider */
    public function testNormalizeLevel4(TokenSequence $expected, TokenSequence $tokenSequence): void
    {
        self::assertEquals($expected, (new TokenSequenceNormalizer(
            $this->createMock(Iterator::class))
        )->normalizeLevel4($tokenSequence));
    }

    public function normalizeLevel4Provider(): array
    {
        $variable = new PhpToken(T_VARIABLE, '');
        return [
            [
                'expected' => TokenSequence::create([$variable]),
                'tokenSequence' => TokenSequence::create([
                    new PhpToken(T_OPEN_TAG, ''),
                    new PhpToken(T_CLOSE_TAG, ''),
                    new PhpToken(T_ECHO, ''),
                    $variable,
                    new PhpToken(T_PUBLIC, ''),
                    new PhpToken(T_WHITESPACE, ''),
                    new PhpToken(T_PRINT, ''),
                    new PhpToken(T_COMMENT, ''),
                    new PhpToken(T_DOC_COMMENT, ''),
                ]),
            ],
        ];
    }
}