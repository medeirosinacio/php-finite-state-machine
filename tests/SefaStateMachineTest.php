<?php

namespace Automata\Tests;

use Automata\Builders\StateMachineBuilder;
use Automata\Builders\TransitionBuilder as Transition;
use Automata\State;
use Automata\Tests\Mocks\States\StateMock;

it('test state machine builder', function () {

    $stateMachine = StateMachineBuilder::configure('SEFA')
        ->addStates([
            \Automata\Builders\StateBuilder::make('iniciado')->trigger('started_solicitacao'),

            \Automata\Builders\StateBuilder::make('solicitacao a processar')->action(fn() => 'send queue'),
            \Automata\Builders\StateBuilder::make('solicitacao em processamento')->timeout(60),
            \Automata\Builders\StateBuilder::make('solicitacao concluida')->trigger('started_down'),

            \Automata\Builders\StateBuilder::make('download a processar')->action(fn() => 'send queue'),
            \Automata\Builders\StateBuilder::make('download em processamento')->timeout(60),
            \Automata\Builders\StateBuilder::make('download concluido')->trigger('started_archives'),

            \Automata\Builders\StateBuilder::make('disponibilizando arquivos')->action(fn() => 'send queue'),

            \Automata\Builders\StateBuilder::make('concluido')
        ])
        ->addTransitions([

            Transition::make('started_solicitacao')
                ->source(new State('iniciado'))
                ->target(new State('solicitacao a processar')),

            Transition::make('solicitacao update')
                ->source(new State('solicitacao em processamento'))
                ->target(new State('solicitacao concluida'))
                ->guard(callback: fn() => 'valida stateable', triggerOnFail: 'fatal_error'),

            Transition::make('started_down')
                ->source(new State('solicitacao concluida'))
                ->target(new State('download a processar')),

            Transition::make('download update')
                ->source(new State('download em processamento'))
                ->target(new State('download concluido'))
                ->guard(callback: fn() => 'valida stateable', triggerOnFail: 'fatal_error'),

            Transition::make('started_archives')
                ->source(new State('download concluido'))
                ->target(new State('disponibilizando arquivos')),

            Transition::make('archives_downloaded')
                ->source(new State('disponibilizando arquivos'))
                ->target(new State('concluido'))
                ->guard(callback: fn() => 'valida arquivos stateable', triggerOnFail: 'fatal_error'),


        ]);
});

// cron ira rodar os entry
