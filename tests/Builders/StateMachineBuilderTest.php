<?php

namespace Automata\Tests\Builders;

use Automata\Builders\StateBuilder;
use Automata\Builders\StateBuilder as StateBuilderAlias;
use Automata\Builders\StateMachineBuilder;
use Automata\Builders\TransitionBuilder as Transition;
use Automata\State;
use Automata\StateMachine;
use Automata\Tests\Mocks\States\StateMock;

it('can be add enum of state in state machine', function () {

    $state = StateMock::Init;

    $stateMachine = StateMachineBuilder::configure()
        ->addState($state)
        ->build();

    $states = $stateMachine->states;

    expect($state)->toEqual($states->get(StateMock::Init->getName()));
    expect($state->getName())->toEqual($states->get(StateMock::Init->getName())->getName());
});

it('can be add multiples states in state machine', function () {

    $stateList = [
        StateMock::Init,
        StateMock::Pendent,
        StateMock::Completed,
    ];

    $stateMachine = StateMachineBuilder::configure()->addStates($stateList)->build();
    $states = array_values($stateMachine->states->toArray());

    expect($states)->toEqual($stateList);
    expect(array_shift($states))->toEqual($stateList[0]);
});

it('test state machine add multiple state', function () {

    $stateMachine = StateMachineBuilder::configure()
        ->addState(name: StateMock::New)
        ->addState(name: StateMock::Pendent)
        ->addState(name: StateMock::Completed);

    expect(array_values($stateMachine->build()->states->toArray()))
        ->toEqual([StateMock::New, StateMock::Pendent, StateMock::Completed]);
});

it('test state machine builder', function () {

    $stateMachine = StateMachineBuilder::configure()
        ->addState(name: StateMock::New)
        ->build();

    expect($stateMachine)->toBeInstanceOf(StateMachine::class);
});

it('should add a state to the state machine', function () {
    $builder = StateMachineBuilder::configure();
    $builder->addState('state1');
    $stateMachine = $builder->build();

    expect($stateMachine->states->count())->toBe(1);
    expect($stateMachine->states->get('state1'))->toBeInstanceOf(State::class);
});

it('should build a state machine', function () {
    $stateMachine = StateMachineBuilder::configure('test-machine')
        ->addState('state-1')
        ->addState(StateBuilder::make('state-2'))
        ->addTransitions([
            Transition::make('event-1')->source(StateMock::Init)->target('state-2'),
            Transition::make('event-2')->source('state-2')->target('state-1'),
        ])
        ->build();

    expect($stateMachine)->toBeInstanceOf(\Automata\StateMachine::class);
    expect($stateMachine->name)->toBe('test-machine');
    expect($stateMachine->states->count())->toBe(2);
    expect($stateMachine->transitions->count())->toBe(2);
});

it('should add states to state machine', function () {
    $stateMachine = StateMachineBuilder::configure()
        ->addState('state-1')
        ->addStates([
            'state-2',
            StateBuilderAlias::make('state-3'),
        ])
        ->build();

    expect($stateMachine->states->count())->toBe(3);
});

it('should add transitions to state machine', function () {
    $stateMachine = StateMachineBuilder::configure()
        ->addTransitions([
            Transition::make('event-1')->source('state-1')->target('state-2'),
            Transition::make('event-2')->source('state-2')->target('state-1'),
        ])
        ->addTransition(Transition::make('event-3')->source('state-1')->target('state-3'))
        ->build();

    expect($stateMachine->transitions->count())->toBe(3);
});

it('should set the name of the state machine', function () {
    $stateMachine = StateMachineBuilder::configure()
        ->name('test-machine')
        ->build();

    expect($stateMachine->name)->toBe('test-machine');
});
