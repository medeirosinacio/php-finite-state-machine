<?php

namespace Automata;

use Automata\Abstracts\Collection;
use Automata\Interfaces\States\State;

/**
 * @method  State get(string|int $key)
 * @property State[] $items
 */
final class States extends Collection
{
    /**
     * @param State $entity
     */
    public function add($entity): static
    {
        $this->getItem($entity);

        $this->items[$entity->getName()] = $entity;

        return $this;
    }

    public function has(int|string|State $key): bool
    {
        return parent::has($key instanceof State ? $key->getName() : $key);
    }

    protected function getEntity(): string
    {
        return State::class;
    }
}
