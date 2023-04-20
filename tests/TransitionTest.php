<?php

use Medeirosinacio\FiniteStateMachine\Transition;
use Tests\Mocks\StateMock;

it('can be created with a "from" state', function () {
    $fromState = StateMock::Init;
    $transition = new Transition($fromState);

    expect($transition->from)->toEqual($fromState);
});

it('can set the "to" state', function () {
    $fromState = StateMock::Init;
    $toState = StateMock::Finished;
    $transition = new Transition($fromState);
    $transition->to = $toState;

    expect($transition->to)->toEqual($toState);
});

it('can set events as an iterable of anonymous functions and invokable classes', function () {
    $fromState = StateMock::Init;
    $transition = new Transition($fromState);
    $event1 = fn () => true;
    $event2 = new class() {
        public function __invoke(): false
        {
            return false;
        }
    };
    $transition->event($event1, $event2);

    expect($transition->getEvents())->toEqual([$event1, $event2]);
});

it('can set rules as an iterable of anonymous functions and invokable classes', function () {
    $fromState = StateMock::Init;
    $transition = new Transition($fromState);
    $event1 = fn () => true;
    $event2 = new class() {
        public function __invoke(): false
        {
            return false;
        }
    };
    $transition->rules($event1);
    $transition->addRule($event2);

    expect($transition->getRules())->toEqual([$event1, $event2]);
});
