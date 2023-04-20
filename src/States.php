<?php

namespace Automata;

use Automata\Abstracts\Collection;
use Automata\Interfaces\States\State;

/**
 * @property State[] $items
 */
final class States extends Collection
{
    /**
     * @param  State  $entity
     */
    public function add($entity): static
    {
        $this->getItem($entity);

        $this->items[$entity->getName()] = $entity;

        return $this;
    }

    public function get(string $string): State
    {
        return $this->items[$string];
    }

    protected function getEntity(): string
    {
        return State::class;
    }
}
