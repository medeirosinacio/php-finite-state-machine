<?php

namespace Automata\Tests;

use Automata\Stateable;
use Automata\StateMachine;
use Automata\Builders\StateBuilder as State;
use Automata\Builders\TransitionBuilder as Transition;

it('test state machine gate example', function () {

    $gate = new Stateable();

    $gateStateMachine = StateMachine::configure('GATE')
        ->addInitialState('Locked')
        ->addStates([
            State::make('Locked')->action(fn () => 'Lock Gate'),
            State::make('Unlocked')->action(fn () => 'Unlock Gate')->timeout(30),
        ])
        ->addTransitions([
            Transition::make('inserted_ticket')
                ->source('Locked')
                ->target('Unlocked')
                ->guard(fn () => 'Ticket is valid'),

            Transition::make('pass_gate')
                ->source('Unlocked')
                ->target('Locked'),

            Transition::make('timeout')
                ->source('Unlocked')
                ->target('Locked'),
        ])->initialize($gate);

    $afterTrigger = $gateStateMachine->getCurrentState();
    $gateStateMachine->trigger('inserted_ticket');
    $beforeTrigger = $gateStateMachine->getCurrentState();

    expect($afterTrigger)->toEqual('Unlocked');
    expect($beforeTrigger)->toEqual('Locked');
    expect($gateStateMachine->getStateable()->getState())->toEqual('Locked');
});