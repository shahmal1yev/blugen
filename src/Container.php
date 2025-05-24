<?php

namespace Blugen;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Simple container accessor class
 */
class Container
{
    private static ?ContainerInterface $container = null;

    public static function set(ContainerInterface $container): void
    {
        self::$container = $container;
    }

    public static function get(): ContainerInterface
    {
        if (null === self::$container) {
            throw new \LogicException('Container has not been set yet.');
        }

        return self::$container;
    }
}
