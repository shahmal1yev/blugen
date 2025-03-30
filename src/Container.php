<?php

namespace Blugen;

use Symfony\Component\DependencyInjection\ContainerBuilder;

class Container
{
    private static ?ContainerBuilder $instance = null;

    private function __construct()
    {}

    public function __clone(): never
    {
        throw new \BadMethodCallException('Cloning is not allowed.');
    }

    public function __wakeup()
    {
        throw new \BadMethodCallException('Unserializing is not allowed.');
    }

    public static function get(): ContainerBuilder
    {
        if (null === static::$instance) {
            throw new \RuntimeException("Container has not been initialized.");
        }

        return static::$instance;
    }

    public static function set(ContainerBuilder $container): void
    {
        static::$instance = $container;
    }
}
