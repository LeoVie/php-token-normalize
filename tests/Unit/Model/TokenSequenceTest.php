<?php

namespace LeoVie\PhpTokenNormalize\Tests\Unit\Model;

use Generator;
use LeoVie\PhpTokenNormalize\Model\TokenSequence;
use PhpToken;
use PHPUnit\Framework\TestCase;

class TokenSequenceTest extends TestCase
{
    /** @dataProvider getTokensProvider */
    public function testGetTokens(array $expected, TokenSequence $tokenSequence): void
    {
        self::assertSame($expected, $tokenSequence->getTokens());
    }

    public function getTokensProvider(): Generator
    {
        $tokens = [];
        yield 'no tokens' => [$tokens, TokenSequence::create($tokens)];

        $tokens = [
            $this->mockPhpToken(T_PUBLIC, 'public')
        ];
        yield 'one token' => [$tokens, TokenSequence::create($tokens)];

        $tokens = [
            $this->mockPhpToken(T_PUBLIC, 'public'),
            $this->mockPhpToken(T_FUNCTION, 'function'),
        ];
        yield 'multiple tokens' => [$tokens, TokenSequence::create($tokens)];
    }

    /** @dataProvider equalsProvider */
    public function testEquals(bool $expected, TokenSequence $a, TokenSequence $b): void
    {
        self::assertSame($expected, $a->equals($b));
    }

    public function equalsProvider(): array
    {
        return [
            'not equal' => [
                'expected' => false,
                TokenSequence::create([$this->mockPhpToken(T_PUBLIC, 'public')]),
                TokenSequence::create([]),
            ],
            'equal' => [
                'expected' => true,
                TokenSequence::create([$this->mockPhpToken(T_PUBLIC, 'public')]),
                TokenSequence::create([$this->mockPhpToken(T_PUBLIC, 'public')]),
            ],
        ];
    }

    /** @dataProvider lengthProvider */
    public function testLength(int $expected, TokenSequence $tokenSequence): void
    {
        self::assertSame($expected, $tokenSequence->length());
    }

    public function lengthProvider(): array
    {
        return [
            'empty' => [
                'expected' => 0,
                TokenSequence::create([]),
            ],
            'non empty' => [
                'expected' => 3,
                TokenSequence::create([
                    $this->mockPhpToken(T_PUBLIC, 'public'),
                    $this->mockPhpToken(T_PUBLIC, 'public'),
                    $this->mockPhpToken(T_PUBLIC, 'public'),
                ]),
            ],
        ];
    }

    /** @dataProvider isEmptyProvider */
    public function testIsEmpty(bool $expected, TokenSequence $tokenSequence): void
    {
        self::assertSame($expected, $tokenSequence->isEmpty());
    }

    public function isEmptyProvider(): array
    {
        return [
            'empty' => [
                'expected' => true,
                TokenSequence::create([]),
            ],
            'non empty' => [
                'expected' => false,
                TokenSequence::create([$this->mockPhpToken(T_PUBLIC, 'public'),]),
            ],
        ];
    }

    /** @dataProvider withoutAccessModifiersProvider */
    public function testWithoutAccessModifiers(array $expected, TokenSequence $tokenSequence): void
    {
        self::assertEquals($expected, $tokenSequence->withoutAccessModifiers()->filter()->getTokens());
    }

