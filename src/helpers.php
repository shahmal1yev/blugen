<?php

function container(): Symfony\Component\DependencyInjection\ContainerBuilder {
    return \Blugen\Container::get();
}

function toPascalCase(string $input): string
{
    return implode('', array_map('ucfirst', explode(' ', strtolower(
        preg_replace('/[-_]|(?<=[a-z])(?=[A-Z])/', ' ', $input)
    ))));
}
