<?php

namespace Automata\Tests;

use Automata\Builders\TransitionBuilder;
use Automata\Exceptions\MissingTransitionTriggeredException;
use Automata\Interfaces\Stateable\ComplexStateable;
use Automata\Interfaces\States\ComplexState;
use Automata\Interfaces\States\CompositeState;
use Automata\Stateable;
use Automata\StateMachine;

it('assert change status state complex is completed', function () {

    $stateComplex = \Mockery::mock(CompositeState::class)
        ->shouldReceive('getName')->andReturn('stateComplex')
        ->shouldReceive('isCompleted')->andReturn(true)
        ->shouldReceive('resolve')
        ->getMock();

    $stateMachine = StateMachine::configure('Test')
        ->addInitialState('stateComplex')
        ->addState($stateComplex)
        ->addState('finished')
        ->addTransition(
            TransitionBuilder::makeCompositeStateCompletedEvent('stateComplex')
                ->source('stateComplex')
                ->target('finished')
        )
        ->build()
        ->inicialize(new Stateable());

    expect($stateMachine->getCurrentState()->getName())->toEqual('finished');
});

it('assert not change status state complex is not completed', function () {

    $stateComplex = \Mockery::mock(CompositeState::class)
        ->shouldReceive('getName')->andReturn('stateComplex')
        ->shouldReceive('isCompleted')->andReturn(false)
        ->shouldReceive('resolve')
        ->getMock();

    $stateMachine = StateMachine::configure('Test')
        ->addInitialState('stateComplex')
        ->addState($stateComplex)
        ->addState('finished')
        ->addTransition(
            TransitionBuilder::makeCompositeStateCompletedEvent('stateComplex')
                ->source('stateComplex')
                ->target('finished')
        )
        ->build()
        ->inicialize(new Stateable());

    expect($stateMachine->getCurrentState()->getName())->toEqual('stateComplex');
});

it('assert MissingTransitionTriggeredException', function () {

    $stateComplex = \Mockery::mock(CompositeState::class)
        ->shouldReceive('getName')->andReturn('stateComplex')
        ->shouldReceive('isCompleted')->andReturn(true)
        ->shouldReceive('resolve')
        ->getMock();

    $stateMachine = StateMachine::configure('Test')
        ->addInitialState('stateComplex')
        ->addState($stateComplex)
        ->build();

    expect(fn () => $stateMachine->inicialize(new Stateable()))
        ->toThrow(MissingTransitionTriggeredException::class);
});