<?php

declare(strict_types=1);

namespace LeoVie\PhpTokenNormalize\Tests\Unit\TokenNormalizer;

use LeoVie\PhpTokenNormalize\TokenNormalizer\LNumberNormalizer;
use PhpToken;
use PHPUnit\Framework\TestCase;

class LNumberNormalizerTest extends TestCase
{
    /** @dataProvider supportsProvider */
    public function testSupports(bool $expected, PhpToken $token): void
    {
        self::assertSame($expected, (new LNumberNormalizer())->supports($token));
    }

    public function supportsProvider(): array
    {
        return [
            'T_LNUMBER' => [
                true,
                new PhpToken(T_LNUMBER, ''),
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
        self::assertEquals($expected, (new LNumberNormalizer())->normalizeToken([], $token));
    }

    public function normalizeProvider(): array
    {
        return [
            [
                'expected' => new PhpToken(T_LNUMBER, '1', 10, 20),
                'token' => new PhpToken(T_LNUMBER, '15', 10, 20),
            ],
            [
                'expected' => new PhpToken(T_LNUMBER, '1', 199, 71),
                'token' => new PhpToken(T_LNUMBER, '1000', 199, 71),
            ],
        ];
    }
}