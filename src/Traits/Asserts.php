<?php

namespace Automata\Traits;

use Automata\Exceptions\MissingInitialStateException;
use Automata\Exceptions\StateMachineAlreadyShutdownException;
use Automata\Exceptions\StateMachineAlreadyStartedException;
use Automata\Exceptions\StateMachineNotHasComplexStateableException;
use Automata\Exceptions\StateMachineNotHasContextStateableException;
use Automata\Interfaces\States\ComplexState;
use Automata\Interfaces\States\ComplexStateable;
use Automata\Interfaces\States\Stateable;

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