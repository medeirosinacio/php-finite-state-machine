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

    public static function makeCompositeStateCompletedEvent(
        string $compositeState,
        ?State $source = null
    ): TransitionBuilder {
        return self::make($compositeState.'_completed', $source);
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

    public function action(\Closure|callable $callback): TransitionBuilder
    {
        $this->transition->actions[] = $callback instanceof \Closure ? $callback : fn () => $callback();;

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

    public function guard(\Closure|callable $callback, ?string $triggerOnFail = null): TransitionBuilder
    {
        $this->transition->guards[] = $callback instanceof \Closure ? $callback : fn () => $callback();
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

    public function triggerOnGuardFail(string $eventName): TransitionBuilder
    {
        $this->transition->triggerOnGuardFail = $eventName;

        return $this;
    }

    public function build(): Transition
    {
        return $this->transition;
    }
}
