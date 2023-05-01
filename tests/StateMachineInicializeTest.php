<?php

namespace Automata\Tests;

use Automata\Builders\StateBuilder;
use Automata\Exceptions\StateMachineAlreadyStartedException;
use Automata\Exceptions\StateMachineNotHasContextStateableException;
use Automata\Interfaces\States\Stateable;
use Automata\StateMachine;
use Automata\States;
use Automata\Transition;
use Automata\Transitions;
use Mockery;

it('can configure a new state machine', function () {
    $stateMachine = StateMachine::configure('Test Machine')->build();
    expect($stateMachine)->toBeInstanceOf(StateMachine::class);
    expect($stateMachine->name)->toBe('Test Machine');
    expect($stateMachine->states)->toBeInstanceOf(States::class);
    expect($stateMachine->transitions)->toBeInstanceOf(Transitions::class);
});

it('can inicialize the state machine', function () {
    $stateMachine = StateMachine::configure('Test Machine')->build();
    $mockStateable = Mockery::mock(Stateable::class);

    $stateMachine->inicialize($mockStateable);
    expect($stateMachine->stateable)->toBe($mockStateable);
});

it('throw an exception if the machine is already enabled', function () {
    $stateMachine = StateMachine::configure('Test Machine')->build();
    $mockStateable = Mockery::mock(Stateable::class);
    $stateMachine->inicialize($mockStateable);
    expect(fn () => $stateMachine->inicialize())->toThrow(StateMachineAlreadyStartedException::class);
});

it('throw an exception if the machine does not have a context stateable', function () {
    $stateMachine = StateMachine::configure('Test Machine')->build();
    expect(fn () => $stateMachine->inicialize())->toThrow(StateMachineNotHasContextStateableException::class);
});

it('can add a state to the state machine', function () {
    $state = StateBuilder::make('pending')->build();
    $stateMachine = StateMachine::configure('Test Machine')->addState($state)->build();
    expect($stateMachine->states->get('pending'))->toBe($state);
});

it('can add multiple states to the state machine', function () {
    $states = [
        StateBuilder::make('pending')->build(),
        StateBuilder::make('processing')->build(),
        StateBuilder::make('completed')->build(),
    ];

    $stateMachine = StateMachine::configure('Test Machine')->addStates($states)->build();

    expect($stateMachine->states->count())->toBe(3);
    expect(array_values($stateMachine->states->toArray()))->toBe($states);
});

it('can add a transition to the state machine', function () {
    $stateMachine = StateMachine::configure('Test Machine')
        ->addTransition('pending -> processing')
        ->build();

    expect($stateMachine->transitions->count())->toBe(1);
});

it('can add multiple transitions to the state machine', function () {
    $transitions = [
        new Transition('pending -> processing'),
        new Transition('processing -> completed'),
    ];
    $stateMachine = StateMachine::configure('Test Machine')
        ->addTransitions($transitions)->build();

    expect($stateMachine->transitions->count())->toBe(2);
    expect(array_values($stateMachine->transitions->toArray()))->toBe($transitions);
});
