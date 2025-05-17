<?php

namespace Blugen\Service\Lexicon\V1\Factory;

use Blugen\Service\Lexicon\GeneratorInterface;
use Blugen\Service\Lexicon\V1\ComponentGenerator\Field\ArrayComponentGenerator;
use Blugen\Service\Lexicon\V1\ComponentGenerator\Field\BooleanComponentGenerator;
use Blugen\Service\Lexicon\V1\ComponentGenerator\Field\BytesComponentGenerator;
use Blugen\Service\Lexicon\V1\ComponentGenerator\Field\CidLinkComponentGenerator;
use Blugen\Service\Lexicon\V1\ComponentGenerator\Field\IntegerComponentGenerator;
use Blugen\Service\Lexicon\V1\ComponentGenerator\Field\NullComponentGenerator;
use Blugen\Service\Lexicon\V1\ComponentGenerator\Field\ObjectComponentGenerator;
use Blugen\Service\Lexicon\V1\ComponentGenerator\Field\ParamsComponentGenerator;
use Blugen\Service\Lexicon\V1\ComponentGenerator\Field\RefComponentGenerator;
use Blugen\Service\Lexicon\V1\ComponentGenerator\Field\StringComponentGenerator;
use Blugen\Service\Lexicon\V1\ComponentGenerator\Field\UnionComponentGenerator;
use Blugen\Service\Lexicon\V1\ComponentGenerator\Field\UnknownComponentGenerator;
use Blugen\Service\Lexicon\V1\Property;
use Nette\PhpGenerator\ClassType;
use Blugen\Service\Lexicon\V1\ComponentGenerator\Field\BlobComponentGenerator;
use Blugen\Service\Lexicon\V1\ComponentGenerator\Field\TokenComponentGenerator;

class ComponentGeneratorFactory
{
    public static function create(ClassType $class, Property $property): GeneratorInterface
    {
        $type = $property->schema()->type();

        return match($type) {
            'string' => new StringComponentGenerator($class, $property),
            'object' => new ObjectComponentGenerator($class, $property),
            'integer' => new IntegerComponentGenerator($class, $property),
            'boolean' => new BooleanComponentGenerator($class, $property),
            'array' => new ArrayComponentGenerator($class, $property),
            'bytes' => new BytesComponentGenerator($class, $property),
            'params' => new ParamsComponentGenerator($class, $property),
            'null' => new NullComponentGenerator($class, $property),
            'cid-link' => new CidLinkComponentGenerator($class, $property),
            'blob' => new BlobComponentGenerator($class, $property),
            'token' => new TokenComponentGenerator($class, $property),
            'ref' => new RefComponentGenerator($class, $property),
            'union' => new UnionComponentGenerator($class, $property),
            'unknown' => new UnknownComponentGenerator($class, $property),
            default => throw new \RuntimeException("Unsupported type: $type"),
        };
    }
}
