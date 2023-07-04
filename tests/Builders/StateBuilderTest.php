<?php

namespace Automata\Tests\Builders;

use Automata\Builders\StateBuilder;
use Automata\State;

it('should create a new state builder instance', function () {
    $builder = new StateBuilder('test');

    expect($builder)->toBeInstanceOf(StateBuilder::class);
});

it('should create a new state builder instance with a name', function () {
    $builder = new StateBuilder('state1');

    expect($builder)->toBeInstanceOf(StateBuilder::class);
});

it('should create a new state builder instance with make method', function () {
    $builder = StateBuilder::make('state1');

    expect($builder)->toBeInstanceOf(StateBuilder::class);
});

it('should set the state name', function () {
    $stateName = 'state1';
    $builder = StateBuilder::make($stateName);
    $state = $builder->build();

    expect($state)->toBeInstanceOf(State::class);
    expect($state->getName())->toBe($stateName);
});

it('should set the state action', function () {
    $action = function () {
    };
    $builder = StateBuilder::make('state1');
    $state = $builder->action($action)->build();

    expect($state)->toBeInstanceOf(State::class);
    expect($state->getAction())->toBeInstanceOf(\Closure::class);
});

it('should set the state timeout', function () {
    $timeout = 60;
    $builder = StateBuilder::make('state1');
    $state = $builder->timeout($timeout)->build();

    expect($state)->toBeInstanceOf(State::class);
    expect($state->getTimeout())->toBe($timeout);
});

it('should set the state trigger', function () {
    $trigger = 'event1';
    $builder = StateBuilder::make('state1');
    $state = $builder->trigger($trigger)->build();

    expect($state)->toBeInstanceOf(State::class);
    expect($state->getTrigger())->toBe($trigger);
});

it('should build the state', function () {
    $builder = StateBuilder::make('state1');
    $state = $builder->build();

    expect($state)->toBeInstanceOf(State::class);
});
