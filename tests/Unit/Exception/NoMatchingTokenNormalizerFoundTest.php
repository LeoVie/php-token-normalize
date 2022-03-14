<?php

declare(strict_types=1);

namespace LeoVie\PhpTokenNormalize\Tests\Unit\Exception;

use LeoVie\PhpTokenNormalize\Exception\NoMatchingTokenNormalizerFound;
use PHPUnit\Framework\TestCase;

class NoMatchingTokenNormalizerFoundTest extends TestCase
{
    /** @dataProvider createProvider */
    public function testCreate(string $expectedMessage, string $tokenType): void
    {
        self::assertSame($expectedMessage, NoMatchingTokenNormalizerFound::create($tokenType)->getMessage());
    }

    public function createProvider(): array
    {
        return [
            [
                'expected' => 'No matching token normalizer found for token type foo.',
                'tokenType' => 'foo',
            ],
            [
                'expected' => 'No matching token normalizer found for token type bar.',
                'tokenType' => 'bar',
            ],
        ];
    }
}