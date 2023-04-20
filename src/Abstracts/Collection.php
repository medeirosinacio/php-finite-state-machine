<?php

namespace Automata\Abstracts;

/**
 * @phpstan-consistent-constructor
 */
abstract class Collection
{
    /** @var array<int|string, mixed> */
    protected array $items = [];

    abstract protected function getEntity(): string;

    /**
     * @param  array<int|string, mixed>  $items
     */
    public function __construct(...$items)
    {
        $this->items = array_map(fn ($entity) => $this->getItem($entity), $items);
    }

    /**
     * @param  array<int|string, mixed>  $items
     */
    public static function make(...$items): static
    {
        return new static(...$items);
    }

    public function add(mixed $entity): static
    {
        $this->items[] = $this->getItem($entity);

        return $this;
    }

    public function count(): int
    {
        return count($this->items);
    }

    /**
     * @return \ArrayIterator<int|string, mixed>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * @return  array<int|string, mixed> $items
     */
    public function toArray(): array
    {
        return $this->items;
    }

    protected function getItem(mixed $entity): mixed
    {
        return (empty($this->getEntity()) || $entity instanceof ($this->getEntity())) ? $entity : throw new \TypeError($this->getEntity());
    }
}
