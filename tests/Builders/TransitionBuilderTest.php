<?php

namespace Automata\Tests\Builders;

use Automata\Builders\TransitionBuilder;
use Automata\Interfaces\States\State;
use Automata\Transition;

it('can build a transition', function () {
    $name = 'transition_name';
    $source = $this->createMock(State::class);
    $target = $this->createMock(State::class);
    $actions = [fn () => null, fn () => null];
    $guards = [fn () => true, fn () => true];
    $triggerOnGuardFail = 'trigger_on_guard_fail';

    $transition = TransitionBuilder::make($name)
        ->source($source)
        ->target($target)
        ->actions($actions)
        ->guards($guards)
        ->triggerOnGuardFail($triggerOnGuardFail)
        ->build();

    expect($transition)->toBeInstanceOf(Transition::class);
    expect((string) $transition->event)->toBe($name);
    expect($transition->source)->toBe($source);
    expect($transition->target)->toBe($target);
    expect($transition->actions)->toBe($actions);
    expect($transition->guards)->toBe($guards);
    expect($transition->triggerOnGuardFail)->toBe($triggerOnGuardFail);
});

it('can build a transition with no source or target states', function () {
    $name = 'transition_name';
    $actions = [fn () => null];
    $guards = [fn () => true];
    $triggerOnGuardFail = 'trigger_on_guard_fail';

    $transition = TransitionBuilder::make($name)
        ->actions($actions)
        ->guards($guards, $triggerOnGuardFail)
        ->build();

    expect($transition)->toBeInstanceOf(Transition::class);
    expect((string) $transition->event)->toBe($name);
    expect($transition->source)->toBeNull();
    expect($transition->target)->toBeNull();
    expect($transition->actions)->toBe($actions);
    expect($transition->guards)->toBe($guards);
    expect($transition->triggerOnGuardFail)->toBe($triggerOnGuardFail);
});

it('can build a transition with no actions or guards', function () {
    $name = 'transition_name';
    $source = $this->createMock(State::class);
    $target = $this->createMock(State::class);

    $transition = TransitionBuilder::make($name)
        ->source($source)
        ->target($target)
        ->build();

    expect($transition)->toBeInstanceOf(Transition::class);
    expect((string) $transition->event)->toBe($name);
    expect($transition->source)->toBe($source);
    expect($transition->target)->toBe($target);
    expect($transition->actions)->toBe([]);
    expect($transition->guards)->toBe([]);
    expect($transition->triggerOnGuardFail)->toBeNull();
});

it('can build a transition with no source', function () {
    $name = 'transition_name';
    $target = $this->createMock(State::class);

    $transition = TransitionBuilder::make($name)
        ->target($target)
        ->build();

    expect($transition)->toBeInstanceOf(Transition::class);
    expect((string) $transition->event)->toBe($name);
    expect($transition->source)->toBeNull();
    expect($transition->target)->toBe($target);
    expect($transition->actions)->toBe([]);
    expect($transition->guards)->toBe([]);
    expect($transition->triggerOnGuardFail)->toBeNull();
});
