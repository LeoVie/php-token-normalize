<?php

namespace LeoVie\PhpTokenNormalize\Model;

class Token
{
    public const T_MULTIPLY = '*';
    public const T_PLUS = '+';
    public const T_MINUS = '-';
    public const T_EQUAL = '=';

    public static function getId(string $tokenSymbol): int
    {
        return \PhpToken::tokenize('<?php ' . $tokenSymbol)[1]->id;
    }
}