    public function withoutAccessModifiersProvider(): Generator
    {
        yield 'no tokens' => [
            'expected' => [],
            TokenSequence::create([])
        ];

        yield 'only access modifier tokens' => [
            'expected' => [],
            TokenSequence::create([
                $this->mockPhpToken(T_PUBLIC, 'public'),
                $this->mockPhpToken(T_PROTECTED, 'protected'),
                $this->mockPhpToken(T_PRIVATE, 'private'),
            ])
        ];

        yield 'no access modifier tokens' => [
            'expected' => [
                $this->mockPhpToken(T_OPEN_TAG, '<?php'),
                $this->mockPhpToken(T_FUNCTION, 'function'),
                $this->mockPhpToken(T_CLOSE_TAG, '?>'),
            ],
            TokenSequence::create([
                $this->mockPhpToken(T_OPEN_TAG, '<?php'),
                $this->mockPhpToken(T_FUNCTION, 'function'),
                $this->mockPhpToken(T_CLOSE_TAG, '?>'),
            ])
        ];

        yield 'mixed' => [
            'expected' => [
                $this->mockPhpToken(T_OPEN_TAG, '<?php'),
                $this->mockPhpToken(T_FUNCTION, 'function'),
                $this->mockPhpToken(T_CLOSE_TAG, '?>'),
            ],
            TokenSequence::create([
                $this->mockPhpToken(T_OPEN_TAG, '<?php'),
                $this->mockPhpToken(T_PUBLIC, 'public'),
                $this->mockPhpToken(T_FUNCTION, 'function'),
                $this->mockPhpToken(T_CLOSE_TAG, '?>'),
            ])
        ];
    }

    /** @dataProvider withoutOpenTagProvider */
    public function testWithoutOpenTag(array $expected, TokenSequence $tokenSequence): void
    {
        self::assertEquals($expected, $tokenSequence->withoutOpenTag()->filter()->getTokens());
    }

    public function withoutOpenTagProvider(): Generator
    {
        yield 'no tokens' => [
            'expected' => [],
            TokenSequence::create([])
        ];

        yield 'only open tag token' => [
            'expected' => [],
            TokenSequence::create([
                $this->mockPhpToken(T_OPEN_TAG, '<?php'),
            ])
        ];

        yield 'no open tag token' => [
            'expected' => [
                $this->mockPhpToken(T_FUNCTION, 'function'),
                $this->mockPhpToken(T_CLOSE_TAG, '?>'),
            ],
            TokenSequence::create([
                $this->mockPhpToken(T_FUNCTION, 'function'),
                $this->mockPhpToken(T_CLOSE_TAG, '?>'),
            ])
        ];

        yield 'mixed' => [
            'expected' => [
                $this->mockPhpToken(T_FUNCTION, 'function'),
                $this->mockPhpToken(T_CLOSE_TAG, '?>'),
            ],
            TokenSequence::create([
                $this->mockPhpToken(T_OPEN_TAG, '<?php'),
                $this->mockPhpToken(T_FUNCTION, 'function'),
                $this->mockPhpToken(T_CLOSE_TAG, '?>'),
            ])
        ];
    }

    /** @dataProvider withoutCloseTagProvider */
    public function testWithoutCloseTag(array $expected, TokenSequence $tokenSequence): void
    {
        self::assertEquals($expected, $tokenSequence->withoutCloseTag()->filter()->getTokens());
    }

    public function withoutCloseTagProvider(): Generator
    {
        yield 'no tokens' => [
            'expected' => [],
            TokenSequence::create([])
        ];

        yield 'only close tag token' => [
            'expected' => [],
            TokenSequence::create([
                $this->mockPhpToken(T_CLOSE_TAG, '?>'),
            ])
        ];

        yield 'no close tag token' => [
            'expected' => [
                $this->mockPhpToken(T_OPEN_TAG, '<?php'),
                $this->mockPhpToken(T_FUNCTION, 'function'),
            ],
            TokenSequence::create([
                $this->mockPhpToken(T_OPEN_TAG, '<?php'),
                $this->mockPhpToken(T_FUNCTION, 'function'),
            ])
        ];

        yield 'mixed' => [
            'expected' => [
                $this->mockPhpToken(T_OPEN_TAG, '<?php'),
                $this->mockPhpToken(T_FUNCTION, 'function'),
            ],
            TokenSequence::create([
                $this->mockPhpToken(T_OPEN_TAG, '<?php'),
                $this->mockPhpToken(T_FUNCTION, 'function'),
                $this->mockPhpToken(T_CLOSE_TAG, '?>'),
            ])
        ];
    }

