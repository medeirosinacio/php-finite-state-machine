<?php

namespace Automata;

use Automata\Builders\StateMachineBuilder;
use Automata\Interfaces\States\CompositeState;
use Automata\Exceptions\{MissingStateException, MissingTransitionTriggeredException,};
use Automata\Interfaces\Stateable\ComplexStateable;
use Automata\Interfaces\Stateable\CompositeStateable;
use Automata\Interfaces\Stateable\Stateable;
use Automata\Interfaces\States\ComplexState;
use Automata\Traits\Asserts;
use Automata\Traits\Assessors;
use Automata\Traits\PowerPanel;

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
class StateMachine
{
    use Assessors,
        Asserts,
        PowerPanel;

    private const EVENT_TIMEOUT = 'timeout';

    protected bool $isRunning = false;

    protected ?\Automata\Interfaces\States\State $initialState = null;

    public function __construct(
        protected ?string                                            $uid = null,
        protected States|ComplexState|CompositeState                 $states = new States(),
        protected Transitions                                        $transitions = new Transitions(),
        protected Stateable|ComplexStateable|CompositeStateable|null $stateable = null,
        protected States                                             $finalStates = new States(),
    )
    {
    }

    public static function configure(?string $uid = null): StateMachineBuilder
    {
        return StateMachineBuilder::configure($uid);
    }

    public function executeActionFromCurrentState(): void
    {
        $this->assertMachineEnabled();

        $currentState = $this->getCurrentState();

        if ($currentState instanceof CompositeState) {
            $this->resolveCompositeState(compositeState: $currentState);
        }

        if ($currentState instanceof ComplexState && $currentState->getAction() instanceof \Closure) {
            ($currentState->getAction())($this->stateable);
        }
    }

    public function executeTriggerFromCurrentState(): void
    {
        $this->assertMachineEnabled();

        $currentState = $this->getCurrentState();

        if ($trigger = $currentState->getTrigger()) {
            $this->trigger($trigger);
        }
    }

    private function resolveCompositeState(CompositeState $compositeState): void
    {
        $compositeState->resolve(stateable: $this->stateable);

        if ($compositeState->isCompleted()) {
            $this->trigger(eventName: $compositeState->getName() . '_completed');
        }
    }

    private function configureInitialState(): self
    {
        $this->initialState =
            $this->initialState ? $this->getState(state: $this->initialState) : $this->stateable?->getState();

        $this->assertInitialState();

        if ($this->stateable->getState() === null) {
            $this->stateable->setState($this->initialState);
        }

        return $this;
    }

    public function transitionTo(Interfaces\States\State $state): void
    {
        $this->stateable->setState($state);

        if ($state instanceof ComplexStateable) {
            $this->stateable->updateDateStateDefined();
        }
    }

    public function trigger(string|Interfaces\Event $eventName, mixed ...$params): void
    {
        $this->assertMachineEnabled();

        $this->forceTrigger($eventName, $params);
    }

    public function isFinalState(): bool
    {
        $this->assertFinalState();

        return $this->finalStates->has($this->getCurrentState());
    }

    protected function forceTrigger(string|Interfaces\Event $eventName, mixed ...$params): void
    {
        $transition = $this->transitions->get($eventName);

        if ($transition === null) {
            throw new MissingTransitionTriggeredException($eventName, $this->transitions);
        }

        if ($this->states->missing($transition->target->getName())) {
            throw new MissingStateException($transition->target, $this->states); // TODO create test
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
