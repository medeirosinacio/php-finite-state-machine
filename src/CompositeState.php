<?php

namespace Automata;

use Automata\Interfaces\Stateable\ComplexStateable;
use Automata\Interfaces\Stateable\Stateable;
use Closure;

/**
 * @see \Automata\Interfaces\States\State
 * @see \Automata\Interfaces\States\ComplexState
 *
 * {@inheritDoc}
 */
final class CompositeState implements Interfaces\States\CompositeState
{
    public function __construct(
        public string $name,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function resolve(Stateable|ComplexStateable|null $stateable): void
    {
        // TODO: Implement resolve() method.
    }

    public function isCompleted(): bool
    {
        // TODO: Implement isCompleted() method.
    }

    public function getSubMachines()
    {
        return [

        ];
    }

    public function next()
    {
        // TODO: Implement next() method.
    }
}