    /** @dataProvider withoutWhitespacesProvider */
    public function testWithoutWhitespaces(array $expected, TokenSequence $tokenSequence): void
    {
        self::assertEquals($expected, $tokenSequence->withoutWhitespaces()->filter()->getTokens());
    }

    public function withoutWhitespacesProvider(): Generator
    {
        yield 'no tokens' => [
            'expected' => [],
            TokenSequence::create([])
        ];

        yield 'only whitespace token' => [
            'expected' => [],
            TokenSequence::create([
                $this->mockPhpToken(T_WHITESPACE, ' '),
            ])
        ];

        yield 'no whitespace token' => [
            'expected' => [
                $this->mockPhpToken(T_OPEN_TAG, '<?php'),
                $this->mockPhpToken(T_FUNCTION, 'function'),
            ],
            TokenSequence::create([
                $this->mockPhpToken(T_OPEN_TAG, '<?php'),
                $this->mockPhpToken(T_FUNCTION, 'function'),
            ])
        ];

        yield 'mixed' => [
            'expected' => [
                $this->mockPhpToken(T_OPEN_TAG, '<?php'),
                $this->mockPhpToken(T_FUNCTION, 'function'),
            ],
            TokenSequence::create([
                $this->mockPhpToken(T_OPEN_TAG, '<?php'),
                $this->mockPhpToken(T_FUNCTION, 'function'),
                $this->mockPhpToken(T_WHITESPACE, ' '),
            ])
        ];
    }

    /** @dataProvider withoutCommentsProvider */
    public function testWithoutComments(array $expected, TokenSequence $tokenSequence): void
    {
        self::assertEquals($expected, $tokenSequence->withoutComments()->filter()->getTokens());
    }

    public function withoutCommentsProvider(): Generator
    {
        yield 'no tokens' => [
            'expected' => [],
            TokenSequence::create([])
        ];

        yield 'only comment token' => [
            'expected' => [],
            TokenSequence::create([
                $this->mockPhpToken(T_COMMENT, '// foo'),
            ])
        ];

        yield 'no comment token' => [
            'expected' => [
                $this->mockPhpToken(T_OPEN_TAG, '<?php'),
                $this->mockPhpToken(T_FUNCTION, 'function'),
            ],
            TokenSequence::create([
                $this->mockPhpToken(T_OPEN_TAG, '<?php'),
                $this->mockPhpToken(T_FUNCTION, 'function'),
            ])
        ];

        yield 'mixed' => [
            'expected' => [
                $this->mockPhpToken(T_OPEN_TAG, '<?php'),
                $this->mockPhpToken(T_FUNCTION, 'function'),
            ],
            TokenSequence::create([
                $this->mockPhpToken(T_OPEN_TAG, '<?php'),
                $this->mockPhpToken(T_FUNCTION, 'function'),
                $this->mockPhpToken(T_COMMENT, '// foo'),
            ])
        ];
    }

    /** @dataProvider withoutDocCommentsProvider */
    public function testWithoutDocComments(array $expected, TokenSequence $tokenSequence): void
    {
        self::assertEquals($expected, $tokenSequence->withoutDocComments()->filter()->getTokens());
    }

    public function withoutDocCommentsProvider(): Generator
    {
        yield 'no tokens' => [
            'expected' => [],
            TokenSequence::create([])
        ];

        yield 'only doc comment token' => [
            'expected' => [],
            TokenSequence::create([
                $this->mockPhpToken(T_DOC_COMMENT, '// foo'),
            ])
        ];

        yield 'no doc comment token' => [
            'expected' => [
                $this->mockPhpToken(T_OPEN_TAG, '<?php'),
                $this->mockPhpToken(T_FUNCTION, 'function'),
            ],
            TokenSequence::create([
                $this->mockPhpToken(T_OPEN_TAG, '<?php'),
                $this->mockPhpToken(T_FUNCTION, 'function'),
            ])
        ];

        yield 'mixed' => [
            'expected' => [
                $this->mockPhpToken(T_OPEN_TAG, '<?php'),
                $this->mockPhpToken(T_FUNCTION, 'function'),
            ],
            TokenSequence::create([
                $this->mockPhpToken(T_OPEN_TAG, '<?php'),
                $this->mockPhpToken(T_FUNCTION, 'function'),
                $this->mockPhpToken(T_DOC_COMMENT, '// foo'),
            ])
        ];
    }

