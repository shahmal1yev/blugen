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

    public static function get(): ?ContainerInterface
    {
        return self::$container;
    }

    /**
     * Get a service from the container
     */
    public static function getService(string $id)
    {
        if (self::$container === null) {
            throw new \RuntimeException('Container is not initialized');
        }

        return self::$container->get($id);
    }

    /**
     * Get a parameter from the container
     */
    public static function getParameter(string $name)
    {
        if (self::$container === null) {
            throw new \RuntimeException('Container is not initialized');
        }

        return self::$container->getParameter($name);
    }
}
