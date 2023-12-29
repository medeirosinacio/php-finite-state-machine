<?php

namespace Automata;


/**
 * @see \Automata\Interfaces\States\State
 * @see \Automata\Interfaces\States\ComplexState
 *
 * {@inheritDoc}
 */
final class CompositeState implements Interfaces\States\CompositeState
{
    private bool $isAsync = false;

    public function __construct(
        public string $name,
        public CompositeStates $states,
        public ?int $timeout = null,
        public ?string $triggerOnSuccess = null,
        public ?string $triggerOnFail = null,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function resolve(Interfaces\Stateable\CompositeStateable $stateable): void
    {
        foreach ($this->states->getIterator() as $state) {
            if ($state->inicialize($stateable)->isFinalState()) {
                continue;
            }
            return;
        }
    }

    public function isCompleted(): bool
    {
        return $this->states->allFinalStateSuccess();
    }

    public function isFail(): bool
    {
        return !$this->isCompleted() ;
    }

    public function next()
    {
        // TODO: Implement next() method.
    }

    public function getStates(): CompositeStates
    {
        return $this->states;
    }

    public function isAsync(): bool
    {
        return $this->isAsync;
    }

    public function triggerOnSuccess(): string
    {
        return $this->triggerOnSuccess;
    }

    public function triggerOnFail(): string
    {
        return $this->triggerOnSuccess;
    }
}
