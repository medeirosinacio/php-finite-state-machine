<?php

namespace Automata;

use Automata\Abstracts\Collection;
use Automata\Interfaces\States\State;

/**
 * @method  Transition get(string|int $key)
 * @property Transition[] $items
 */
final class Transitions extends Collection
{
    /**
     * @param Transition $entity
     */
    public function add($entity): static
    {
        $this->getItem($entity);

        $this->items[$entity->event->getName()] = $entity;

        return $this;
    }

    protected function getEntity(): string
    {
        return Transition::class;
    }
}
