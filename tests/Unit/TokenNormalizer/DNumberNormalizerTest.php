<?php

declare(strict_types=1);

namespace LeoVie\PhpTokenNormalize\Tests\Unit\TokenNormalizer;

use LeoVie\PhpTokenNormalize\TokenNormalizer\DNumberNormalizer;
use PhpToken;
use PHPUnit\Framework\TestCase;

class DNumberNormalizerTest extends TestCase
{
    /** @dataProvider supportsProvider */
    public function testSupports(bool $expected, PhpToken $token): void
    {
        self::assertSame($expected, (new DNumberNormalizer())->supports($token));
    }

    public function supportsProvider(): array
    {
        return [
            'T_DNUMBER' => [
                true,
                new PhpToken(T_DNUMBER, ''),
            ],
            'T_VARIABLE' => [
                false,
                new PhpToken(T_VARIABLE, ''),
            ],
        ];
    }

    /** @dataProvider normalizeProvider */
    public function testNormalize(PhpToken $expected, PhpToken $token): void
    {
        self::assertEquals($expected, (new DNumberNormalizer())->normalizeToken([], $token));
    }

    public function normalizeProvider(): array
    {
        return [
            [
                'expected' => new PhpToken(T_DNUMBER, '1.0', 10, 20),
                'token' => new PhpToken(T_DNUMBER, '0.5', 10, 20),
            ],
            [
                'expected' => new PhpToken(T_DNUMBER, '1.0', 199, 71),
                'token' => new PhpToken(T_DNUMBER, '199.6', 199, 71),
            ],
        ];
    }
}