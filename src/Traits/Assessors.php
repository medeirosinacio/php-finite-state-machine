<?php

namespace Automata\Traits;

use Automata\Interfaces\States\State;
use Automata\Interfaces\States\Stateable;
use Automata\States;
use Automata\Transitions;

/**
 * @property ?string $uid
 * @property States $states
 * @property Transitions $transitions
 * @property ?Stateable $stateable
 * @property ?State $initialState
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
        $this->assertMachineDisabled();

        $this->initialState = $initialState;
    }

    public function getCurrentState(): State
    {
        $this->assertMachineEnabled();

        return $this->stateable->getState();
    }
}
