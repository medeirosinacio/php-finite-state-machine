<?php

namespace Automata\Interfaces\States;

/**
 * A interface "State" define o estado de um objeto de contexto de uma Máquina de Estado.
 * Essa interface é a mais simples sendo projetada para ser usada em estados que possuem apenas o nome,
 * sem implementações adicionais, como timeout ou ações.
 *
 * A função "getName()" retorna o nome do estado como uma string.
 * É importante destacar que uma instância da classe State pode e deve ser
 * compartilhada por vários objetos Transition quando se trata do mesmo estado para sua origem/do estado target.
 *
 * Para configurações de Estados mais complexos, veja:
 *
 * @see  \Automata\Interfaces\States\CompositeState
 * @see  \Automata\Interfaces\States\ComplexState
 * @see  \Automata\State
 */
interface State
{
    public const UNKNOWN = \Automata\Enums\State::Unknown;

    public function getName(): string;
}
