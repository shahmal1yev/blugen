<?php

namespace Blugen\Service\Lexicon\V1\Resolver;

class NsidResolver
{
    public static function namespace(string $nsid): string
    {
        [$nsid, $fragment] = static::parts($nsid);

        $nsidParts = explode(".", $nsid);
        $namespace = implode("\\", array_map('ucwords', $nsidParts));

        if ($fragment) {
            $namespace .= "\\" . ucwords($fragment);
        }

        return $namespace;
    }

    private static function parts(string $nsid): array
    {
        $parts = explode("#", $nsid, 2);
        $nsid = current($parts);
        $fragment = next($parts) ?? null;

        return [$nsid, $fragment];
    }
}
