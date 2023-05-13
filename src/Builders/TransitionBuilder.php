<?php

namespace Automata\Builders;

use Automata\Event;
use Automata\Interfaces\Builder;
use Automata\Interfaces\States\State;
use Automata\Traits\ResolveInstance;
use Automata\Transition;

final class TransitionBuilder implements Builder
{
    use ResolveInstance;

    private readonly Transition $transition;

    public function __construct(string $eventName, ?State $source = null)
    {
        $this->transition = new Transition(new Event($eventName));
        $source && $this->source($source);
    }

    public static function make(string $eventName, ?State $source = null): TransitionBuilder
    {
        return new self($eventName, $source);
    }

    public function source(State|StateBuilder|string $state): TransitionBuilder
    {
        /** @var \Automata\State $state */
        $state = self::resolveInstance($state, \Automata\State::class);
        $this->transition->source = $state;

        return $this;
    }

    public function target(State|StateBuilder|string $state): TransitionBuilder
    {
        /** @var \Automata\State $state */
        $state = self::resolveInstance($state, \Automata\State::class);
        $this->transition->target = $state;

        return $this;
    }

    public function action(\Closure $callback): TransitionBuilder
    {
        $this->transition->actions[] = $callback;

        return $this;
    }

    /**
     * @param \Closure[] $actions
     */
    public function actions(array $actions): TransitionBuilder
    {
        $this->transition->actions = array_merge($this->transition->actions, $actions);

        return $this;
    }

    public function guard(\Closure $callback, ?string $triggerOnFail = null): TransitionBuilder
    {
        $this->transition->guards[] = $callback;
        $this->transition->triggerOnGuardFail = $triggerOnFail;

        return $this;
    }

    /**
     * @param \Closure[] $callbacks
     */
    public function guards(array $callbacks, ?string $triggerOnFail = null): TransitionBuilder
    {
        $this->transition->guards = array_merge($this->transition->guards, $callbacks);
        $this->transition->triggerOnGuardFail = $triggerOnFail;

        return $this;
    }

    public function triggerOnGuardFail(string $callback): TransitionBuilder
    {
        $this->transition->triggerOnGuardFail = $callback;

        return $this;
    }

    public function build(): Transition
    {
        return $this->transition;
    }
}
