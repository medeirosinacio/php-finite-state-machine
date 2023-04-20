<?php

namespace Automata\Builders;

use Automata\Interfaces\Builder;
use Automata\State;

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

    public function action(\Closure $action): StateBuilder
    {
        $this->state->action = $action;

        return $this;
    }

    public function timeout(int $timeout): StateBuilder
    {
        $this->state->timeout = $timeout;

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
