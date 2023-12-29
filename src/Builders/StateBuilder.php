<?php

namespace Automata\Builders;

use Automata\CompositeState;
use Automata\Interfaces\Builder;
use Automata\State;
use Automata\States;

final readonly class StateBuilder implements Builder
{
    private State $state;

    public function __construct(string $name)
    {
        $this->state = new State($name);
    }

    public static function make(string $name): StateBuilder
    {
        return new self($name);
    }

    public function name(string $name): StateBuilder
    {
        $this->state->name = $name;

        return $this;
    }

    public function action(\Closure|callable $action): StateBuilder
    {
        $this->state->action = $action instanceof \Closure ? $action : fn() => $action();

        return $this;
    }

    public function timeout(int $seconds): StateBuilder
    {
        $this->state->timeout = $seconds;

        return $this;
    }

    public function trigger(string $trigger): StateBuilder
    {
        $this->state->trigger = $trigger;

        return $this;
    }

    public function build(): State
    {
        return $this->state;
    }
}
