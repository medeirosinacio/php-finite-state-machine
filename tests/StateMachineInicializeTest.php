<?php

namespace Automata\Tests;

use Automata\Builders\StateBuilder;
use Automata\Builders\TransitionBuilder;
use Automata\Exceptions\MissingFinalStateException;
use Automata\Exceptions\MissingInitialStateException;
use Automata\Exceptions\MissingTransitionTriggeredException;
use Automata\Exceptions\StateMachineAlreadyShutdownException;
use Automata\Exceptions\StateMachineAlreadyStartedException;
use Automata\Exceptions\StateMachineNotHasContextStateableException;
use Automata\Interfaces\Stateable\Stateable;
use Automata\Interfaces\States\ComplexState;
use Automata\Interfaces\States\State;
use Automata\StateMachine;
use Automata\States;
use Automata\Tests\Mocks\States\StateMock;
use Automata\Transition;
use Automata\Transitions;
use Mockery;

it('can configure a new state machine', function () {
    $stateMachine = StateMachine::configure('Test Machine')->build();

    expect($stateMachine)->toBeInstanceOf(StateMachine::class);
    expect($stateMachine->getUid())->toBe('Test Machine');
    expect($stateMachine->getStates())->toBeInstanceOf(States::class);
    expect($stateMachine->getTransitions())->toBeInstanceOf(Transitions::class);
});

it('can inicialize the state machine', function () {
    $stateMachine = StateMachine::configure('Test Machine')->build();
    $mockStateable = Mockery::mock(Stateable::class);
    $mockStateable->shouldReceive('getState')->andReturn(StateMock::Init);

    $stateMachine->inicialize($mockStateable);

    expect($stateMachine->getStateable())->toBe($mockStateable);
});

it('throw an exception if the machine is already enabled', function () {
    $stateMachine = StateMachine::configure('Test Machine')->build();
    $mockStateable = Mockery::mock(Stateable::class);
    $mockStateable->shouldReceive('getState')->andReturn(StateMock::Init);

    $stateMachine->inicialize($mockStateable);

    expect(fn() => $stateMachine->inicialize())->toThrow(StateMachineAlreadyStartedException::class);
});

it('throw an exception if the machine is already enabled without initial state', function () {
    $stateMachine = StateMachine::configure('Test Machine')->build();
    $mockStateable = Mockery::mock(Stateable::class);
    $mockStateable->shouldReceive('getState')->andReturn(null);

    expect(fn() => $stateMachine->inicialize($mockStateable))->toThrow(MissingInitialStateException::class);
});

it('throw an exception if the machine does not have a context stateable', function () {
    $stateMachine = StateMachine::configure('Test Machine')->build();

    expect(fn() => $stateMachine->inicialize())->toThrow(StateMachineNotHasContextStateableException::class);
});

it('can add a state to the state machine', function () {
    $state = StateBuilder::make('pending')->build();
    $stateMachine = StateMachine::configure('Test Machine')->addState($state)->build();

    expect($stateMachine->getStates()->get('pending'))->toBe($state);
});

it('can add multiple states to the state machine', function () {
    $states = [
        StateBuilder::make('pending')->build(),
        StateBuilder::make('processing')->build(),
        StateBuilder::make('completed')->build(),
    ];

    $stateMachine = StateMachine::configure('Test Machine')->addStates($states)->build();

    expect($stateMachine->getStates()->count())->toBe(3);
    expect(array_values($stateMachine->getStates()->toArray()))->toBe($states);
});

it('can add a transition to the state machine', function () {
    $stateMachine = StateMachine::configure('Test Machine')
        ->addTransition('pending -> processing')
        ->build();

    expect($stateMachine->getTransitions()->count())->toBe(1);
});

it('can add multiple transitions to the state machine', function () {
    $transitions = [
        new Transition('pending -> processing'),
        new Transition('processing -> completed'),
    ];
    $stateMachine = StateMachine::configure('Test Machine')
        ->addTransitions($transitions)->build();

    expect($stateMachine->getTransitions()->count())->toBe(2);
    expect(array_values($stateMachine->getTransitions()->toArray()))->toBe($transitions);
});

it('can shutdown the state machine', function () {
    $stateMachine = StateMachine::configure('Test Machine')->build();
    $mockStateable = Mockery::mock(Stateable::class);
    $mockStateable->shouldReceive('getState')->andReturn(StateMock::Init);

    $stateMachine->inicialize($mockStateable);
    $stateMachine->shutdown();

    expect($stateMachine->initialized())->toBeFalse();
});

