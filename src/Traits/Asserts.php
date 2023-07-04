<?php

namespace Automata\Traits;

use Automata\Exceptions\MissingInitialStateException;
use Automata\Exceptions\MissingFinalStateException;
use Automata\Exceptions\MissingStateException;
use Automata\Exceptions\StateMachineAlreadyShutdownException;
use Automata\Exceptions\StateMachineAlreadyStartedException;
use Automata\Exceptions\StateMachineNotHasComplexStateableException;
use Automata\Exceptions\StateMachineNotHasContextStateableException;
use Automata\Interfaces\Stateable\ComplexStateable;
use Automata\Interfaces\Stateable\Stateable;
use Automata\Interfaces\States\ComplexState;
use Automata\Interfaces\States\State;

trait Asserts
{
    protected function assertMachineDisabled(): void
    {
        $this->initialized() && throw new StateMachineAlreadyStartedException();
    }

    protected function assertMachineEnabled(bool $runClock = true): void
    {
        if ($runClock) {
            $this->timeoutStateChecker();
        }

        is_false($this->initialized()) && throw new StateMachineAlreadyShutdownException();
    }

    protected function assertInitialState(): void
    {
        is_null($this->initialState) && throw new MissingInitialStateException();
    }

    protected function assertFinalState(): void
    {
        $this->finalStates->empty() && throw new MissingFinalStateException();
    }

    protected function assertMachineStateableContext(): void
    {
        $this->getStateable() instanceof Stateable === false &&
        throw new StateMachineNotHasContextStateableException();
    }

    protected function assertMachineStateableComplex(): void
    {
        $this->getStateable() instanceof ComplexStateable === false &&
        throw new StateMachineNotHasComplexStateableException();
    }

    protected function assertMachineStateExist(State $state): void
    {
        $this->getStates()->has($state->getName()) || throw new MissingStateException($state, $this->getStates());
    }

    protected function stateIsComplexType(): bool
    {
        return $this->stateable?->getState() instanceof ComplexState;
    }

    protected function stateNotComplexType(): bool
    {
        return !$this->stateIsComplexType();
    }

    protected function stateableIsComplexType(): bool
    {
        return $this->stateable instanceof ComplexStateable;
    }

    protected function stateableNotComplexType(): bool
    {
        return !$this->stateableIsComplexType();
    }

    protected function stateHasTimeout(): bool
    {
        return is_int($this->stateable->getState()->getTimeout());
    }

    protected function stateNotHasTimeout(): bool
    {
        return !$this->stateHasTimeout();
    }
}
