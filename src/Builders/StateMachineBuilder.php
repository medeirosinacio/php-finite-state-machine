<?php

namespace Automata\Builders;

use Automata\Interfaces\Builder;
use Automata\Interfaces\States\State;
use Automata\StateMachine;
use Automata\States;
use Automata\Traits\ResolveInstance;
use Automata\Transition;
use Automata\Transitions;

final class StateMachineBuilder implements Builder
{
    use ResolveInstance;

    private readonly StateMachine $stateMachine;

    public function __construct(
        public ?string $name = null
    ) {
        $this->stateMachine = new StateMachine();
        $this->stateMachine->transitions = new Transitions();
        $this->stateMachine->states = new States();
        $name && $this->name($name);
    }

    public static function configure(?string $name = null): static
    {
        return new self($name);
    }

    public function build(): StateMachine
    {
        return $this->stateMachine;
    }

    public function addTransition(Transition|TransitionBuilder|string $event): self
    {
        $this->stateMachine->transitions->add($this->getTransitionInstance($event));

        return $this;
    }

    /**
     * @param  Transition[]|TransitionBuilder[]  $transitions
     */
    public function addTransitions(array $transitions): self
    {
        foreach ($transitions as $transition) {
            $this->addTransition($this->getTransitionInstance($transition));
        }

        return $this;
    }

    /**
     * @param  State[]|StateBuilder[]|string[]  $states
     */
    public function addStates(array $states): self
    {
        foreach ($states as $state) {
            $this->addState($this->getStateInstance($state));
        }

        return $this;
    }

    public function addState(State|StateBuilder|string $name): self
    {
        $this->stateMachine->states->add($this->getStateInstance($name));

        return $this;
    }

    public function name(string $name): StateMachineBuilder
    {
        $this->stateMachine->name = $name;

        return $this;
    }

    private function getStateInstance(State|StateBuilder|string $state): State
    {
        /** @var State $instance */
        $instance = self::resolveInstance($state, \Automata\State::class);

        return $instance;
    }

    private function getTransitionInstance(Transition|TransitionBuilder|string $transition): Transition
    {
        /** @var Transition $instance */
        $instance = self::resolveInstance($transition, Transition::class);

        return $instance;
    }
}
