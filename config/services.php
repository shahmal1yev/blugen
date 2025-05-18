<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Blugen\Config\ConfigManager;
use Composer\Autoload\ClassLoader;
use Symfony\Component\Filesystem\Filesystem;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->public();

    $services->set(ClassLoader::class)->public();
    $services->set(ConfigManager::class)->public();
};
