<?php

namespace Automata;

use Automata\Abstracts\Collection;

/**
 * @method  StateMachine get(string|int $key)
 * @method  StateMachine[] getIterator()
 * @property StateMachine[] $items
 */
final class CompositeStates extends Collection
{
    /**
     * @param StateMachine $entity
     */
    public function add($entity): static
    {
        $this->getItem($entity);

        $this->items[$entity->getUid()] = $entity;

        return $this;
    }

    public function has(int|string|StateMachine $key): bool
    {
        return parent::has($key instanceof StateMachine ? $key->getUid() : $key);
    }

    protected function getEntity(): string
    {
        return StateMachine::class;
    }
}
