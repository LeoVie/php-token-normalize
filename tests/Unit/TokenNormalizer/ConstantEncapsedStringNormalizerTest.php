<?php

declare(strict_types=1);

namespace LeoVie\PhpTokenNormalize\Tests\Unit\TokenNormalizer;

use LeoVie\PhpTokenNormalize\TokenNormalizer\ConstantEncapsedStringNormalizer;
use PhpToken;
use PHPUnit\Framework\TestCase;

class ConstantEncapsedStringNormalizerTest extends TestCase
{
    /** @dataProvider supportsProvider */
    public function testSupports(bool $expected, PhpToken $token): void
    {
        self::assertSame($expected, (new ConstantEncapsedStringNormalizer())->supports($token));
    }

    public function supportsProvider(): array
    {
        return [
            'T_CONSTANT_ENCAPSED_STRING' => [
                true,
                new PhpToken(T_CONSTANT_ENCAPSED_STRING, ''),
            ],
            'T_VARIABLE' => [
                false,
                new PhpToken(T_VARIABLE, ''),
            ],
        ];
    }

    public function testReset(): void
    {
        $normalizer = new ConstantEncapsedStringNormalizer();
        self::assertSame($normalizer, $normalizer->reset());
    }

    /** @dataProvider normalizeProvider */
    public function testNormalize(PhpToken $expected, PhpToken $token): void
    {
        self::assertEquals($expected, (new ConstantEncapsedStringNormalizer())->normalizeToken([], $token));
    }

    public function normalizeProvider(): array
    {
        return [
            [
                'expected' => new PhpToken(T_CONSTANT_ENCAPSED_STRING, "'constant_encapsed_string'", 10, 20),
                'token' => new PhpToken(T_CONSTANT_ENCAPSED_STRING, 'lorem ipsum', 10, 20),
            ],
            [
                'expected' => new PhpToken(T_CONSTANT_ENCAPSED_STRING, "'constant_encapsed_string'", 199, 71),
                'token' => new PhpToken(T_CONSTANT_ENCAPSED_STRING, '', 199, 71),
            ],
        ];
    }
}