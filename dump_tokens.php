<?php

$code = "<?php function foo(): void {} foo(); $bar = 'def';";

$tokens = PhpToken::tokenize($code);

foreach ($tokens as $token) {
    print($token->id . ' -> ' . $token->getTokenName() . ': "' . $token->text . "\"\n");
}