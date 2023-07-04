<?php

namespace Automata\Traits;

use Automata\Interfaces\Stateable\Stateable;
use Automata\Interfaces\States\ComplexState;
use Automata\Interfaces\States\CompositeState;
use Automata\Interfaces\States\State;
use Automata\States;
use Automata\Transitions;

/**
 * @property ?string $uid
 * @property States $states
 * @property Transitions $transitions
 * @property ?Stateable $stateable
 * @property ?State $initialState
 * @property ?States $finalStates
 */
trait Assessors
{
    public function setUid(string $uid): void
    {
        $this->uid = $uid;
    }

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function getStates(): States
    {
        $this->assertMachineDisabled();

        return $this->states;
    }

    public function getFinalStates(): States
    {
        $this->assertMachineDisabled();

        return $this->finalStates;
    }

    public function setFinalStates(States $states): void
    {
        $this->assertMachineDisabled();

        $this->finalStates = $states;
    }

    public function setFinalState(State $state): void
    {
        $this->assertMachineDisabled();

        $this->finalStates->add($state);
    }

    public function setStates(States $states): void
    {
        $this->assertMachineDisabled();

        $this->states = $states;
    }

    public function getTransitions(): Transitions
    {
        $this->assertMachineDisabled();

        return $this->transitions;
    }

    public function setTransitions(Transitions $transitions): void
    {
        $this->assertMachineDisabled();

        $this->transitions = $transitions;
    }

    public function getStateable(): ?Stateable
    {
        return $this->stateable;
    }

    public function setStateable(?Stateable $stateable): void
    {
        $this->assertMachineDisabled();

        $this->stateable ??= $stateable;
    }

    public function getInitialState(): ?State
    {
        return $this->initialState;
    }

    public function setInitialState(?State $initialState): void
    {
        $this->initialState = $initialState;
    }

    public function getCurrentState(): State|ComplexState|CompositeState
    {
        $this->assertMachineEnabled();

        return $this->stateable->getState();
    }

    protected function getState(State|string $state): State
    {
        $this->assertMachineStateExist($state);

        return $this->states->get($state instanceof State ? $state->getName() : $state);
    }

    protected function hasState(State|string $state): bool
    {
        return $this->states->has($state instanceof State ? $state->getName() : $state);
    }
}
