<?php

declare(strict_types=1);

namespace LeoVie\PhpTokenNormalize\Tests\Functional;

use LeoVie\PhpTokenNormalize\PhpTokenNormalizeBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class TestingKernel extends Kernel
{
    public function registerBundles(): array
    {
        return [
            new PhpTokenNormalizeBundle()
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
    }
}