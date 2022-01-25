<?php
declare(strict_types=1);

namespace LeoVie\PhpTokenNormalize\Tests\Functional;

use LeoVie\PhpTokenNormalize\Service\TokenSequenceNormalizer;
use PHPUnit\Framework\TestCase;

class FrameworkTest extends TestCase
{
    public function testServiceWiring(): void
    {
        $kernel = new TestingKernel('test', true);
        $kernel->boot();
        $tokenSequenceNormalizer = $kernel->getContainer()->get(TokenSequenceNormalizer::class);

        self::assertInstanceOf(TokenSequenceNormalizer::class, $tokenSequenceNormalizer);
    }
}