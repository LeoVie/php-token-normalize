<?php

declare(strict_types=1);

namespace LeoVie\PhpTokenNormalize\Tests\Unit\Exception;

use LeoVie\PhpTokenNormalize\Exception\NoReplacementRegistered;
use PHPUnit\Framework\TestCase;

class NoReplacementRegisteredTest extends TestCase
{
    /** @dataProvider createProvider */
    public function testCreate(string $expectedMessage, string $original): void
    {
        self::assertSame($expectedMessage, NoReplacementRegistered::create($original)->getMessage());
    }

    public function createProvider(): array
    {
        return [
            [
                'expected' => 'No replacement registered for $abc.',
                'original' => '$abc',
            ],
            [
                'expected' => 'No replacement registered for $foo.',
                'original' => '$foo',
            ],
        ];
    }
}