    /** @dataProvider withoutOutputsProvider */
    public function testWithoutOutputs(array $expected, TokenSequence $tokenSequence): void
    {
        self::assertEquals($expected, $tokenSequence->withoutOutputs()->filter()->getTokens());
    }

    public function withoutOutputsProvider(): Generator
    {
        yield 'no tokens' => [
            'expected' => [],
            TokenSequence::create([])
        ];

        yield 'only echo tag' => [
            'expected' => [],
            TokenSequence::create([
                $this->mockPhpToken(T_ECHO, 'echo "abc"'),
            ])
        ];

        yield 'only print tag' => [
            'expected' => [],
            TokenSequence::create([
                $this->mockPhpToken(T_PRINT, 'print("abc")'),
            ])
        ];

        yield 'only echo and print tag' => [
            'expected' => [],
            TokenSequence::create([
                $this->mockPhpToken(T_ECHO, 'echo "abc"'),
                $this->mockPhpToken(T_PRINT, 'print("abc")'),
            ])
        ];

        yield 'no output tokens' => [
            'expected' => [
                $this->mockPhpToken(T_OPEN_TAG, '<?php'),
                $this->mockPhpToken(T_FUNCTION, 'function'),
            ],
            TokenSequence::create([
                $this->mockPhpToken(T_OPEN_TAG, '<?php'),
                $this->mockPhpToken(T_FUNCTION, 'function'),
            ])
        ];

        yield 'mixed' => [
            'expected' => [
                $this->mockPhpToken(T_OPEN_TAG, '<?php'),
                $this->mockPhpToken(T_FUNCTION, 'function'),
            ],
            TokenSequence::create([
                $this->mockPhpToken(T_OPEN_TAG, '<?php'),
                $this->mockPhpToken(T_PRINT, 'print("abc")'),
                $this->mockPhpToken(T_FUNCTION, 'function'),
                $this->mockPhpToken(T_ECHO, 'echo "abc"'),
            ])
        ];
    }

    private function mockPhpToken(int $type, string $asString): PhpToken
    {
        $phpToken = $this->createMock(PhpToken::class);
        $phpToken->id = $type;
        $phpToken->method('__toString')->willReturn($asString);

        return $phpToken;
    }

    /** @dataProvider identityProvider */
    public function testIdentity(string $expected, TokenSequence $tokenSequence): void
    {
        self::assertSame($expected, $tokenSequence->identity());
    }

    public function identityProvider(): Generator
    {
        yield 'no tokens' => [
            'expected' => '',
            TokenSequence::create([])
        ];

        yield 'with tokens' => [
            'expected' => '<?php function',
            TokenSequence::create([
                $this->mockPhpToken(T_OPEN_TAG, '<?php'),
                $this->mockPhpToken(T_FUNCTION, 'function'),
            ])
        ];
    }

    /** @dataProvider toCodeProvider */
    public function testToCode(string $expected, TokenSequence $tokenSequence): void
    {
        self::assertSame($expected, $tokenSequence->toCode());
    }

    public function toCodeProvider(): Generator
    {
        yield 'no tokens' => [
            'expected' => '',
            TokenSequence::create([])
        ];

        yield 'with tokens' => [
            'expected' => '<?php function',
            TokenSequence::create([
                $this->mockPhpToken(T_OPEN_TAG, '<?php'),
                $this->mockPhpToken(T_FUNCTION, 'function'),
            ])
        ];
    }

    /** @dataProvider withoutFunctionStaticProvider */
    public function testWithoutFunctionStatic(array $expected, TokenSequence $tokenSequence): void
    {
        self::assertSame($expected, $tokenSequence->withoutFunctionStatic()->filter()->getTokens());
    }