it('throws an exception if the machine is already disabled', function () {
    $stateMachine = StateMachine::configure('Test Machine')->build();
    $mockStateable = Mockery::mock(Stateable::class);
    $mockStateable->shouldReceive('getState')->andReturn(StateMock::Init);

    expect(fn() => $stateMachine->shutdown())->toThrow(StateMachineAlreadyShutdownException::class);
});

it('can get the current state of the machine', function () {
    $stateMachine = StateMachine::configure('Test Machine')->build();
    $mockStateable = Mockery::mock(Stateable::class);
    $mockStateable->shouldReceive('getState')->andReturn(StateMock::Init);

    $stateMachine->inicialize($mockStateable);

    expect($stateMachine->getCurrentState())->toBe(StateMock::Init);
});

it('can add a state and transition to the state machine', function () {
    $stateMachine = StateMachine::configure('Test Machine')
        ->addState(StateBuilder::make('pending')->build())
        ->addTransition('pending -> processing')
        ->build();

    expect($stateMachine->getStates()->count())->toBe(1);
    expect($stateMachine->getTransitions()->count())->toBe(1);
});

it('can add multiple states and transitions to the state machine', function () {
    $states = [
        StateBuilder::make('pending')->build(),
        StateBuilder::make('processing')->build(),
        StateBuilder::make('completed')->build(),
    ];
    $transitions = [
        new Transition('pending -> processing'),
        new Transition('processing -> completed'),
    ];

    $stateMachine = StateMachine::configure('Test Machine')
        ->addStates($states)
        ->addTransitions($transitions)
        ->build();

    expect($stateMachine->getStates()->count())->toBe(3);
    expect($stateMachine->getTransitions()->count())->toBe(2);
});

it('throws MissingTransitionTriggeredException when triggering a non-existent transition', function () {
    $mockStateable = Mockery::mock(Stateable::class);
    $mockStateable->shouldReceive('getState')->andReturn(StateMock::Init);

    $stateMachine = new StateMachine();
    $stateMachine->getTransitions()->add(entity: new Transition('processing'));
    $stateMachine->inicialize(stateable: $mockStateable);

    expect(fn() => $stateMachine->trigger(eventName: 'not_exist'))
        ->toThrow(MissingTransitionTriggeredException::class);
});

it('can configure a new state machine with initial state', function () {
    $mockStateable = new class implements Stateable {

        private ?State $state = null;

        public function getState(): State|ComplexState|null
        {
            return $this->state;
        }

        public function setState(State $state): void
        {
            $this->state = $state;
        }
    };

    $stateMachine = StateMachine::configure('Test Machine')
        ->addInitialState(StateMock::Init)
        ->addState(StateMock::Init)
        ->build()
        ->inicialize($mockStateable);

    expect($stateMachine->getCurrentState()->getName())->toEqual(StateMock::Init->getName());
});

it('can configure a new state machine with final state false', function () {
    $mockStateable = Mockery::mock(Stateable::class);
    $mockStateable->shouldReceive('getState')->andReturn(StateMock::Init);;

    $stateMachine = StateMachine::configure('Test Machine')
        ->addState(StateMock::Init)
        ->addState(StateMock::Completed)
        ->addFinalState(StateMock::Completed)
        ->build()
        ->inicialize($mockStateable);

    expect($stateMachine->isFinalState())->toBeFalse();
});

it('can configure a new state machine with final state true', function () {
    $mockStateable = new class implements Stateable {

        private ?State $state = null;

        public function getState(): State|ComplexState|null
        {
            return $this->state;
        }

        public function setState(State $state): void
        {
            $this->state = $state;
        }
    };

    $stateMachine = StateMachine::configure('Test Machine')
        ->addInitialState(StateMock::Init)
        ->addState(StateMock::Init->trigger('created'))
        ->addState(StateMock::Completed)
        ->addFinalState(StateMock::Completed)
        ->addTransition(
            TransitionBuilder::make('created')
                ->source(StateMock::Init)
                ->target(StateMock::Completed)
        )
        ->build()
        ->inicialize($mockStateable);

    expect($stateMachine->isFinalState())->toBeTrue();
});

it('throws MissingFinalStateException when state machine without final state', function () {
    $mockStateable = Mockery::mock(Stateable::class);
    $mockStateable->shouldReceive('getState')->andReturn(StateMock::Init);;

    $stateMachine = StateMachine::configure('Test Machine')
        ->addState(StateMock::Init)
        ->addState(StateMock::Completed)
        ->build()
        ->inicialize($mockStateable);

    expect(fn() => $stateMachine->isFinalState())
        ->toThrow(MissingFinalStateException::class);
});
