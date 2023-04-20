<?php

namespace Automata\Builders;

use Automata\Interfaces\Builder;
use Automata\Interfaces\States\State;
use Automata\Traits\ResolveInstance;
use Automata\Transition;

final class TransitionBuilder implements Builder
{
    use ResolveInstance;

    private readonly Transition $transition;

    public function __construct(string $name, ?State $source = null)
    {
        $this->transition = new Transition($name);
        $source && $this->source($source);
    }

    public static function make(string $name, ?State $source = null): TransitionBuilder
    {
        return new self($name, $source);
    }

    public function source(State|StateBuilder|string $source): TransitionBuilder
    {
        /** @var \Automata\State $source */
        $source = self::resolveInstance($source, \Automata\State::class);
        $this->transition->source = $source;

        return $this;
    }

    public function target(State|StateBuilder|string $target): TransitionBuilder
    {
        /** @var \Automata\State $target */
        $target = self::resolveInstance($target, \Automata\State::class);
        $this->transition->target = $target;

        return $this;
    }

    public function action(\Closure $callback): TransitionBuilder
    {
        $this->transition->actions[] = $callback;

        return $this;
    }

    /**
     * @param  \Closure[]  $actions
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
     * @param  \Closure[]  $callbacks
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
