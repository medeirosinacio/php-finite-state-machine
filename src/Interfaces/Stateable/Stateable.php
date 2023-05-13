<?php

namespace Automata\Interfaces\Stateable;

use Automata\Interfaces\States\ComplexState;
use Automata\Interfaces\States\State;

/**
 * Stateable é uma interface que define os métodos que qualquer contexto que queira
 * usar a máquina de estados deve implementar.
 *
 * O contexto é um objeto que contém todas as informações contextuais necessárias para que a máquina de estados
 * faça o seu trabalho, com a ajuda das dependências relevantes. O contexto é criado pela sua aplicação para fornecer
 * as dependências adequadas ('contexto') para a máquina de estados trabalhar.
 *
 * Os métodos definidos se referem a manipulação do Estado dentro da entidade de contexto.
 */
interface Stateable
{
    public function getState(): State|ComplexState|null;

    public function setState(State $state): void;
}
