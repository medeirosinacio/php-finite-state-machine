<?php

namespace Automata\Interfaces\States;

/**
 * A interface "ComplexState" é uma extensão da interface State usada para definir um estado mais complexo,
 * que além de possuir um nome, pode conter uma ação, um timeout e um trigger event.
 *
 * {@inheritDoc}
 */
interface ComplexState extends State
{
    /**
     * Retorna a ação a ser executada sempre que o objeto de contexto estiver no estado indicado.
     * A Maquina de Estados irá executar essa ação sempre que for inicializada com o objeto com o estado que implementa
     * essa interface.
     */
    public function getAction(): ?\Closure;

    /**
     * Retorna o tempo máximo que o objeto de contexto pode permanecer no mesmo estado.
     * Sempre que a máquina de estados for inicializada e o timeout do estado atual estiver expirado,
     * a máquina de estado irá disparar o evento de timeout.
     */
    public function getTimeout(): ?int;

    /**
     * Retorna o nome do evento a ser disparado assim que a máquina de estados entrar no estado que implementa
     * essa interface e ocorre após a Action de Estado.
     */
    public function getTrigger(): ?string;
}
