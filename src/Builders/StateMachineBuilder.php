<?php

namespace Automata\Builders;

use Automata\Interfaces\Builder;
use Automata\Interfaces\States\State;
use Automata\StateMachine;
use Automata\Traits\ResolveInstance;
use Automata\Transition;

final class StateMachineBuilder implements Builder
{
    use ResolveInstance;

    private readonly StateMachine $stateMachine;

    public function __construct(
        public ?string $name = null
    ) {
        $this->stateMachine = new StateMachine(uid: $name);
    }

    public static function configure(?string $uid = null): self
    {
        return new self($uid);
    }

    public function build(): StateMachine
    {
        return $this->stateMachine;
    }

    public function addTransition(Transition|TransitionBuilder|string $event): self
    {
        $this->stateMachine->getTransitions()->add($this->getTransitionInstance($event));

        return $this;
    }

    /**
     * @param Transition[]|TransitionBuilder[] $transitions
     */
    public function addTransitions(array $transitions): self
    {
        foreach ($transitions as $transition) {
            $this->addTransition($this->getTransitionInstance($transition));
        }

        return $this;
    }

    /**
     * @param State[]|StateBuilder[]|string[] $states
     */
    public function addStates(array $states): self
    {
        foreach ($states as $state) {
            $this->addState(name: $this->getStateInstance($state));
        }

        return $this;
    }

    public function addState(State|StateBuilder|string $name): self
    {
        $this->stateMachine->getStates()->add(entity: $this->getStateInstance($name));

        return $this;
    }

    public function name(string $uid): StateMachineBuilder
    {
        $this->stateMachine->setUid($uid);

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

    public function addInitialState(State|StateBuilder|string $state): StateMachineBuilder
    {
        $this->stateMachine->setInitialState(self::resolveInstance($state, \Automata\State::class));

        return $this;
    }

    /**
     * @param State[]|StateBuilder[]|string[] $states
     */
    public function addFinalStates(array $states): self
    {
        foreach ($states as $state) {
            $this->addFinalState(state: $state);
        }

        return $this;
    }

    public function addFinalState(State|StateBuilder|string $state): self
    {
        $this->stateMachine->getFinalStates()->add(self::resolveInstance($state, \Automata\State::class));

        return $this;
    }
}
