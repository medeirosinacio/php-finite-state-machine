<?php

namespace Automata\Tests;

use Automata\Stateable;
use Automata\StateMachine;
use Automata\Builders\StateBuilder as State;
use Automata\Builders\TransitionBuilder as Transition;

$gateStateMachine = StateMachine::configure('GATE')
    ->addInitialState(state: 'Locked')
    ->addStates([
        State::make(name: 'Locked')->action(action: fn () => 'Lock Gate'),
        State::make(name: 'Unlocked')->action(action: fn () => 'Unlock Gate')->timeout(seconds: 3),
    ])
    ->addTransitions([
        Transition::make(eventName: 'inserted_ticket')
            ->source(state: 'Locked')
            ->target(state: 'Unlocked')
            ->guard(callback: fn () => 'Ticket is valid'),

        Transition::make(eventName: 'pass_gate')
            ->source(state: 'Unlocked')
            ->target(state: 'Locked'),

        Transition::make(eventName: 'timeout')
            ->source(state: 'Unlocked')
            ->target(state: 'Locked'),
    ])
    ->build()
    ->inicialize(new Stateable());

it('should start in Locked state', function () use ($gateStateMachine) {
    expect($gateStateMachine->getCurrentState()->getName())->toEqual('Locked');
});

it('should change to Unlocked state when ticket is inserted', function () use ($gateStateMachine) {
    $gateStateMachine->trigger('inserted_ticket', true);
    expect($gateStateMachine->getCurrentState()->getName())->toEqual('Unlocked');
});

it('should change back to Locked state when pass_gate transition is triggered', function () use ($gateStateMachine) {
    $gateStateMachine->trigger('inserted_ticket', true);
    $gateStateMachine->trigger('pass_gate', true);
    expect($gateStateMachine->getCurrentState()->getName())->toEqual('Locked');
});

it('should change no back to Locked state before 3 seconds of inactivity in Unlocked state',
    function () use ($gateStateMachine) {
        $gateStateMachine->trigger('inserted_ticket', true);

        sleep(2);

        expect($gateStateMachine->getCurrentState()->getName())->toEqual('Unlocked');
    });

it('should change back to Locked state in 3 seconds of inactivity in Unlocked state',
    function () use ($gateStateMachine) {
        $gateStateMachine->trigger('inserted_ticket', true);

        sleep(3);

        expect($gateStateMachine->getCurrentState()->getName())->toEqual('Locked');
    });

it('should change back to Locked state after 3 seconds of inactivity in Unlocked state',
    function () use ($gateStateMachine) {
        $gateStateMachine->trigger('inserted_ticket', true);

        sleep(4);

        expect($gateStateMachine->getCurrentState()->getName())->toEqual('Locked');
    });
