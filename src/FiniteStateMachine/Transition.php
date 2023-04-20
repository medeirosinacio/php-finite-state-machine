<?php

namespace Medeirosinacio\FiniteStateMachine;

use Medeirosinacio\FiniteStateMachine\Interfaces\State;

final class Transition
{
    public State $to;

    private array $events = [];

    private array $rules = [];

    public function __construct(public readonly State $from)
    {
    }

    public function event(callable ...$events): self
    {
        $this->events = $events;

        return $this;
    }

    public function rules(callable ...$rules): self
    {
        $this->rules = $rules;

        return $this;
    }

    public function addRule(callable $rule): self
    {
        $this->rules[] = $rule;

        return $this;
    }

    public function getRules(): iterable
    {
        return $this->rules;
    }

    public function getEvents(): iterable
    {
        return $this->events;
    }
}
