<?php

declare(strict_types=1);

namespace LeoVie\PhpTokenNormalize\Tests\Unit\TokenNormalizer;

use LeoVie\PhpTokenNormalize\TokenNormalizer\NothingToNormalizeNormalizer;
use PhpToken;
use PHPUnit\Framework\TestCase;

class NothingToNormalizeNormalizerTest extends TestCase
{
    /** @dataProvider supportsProvider */
    public function testSupports(bool $expected, PhpToken $token): void
    {
        self::assertSame($expected, (new NothingToNormalizeNormalizer())->supports($token));
    }

    public function supportsProvider(): array
    {
        return [
            'T_SWITCH' => [
                true,
                new PhpToken(T_SWITCH, ''),
            ],
            'T_STATIC' => [
                true,
                new PhpToken(T_STATIC, ''),
            ],
        ];
    }

    /** @dataProvider normalizeProvider */
    public function testNormalize(PhpToken $token): void
    {
        self::assertEquals($token, (new NothingToNormalizeNormalizer())->normalizeToken([], $token));
    }

    public function normalizeProvider(): array
    {
        return [
            [
                'token' => new PhpToken(T_SWITCH, 'lorem ipsum', 10, 20),
            ],
            [
                'token' => new PhpToken(T_STATIC, '', 199, 71),
            ],
        ];
    }
}