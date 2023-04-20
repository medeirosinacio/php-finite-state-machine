<?php

use Automata\Builders\StateMachineBuilder;
use Automata\Builders\TransitionBuilder as Transition;
use Tests\Mocks\States\StateMock;

it('test state machine builder', function () {

    $stateMachine = StateMachineBuilder::configure('SEFA_DOWNLOAD')
        ->addStates([
            \Automata\Builders\StateBuilder::make('New')->trigger('created'),
            StateMock::Pendent->action(fn () => 'envia tarefa para fila'),
            StateMock::Processing->timeout(60),
            StateMock::Retry->trigger('retrying'),
            StateMock::Erro->trigger('try_retrying'),
            StateMock::Canceled,
            StateMock::Completed,
        ])
        ->addTransitions([

            Transition::make('created')
                ->source(StateMock::New)
                ->target(StateMock::Pendent),

            Transition::make('task_get_to_processing')
                ->source(StateMock::Pendent)
                ->target(StateMock::Processing),

            Transition::make('updated')
                ->source(StateMock::Processing)
                ->target(StateMock::Completed)
                ->guard(fn () => 'tem arquivos', triggerOnFail: 'retry'),

            Transition::make('retry')
                ->source(StateMock::Processing)
                ->target(StateMock::Retry),

            Transition::make('retrying')
                ->source(StateMock::Retry)
                ->target(StateMock::Pendent),

            Transition::make('processing_timeout')
                ->source(StateMock::Processing)
                ->target(StateMock::Erro),

            Transition::make('processing_error')
                ->source(StateMock::Processing)
                ->target(StateMock::Erro),

            Transition::make('try_retrying')
                ->source(StateMock::Erro)
                ->target(StateMock::Pendent)
                ->guard(callback: fn () => 'valida tentativas e se é fatal', triggerOnFail: 'fatal_error'),

            Transition::make('fatal_error')
                ->target(StateMock::Erro)
                ->action(fn () => 'seta erro fatal conforme mensagem e finaliza tarefa'),

            Transition::make('cancel')
                ->target(StateMock::Canceled)
                ->action(fn () => 'seta erro fatal conforme mensagem e finaliza tarefa'),

        ]);
});

// cron ira rodar os entry
