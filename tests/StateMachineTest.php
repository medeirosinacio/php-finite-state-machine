<?php

use Medeirosinacio\FiniteStateMachine\StateMachine;

it('can initialize to the start state', function () {
    $stateMachine = new StateMachine();
    expect($stateMachine->getCurrentState())->toBe('start');
});

it('can transition to a new state on valid input', function () {
    $stateMachine = new StateMachine();
    $stateMachine->processInput('valid_input');
    expect($stateMachine->getCurrentState())->toBe('new_state');
});

it('stays in the same state on invalid input', function () {
    $stateMachine = new StateMachine();
    $stateMachine->processInput('invalid_input');
    expect($stateMachine->getCurrentState())->toBe('start');
});

it('can perform multiple state transitions', function () {
    $stateMachine = new StateMachine();
    $stateMachine->processInput('valid_input');
    $stateMachine->processInput('another_valid_input');
    expect($stateMachine->getCurrentState())->toBe('final_state');
});

it('reaches the final state on a sequence of valid inputs', function () {
    $stateMachine = new StateMachine();
    $stateMachine->processInput('valid_input');
    $stateMachine->processInput('another_valid_input');
    expect($stateMachine->getCurrentState())->toBe('final_state');
});

it('can be reset to the start state', function () {
    $stateMachine = new StateMachine();
    $stateMachine->processInput('valid_input');
    $stateMachine->reset();
    expect($stateMachine->getCurrentState())->toBe('start');
});

it('handles invalid transitions gracefully', function () {
    $stateMachine = new StateMachine();
    $stateMachine->processInput('valid_input');
    $stateMachine->processInput('invalid_input');
    expect($stateMachine->getCurrentState())->toBe('new_state');
});

it('maintains state properties during transitions', function () {
    $stateMachine = new StateMachine();
    $stateMachine->processInput('valid_input');
    $currentState = $stateMachine->getCurrentState();
    expect($stateMachine->getStateProperty($currentState))->toBe('some_value');
});

it('can handle concurrent inputs without conflicts', function () {
    $stateMachine = new StateMachine();
    $input1 = 'valid_input';
    $input2 = 'another_valid_input';
    $stateMachine->processInput($input1);
    $stateMachine->processInput($input2);
    expect($stateMachine->getCurrentState())->toBe('final_state');
});

it('can persist and restore state between sessions', function () {
    $stateMachine = new StateMachine();
    $stateMachine->processInput('valid_input');
    $stateMachine->saveStateToFile('state.json');
    $stateMachine->reset();
    $stateMachine->loadStateFromFile('state.json');
    expect($stateMachine->getCurrentState())->toBe('new_state');
});
