<?php

namespace Automata;

use Automata\Builders\StateMachineBuilder;
use Automata\Interfaces\States\ComplexStateable;
use Automata\Traits\Asserts;
use Automata\Traits\Assessors;
use Automata\Traits\PowerPanel;
use Automata\Exceptions\{MissingStateException,
    MissingTransitionTriggeredException,
};
use Automata\Interfaces\States\Stateable;

/**
 * A classe StateMachine é utilizada para executar transições de estados para uma entidade que representa um objeto
 * de domínio. Esta classe aplica lógica de guarda para verificar se uma transição é permitida e lógica de
 * transição para processar a transição.
 *
 * A classe possui as seguintes propriedades:
 *
 * $states: objeto que armazena os estados disponíveis da máquina de estados.
 * $initialState: estado inicial da máquina de estados.
 * $currentStateCache: estado atual cacheado da máquina de estados.
 * $transitions: objeto que armazena as transições da máquina de estados.
 * $context: objeto que representa a entidade de domínio.
 *
 * Essa classe é parte de um framework para máquinas de estados(Automata), e pode ser usada em conjunto com outras classes
 * para definir regras de transição de estados, eventos e lógica personalizada para cada aplicação específica.
 */
final class StateMachine
{
    use Assessors,
        Asserts,
        PowerPanel;

    private const EVENT_TIMEOUT = 'timeout';

    protected bool $isRunning = false;

    protected ?\Automata\Interfaces\States\State $initialState = null;

    public function __construct(
        protected ?string $uid = null,
        protected States $states = new States(),
        protected Transitions $transitions = new Transitions(),
        protected Stateable|ComplexStateable|null $stateable = null
    ) {
    }

    public static function configure(?string $uid = null): StateMachineBuilder
    {
        return StateMachineBuilder::configure($uid);
    }

    private function configureInitialState(): self
    {
        $this->initialState ??= $this->stateable?->getState();

        $this->assertInitialState();

        if ($this->stateable->getState() === null) {
            $this->stateable->setState($this->initialState);
        }

        return $this;
    }

    public function transitionTo(Interfaces\States\State $state): void
    {
        $this->stateable->setState($state);

        $this->stateable->updateDateStateDefined();
    }

    public function trigger(string|Interfaces\Event $eventName, mixed ...$params): void
    {
        $this->assertMachineEnabled();

        $this->forceTrigger($eventName, $params);
    }

    protected function forceTrigger(string|Interfaces\Event $eventName, mixed ...$params): void
    {
        $transition = $this->transitions->get($eventName);

        if ($transition === null) {
            throw new MissingTransitionTriggeredException;
        }

        if ($this->states->missing($transition->target->getName())) {
            throw new MissingStateException; // TODO create test
        }

        if ($transition->source->getName() !== $this->stateable->getState()->getName()) {
            return; // TODO create test
        }

        $this->transitionTo($this->states->get($transition->target->getName()));
    }

    protected function timeoutStateChecker(): void
    {
        if ($this->stateNotComplexType() || $this->stateNotHasTimeout()) {
            return;
        }

        $this->assertMachineStateableComplex();

        $dateStateDefined = $this->stateable->getDateStateDefined();

        if ($dateStateDefined->diff(new \DateTime())->s <= $this->stateable->getState()->getTimeout()) {
            return;
        }

        $this->forceTrigger(self::EVENT_TIMEOUT);
    }
}
