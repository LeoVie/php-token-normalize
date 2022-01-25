<?php

declare(strict_types=1);

namespace LeoVie\PhpTokenNormalize\Tests\Unit\TokenNormalizer;

use LeoVie\PhpTokenNormalize\Model\Token;
use LeoVie\PhpTokenNormalize\TokenNormalizer\PlusMinusNormalizer;
use PhpToken;
use PHPUnit\Framework\TestCase;

class PlusMinusNormalizerTest extends TestCase
{
    /** @dataProvider supportsProvider */
    public function testSupports(bool $expected, PhpToken $token): void
    {
        self::assertSame($expected, (new PlusMinusNormalizer())->supports($token));
    }

    public function supportsProvider(): array
    {
        return [
            '+' => [
                true,
                new PhpToken(Token::getId(Token::T_PLUS), ''),
            ],
            '*' => [
                false,
                new PhpToken(Token::getId(Token::T_MULTIPLY), ''),
            ],
        ];
    }

    public function testReset(): void
    {
        $normalizer = new PlusMinusNormalizer();
        self::assertSame($normalizer, $normalizer->reset());
    }

    /** @dataProvider normalizeProvider */
    public function testNormalize(PhpToken $expected, array $prevTokens, PhpToken $token): void
    {
        self::assertEquals($expected, (new PlusMinusNormalizer())->normalizeToken($prevTokens, $token));
    }

    public function normalizeProvider(): array
    {
        return [
            [
                'expected' => new PhpToken(Token::getId(Token::T_PLUS), '+', 10, 20),
                'prevTokens' => [],
                'token' => new PhpToken(Token::getId(Token::T_PLUS), '+', 10, 20),
            ],
            [
                'expected' => new PhpToken(Token::getId(Token::T_PLUS), '+', 10, 20),
                'prevTokens' => [
                    new PhpToken(T_WHITESPACE, ' '),
                    new PhpToken(Token::getId(Token::T_EQUAL), '='),
                    new PhpToken(T_LNUMBER, '10'),
                ],
                'token' => new PhpToken(Token::getId(Token::T_PLUS), '+', 10, 20),
            ],
            [
                'expected' => new PhpToken(T_WHITESPACE, ' ', 10, 20),
                'prevTokens' => [
                    new PhpToken(T_WHITESPACE, ' '),
                    new PhpToken(Token::getId(Token::T_EQUAL), '='),
                    new PhpToken(T_WHITESPACE, ' '),
                ],
                'token' => new PhpToken(Token::getId(Token::T_PLUS), '+', 10, 20),
            ],
        ];
    }
}