<?php

declare(strict_types=1);

namespace LeoVie\PhpTokenNormalize\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class PhpTokenNormalizeExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configDir = new FileLocator(__DIR__ . '/../../config/');

        $loader = new YamlFileLoader($container, $configDir);
        $loader->load('services.yaml');
    }
}