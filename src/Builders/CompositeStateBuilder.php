<?php

namespace Automata\Builders;

use Automata\CompositeState;
use Automata\CompositeStates;
use Automata\Interfaces\Builder;
use Automata\State;
use Automata\StateMachine;
use Automata\States;

class CompositeStateBuilder implements Builder
{
    private CompositeState $state;

    public function __construct(string $name)
    {
        $this->state = new CompositeState($name);
    }

    public static function make(string $name): CompositeStateBuilder
    {
        return new self($name);
    }

    public function name(string $name): CompositeStateBuilder
    {
        $this->state->name = $name;
        return $this;
    }

    public function timeout(int $seconds): CompositeStateBuilder
    {
        $this->state->timeout = $seconds;
        return $this;
    }

    public function build(): CompositeState
    {
        return $this->state;
    }

    public function triggerOnSuccess(string $event): CompositeStateBuilder
    {
        $this->state->triggerOnSuccess = $event;
        return $this;
    }

    public function triggerOnFail(string $event): CompositeStateBuilder
    {
        $this->state->triggerOnFail = $event;
        return $this;
    }

    public function states(StateMachine|CompositeStates ...$states): CompositeStateBuilder
    {
        $this->state->states = is_array($states) ? new CompositeStates($states) : $states;
        return $this;
    }
}
