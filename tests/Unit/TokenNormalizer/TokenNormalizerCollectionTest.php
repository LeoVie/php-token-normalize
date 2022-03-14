<?php

declare(strict_types=1);

namespace LeoVie\PhpTokenNormalize\Tests\Unit\TokenNormalizer;

use LeoVie\PhpTokenNormalize\Exception\NoMatchingTokenNormalizerFound;
use LeoVie\PhpTokenNormalize\TokenNormalizer\TokenNormalizer;
use LeoVie\PhpTokenNormalize\TokenNormalizer\TokenNormalizerCollection;
use PHPUnit\Framework\TestCase;

class TokenNormalizerCollectionTest extends TestCase
{
    /** @dataProvider getAllProvider */
    public function testGetAll(iterable $tokenNormalizers): void
    {
        self::assertSame(
            $tokenNormalizers,
            (new TokenNormalizerCollection($tokenNormalizers))->getAll()
        );
    }

    public function getAllProvider(): array
    {
        return [
            [new \ArrayIterator([
                $this->createMock(TokenNormalizer::class)
            ])],
            [new \ArrayIterator([
                $this->createMock(TokenNormalizer::class),
                $this->createMock(TokenNormalizer::class),
            ])],
        ];
    }

    public function testWalk(): void
    {
        $tokenNormalizers = [
            $this->createMock(TokenNormalizer::class),
            $this->createMock(TokenNormalizer::class),
        ];
        foreach ($tokenNormalizers as $tokenNormalizer) {
            $tokenNormalizer->expects(self::once())->method('reset');
        }

        (new TokenNormalizerCollection($tokenNormalizers))->walk(
            fn(TokenNormalizer $tn) => $tn->reset()
        );
    }

    /** @dataProvider findMatchingProvider */
    public function testFindMatching(TokenNormalizer $expected, array $tokenNormalizers, \PhpToken $token): void
    {
        self::assertSame(
            $expected,
            (new TokenNormalizerCollection($tokenNormalizers))
                ->findMatching($token)
        );
    }

    public function findMatchingProvider(): array
    {
        $variableNormalizer = $this->mockTokenNormalizer(T_VARIABLE);
        $lNumberNormalizer = $this->mockTokenNormalizer(T_LNUMBER);

        $tokenNormalizers = [$variableNormalizer, $lNumberNormalizer];

        return [
            'T_VARIABLE' => [
                $variableNormalizer,
                $tokenNormalizers,
                new \PhpToken(T_VARIABLE, '$x')
            ],
            'T_LNUMBER' => [
                $lNumberNormalizer,
                $tokenNormalizers,
                new \PhpToken(T_LNUMBER, '10')
            ],
        ];
    }

    private function mockTokenNormalizer(int $supportedId): TokenNormalizer
    {
        $tokenNormalizer = $this->createMock(TokenNormalizer::class);
        $tokenNormalizer->method('supports')->willReturnCallback(
            fn(\PhpToken $token) => $token->id === $supportedId
        );

        return $tokenNormalizer;
    }

    public function testFindMatchingThrows(): void
    {
        self::expectException(NoMatchingTokenNormalizerFound::class);

        (new TokenNormalizerCollection([]))
            ->findMatching(new \PhpToken(T_DNUMBER, '1.0'));
    }
}