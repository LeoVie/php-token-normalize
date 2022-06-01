<?php

declare(strict_types=1);

namespace LeoVie\PhpTokenNormalize\Tests\Unit\TokenNormalizer;

use LeoVie\PhpTokenNormalize\TokenNormalizer\StringNormalizer;
use PhpToken;
use PHPUnit\Framework\TestCase;

class StringNormalizerTest extends TestCase
{
    /** @dataProvider supportsProvider */
    public function testSupports(bool $expected, PhpToken $token): void
    {
        self::assertSame($expected, (new StringNormalizer())->supports($token));
    }

    public function supportsProvider(): array
    {
        return [
            'T_STRING' => [
                true,
                new PhpToken(T_STRING, ''),
            ],
            'T_VARIABLE' => [
                false,
                new PhpToken(T_VARIABLE, ''),
            ],
        ];
    }

    public function testReset(): void
    {
        $normalizer = new StringNormalizer();
        self::assertSame($normalizer, $normalizer->reset());
    }

    /** @dataProvider normalizeProvider */
    public function testNormalize(PhpToken $expected, PhpToken $token): void
    {
        self::assertEquals($expected, (new StringNormalizer())->normalizeToken([], $token));
    }

    public function normalizeProvider(): array
    {
        return [
            [
                'expected' => new PhpToken(T_STRING, 'str', 10, 20),
                'token' => new PhpToken(T_STRING, 'lorem ipsum', 10, 20),
            ],
            [
                'expected' => new PhpToken(T_STRING, 'str', 199, 71),
                'token' => new PhpToken(T_STRING, '', 199, 71),
            ],
        ];
    }
}