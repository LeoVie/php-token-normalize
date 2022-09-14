<?php

declare(strict_types=1);

namespace LeoVie\PhpTokenNormalize\Tests\Unit\TokenNormalizer;

use LeoVie\PhpTokenNormalize\TokenNormalizer\VariableNormalizer;
use PhpToken;
use PHPUnit\Framework\TestCase;

class VariableNormalizerTest extends TestCase
{
    /** @dataProvider supportsProvider */
    public function testSupports(bool $expected, PhpToken $token): void
    {
        self::assertSame($expected, (new VariableNormalizer())->supports($token));
    }

    public function supportsProvider(): array
    {
        return [
            'T_VARIABLE' => [
                true,
                new PhpToken(T_VARIABLE, ''),
            ],
            'T_LNUMBER' => [
                false,
                new PhpToken(T_LNUMBER, ''),
            ],
        ];
    }

    /** @dataProvider normalizeProvider */
    public function testNormalize(PhpToken $expected, PhpToken $token): void
    {
        self::assertEquals($expected, (new VariableNormalizer())->normalizeToken([], $token));
    }

    public function normalizeProvider(): array
    {
        return [
            [
                'expected' => new PhpToken(T_VARIABLE, '$x', 10, 20),
                'token' => new PhpToken(T_VARIABLE, '$foo', 10, 20),
            ],
            [
                'expected' => new PhpToken(T_VARIABLE, '$x', 199, 71),
                'token' => new PhpToken(T_VARIABLE, '$bar', 199, 71),
            ],
        ];
    }
}