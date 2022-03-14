<?php

require_once __DIR__ . '/vendor/autoload.php';

$code = '<?php $a = -10;';

$tokens = PhpToken::tokenize($code);
$tokenSequence = \LeoVie\PhpTokenNormalize\Model\TokenSequence::create($tokens);

$tokenSequenceNormalizer = new \LeoVie\PhpTokenNormalize\Service\TokenSequenceNormalizer(
    new ArrayIterator([
        new \LeoVie\PhpTokenNormalize\TokenNormalizer\ConstantEncapsedStringNormalizer(),
        new \LeoVie\PhpTokenNormalize\TokenNormalizer\DNumberNormalizer(),
        new \LeoVie\PhpTokenNormalize\TokenNormalizer\LNumberNormalizer(),
        new \LeoVie\PhpTokenNormalize\TokenNormalizer\PlusMinusNormalizer(),
        new \LeoVie\PhpTokenNormalize\TokenNormalizer\VariableNormalizer(),
        new \LeoVie\PhpTokenNormalize\TokenNormalizer\NothingToNormalizeNormalizer(),
    ])
);

$normalizedTokenSequence = $tokenSequenceNormalizer->normalizeLevel2($tokenSequence);

foreach ($normalizedTokenSequence->getTokens() as $token) {
    print($token->id . ' -> ' . $token->getTokenName() . ': "' . $token->text . "\"\n");
}