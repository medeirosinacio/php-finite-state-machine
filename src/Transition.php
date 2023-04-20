<?php

namespace Automata;

use Automata\Interfaces\States\State;

/**
 * A classe "Transition" representa uma transição entre estados em uma máquina de estado.
 *
 * A transição é iniciada quando um evento ocorre e uma condição (guarda) é atendida. A transição pode ou não
 * ter ações associadas a ela, que serão executadas no momento da transição.
 *
 * A transição é caracterizada por:
 * - Um estado de origem ($source), quando null a StateMachine vai considerar qualquer Estado
 * - Um estado de destino ($target), quando null a StateMachine vai lançar um erro
 * - Um conjunto de ações ($actions) que serão executadas durante a transição
 * - Um conjunto de guardas ($guards) que devem ser satisfeitas para permitir que a transição ocorra
 * - Um possível gatilho ($triggerOnGuardFail) que será ativado se uma guarda falhar.
 *
 * A propriedade "name" é o nome da transição.
 */
final class Transition
{
    public ?State $source = null;

    public ?State $target = null;

    /** @var \Closure[] */
    public array $actions = [];

    /** @var \Closure[] */
    public array $guards = [];

    public ?string $triggerOnGuardFail = null;

    public function __construct(
        public readonly string $name
    ) {
    }
}
