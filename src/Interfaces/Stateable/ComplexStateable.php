<?php

namespace Automata\Interfaces\Stateable;

/**
 * A interface "ComplexStateable" é uma extensão da interface "Stateable", que permite obter informações da data de
 * criação associadas a estados mais complexos.
 *
 * {@inheritDoc}
 */
interface ComplexStateable extends Stateable
{
    /**
     * O método retorna a data em que o objeto entrou no estado atual da máquina de estados.
     * Essas informações são usadas na verificação de timeout de um ComplexState
     */
    public function getDateStateDefined(): \DateTimeInterface;

    public function updateDateStateDefined(): void;
}
