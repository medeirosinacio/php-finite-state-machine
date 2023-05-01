<?php

namespace Automata;

use Automata\Builders\StateMachineBuilder;
use Automata\Exceptions\StateMachineAlreadyStartedException;
use Automata\Exceptions\StateMachineNotHasContextStateableException;
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
 * $transitions: objeto que armazena as transições da máquina de estados.
 * $context: objeto que representa a entidade de domínio.
 *
 * Essa classe é parte de um framework para máquinas de estados(Automata), e pode ser usada em conjunto com outras classes
 * para definir regras de transição de estados, eventos e lógica personalizada para cada aplicação específica.
 */
final class StateMachine
{
    public ?string $name = null;

    public States $states;

    public Transitions $transitions;

    public ?Stateable $stateable = null;

    private bool $enable = false;

    public static function configure(?string $name = null): StateMachineBuilder
    {
        return StateMachineBuilder::configure($name);
    }

    public function inicialize(?Stateable $stateable = null): self
    {
        $this->stateable ??= $stateable;

        $this->assertMachineContext();

        $this->enableMachine();

        return $this;
    }

    private function assertMachineDisable(): self
    {
        return $this->enable ? throw new StateMachineAlreadyStartedException() : $this;
    }

    private function assertMachineContext(): self
    {
        return $this->stateable instanceof Stateable ? $this : throw new StateMachineNotHasContextStateableException();
    }

    private function enableMachine(): void
    {
        $this->assertMachineDisable();

        $this->enable = true;
    }
}
