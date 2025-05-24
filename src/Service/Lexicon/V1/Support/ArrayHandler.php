<?php

namespace Blugen\Service\Lexicon\V1\Support;

use ArrayIterator;

class ArrayHandler implements \ArrayAccess, \IteratorAggregate, \Countable
{
    private array $items = [];
    private \Closure $validator;

    public function __construct(array $items, callable $validator)
    {
        $this->validator = $validator;
        $this->setItems($items);
    }

    public function setItems(array $items): void
    {
        foreach ($items as $item) {
            ($this->validator)($item);
        }

        $this->items = $items;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        ($this->validator)($value);

        if ($offset === null) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);
    }

    public function getIterator(): \Traversable
    {
        return new ArrayIterator($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }
}