    public function withoutFunctionStaticProvider(): Generator
    {
        yield 'no tokens' => [
            'expected' => [],
            TokenSequence::create([])
        ];

        $functionToken = $this->mockPhpToken(T_FUNCTION, 'function');
        yield 'static > function' => [
            'expected' => [
                $functionToken
            ],
            TokenSequence::create([
                $this->mockPhpToken(T_STATIC, 'static'),
                $functionToken,
            ])
        ];

        $staticToken = $this->mockPhpToken(T_STATIC, 'static');
        yield 'static' => [
            'expected' => [
                $staticToken
            ],
            TokenSequence::create([
                $staticToken,
            ])
        ];

        $whitespaceToken = $this->mockPhpToken(T_WHITESPACE, ' ');
        $functionToken = $this->mockPhpToken(T_FUNCTION, 'function');
        $staticToken = $this->mockPhpToken(T_STATIC, 'static');
        yield 'static > function > static' => [
            'expected' => [
                $whitespaceToken,
                $functionToken,
                $staticToken
            ],
            TokenSequence::create([
                $this->mockPhpToken(T_STATIC, 'static'),
                $whitespaceToken,
                $functionToken,
                $staticToken,
            ])
        ];

        $staticToken = $this->mockPhpToken(T_STATIC, 'static');
        $variableToken = $this->mockPhpToken(T_VARIABLE, '$a');
        $functionToken = $this->mockPhpToken(T_FUNCTION, 'function');
        yield 'static > variable > function' => [
            'expected' => [
                $staticToken,
                $variableToken,
                $functionToken,
            ],
            TokenSequence::create([
                $staticToken,
                $variableToken,
                $functionToken,
            ])
        ];

        $staticToken = $this->mockPhpToken(T_STATIC, 'static');
        $functionToken = $this->mockPhpToken(T_FUNCTION, 'function');
        yield 'function > static' => [
            'expected' => [
                $functionToken,
                $staticToken,
            ],
            TokenSequence::create([
                $functionToken,
                $staticToken,
            ])
        ];

        $staticToken = $this->mockPhpToken(T_STATIC, 'static');
        $variableToken = $this->mockPhpToken(T_VARIABLE, '$a');
        $functionToken = $this->mockPhpToken(T_FUNCTION, 'function');
        yield 'static > variable > static > function' => [
            'expected' => [
                $staticToken,
                $variableToken,
                $functionToken,
            ],
            TokenSequence::create([
                $staticToken,
                $variableToken,
                $staticToken,
                $functionToken,
            ])
        ];
    }

    /** @dataProvider onlyCommentsProvider */
    public function testOnlyComments(array $expected, TokenSequence $tokenSequence): void
    {
        self::assertSame($expected, $tokenSequence->onlyComments()->filter()->getTokens());
    }

    public function onlyCommentsProvider(): Generator
    {
        yield 'no tokens' => [
            'expected' => [],
            TokenSequence::create([])
        ];

        yield 'function' => [
            'expected' => [],
            TokenSequence::create([$this->mockPhpToken(T_FUNCTION, 'function')])
        ];

        $commentToken = $this->mockPhpToken(T_COMMENT, '// comment');
        yield 'comment' => [
            'expected' => [$commentToken],
            TokenSequence::create([$commentToken])
        ];

        $commentToken = $this->mockPhpToken(T_COMMENT, '// comment');
        yield 'function > comment' => [
            'expected' => [$commentToken],
            TokenSequence::create([
                $this->mockPhpToken(T_FUNCTION, 'function'),
                $commentToken
            ])
        ];
    }

    public function testSerializeAndUnserialize(): void
    {
        $tokenSequence = TokenSequence::create([
            new PhpToken(T_OPEN_TAG, '<?php'),
            new PhpToken(T_FUNCTION, 'function'),
        ]);

        $serialized = $tokenSequence->serialize();
        $unserialized = TokenSequence::createFromSerialized($serialized);

        self::assertEquals($tokenSequence->getTokens(), $unserialized->getTokens());
    }
}