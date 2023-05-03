# Automata || Máquina de Estados Finitos em PHP

Automata — é um pacote escrito em PHP para uma Máquina de Estados. Ele permite gerenciar qualquer objeto que possua
estados definidos e transições entre esses estados, incluindo a definição de ações e regras de timeout.

## O que é uma máquina de estado?

Uma Máquina de Estados, também conhecida como Autômato, é uma forma de executar um processo passo a passo, baseada em
diferentes estados. Cada processo deve estar em um único estado de cada vez, como "INICIADO", "PENDENTE", "EM
ANDAMENTO", etc., e a transição para outro estado é feita de acordo com uma lógica ou condição específica. Essa condição
pode ser baseada em um campo, um tempo ou uma função personalizada.

[Leia mais sobre o conceito de State Machine.](./docs/concept-of-state-machine.md)

## Recursos

- Permite a transição de objetos [Stateables](./src/Interfaces/States/Stateable.php) entre
  diferentes [States](./src/Interfaces/States/State.php).
- Gerencia e define as [transições](./src/Transition.php) de estado dentro
  da [Máquina de Estado](./src/StateMachine.php).
- Oferece suporte para ações específicas no estado atual.
- Oferece suporte para ações de transições.
- Fornece suporte para gatilhos de eventos que podem desencadear transições de estado.
- Fornece suporte a timeout de [States](./src/Interfaces/States/State.php), permitindo transições automáticas para um
  novo estado após expiração.
- Fornece suporte a regras de Guard, condições que validam uma transição.
- Oferece Callback ao Guard falhar, permitindo que a aplicação execute uma ação específica quando uma regra de Guard
  falha.

## Exemplo rápido

Suponha que você esteja em uma estação de metrô e precise passar por uma catraca para acessar o trem. A catraca permite
que você passe apenas se tiver um bilhete válido.

![Turnstile_state_machine_colored.svg](https://upload.wikimedia.org/wikipedia/commons/thumb/9/9e/Turnstile_state_machine_colored.svg/1920px-Turnstile_state_machine_colored.svg.png)


```php

use Automata\StateMachine
use Automata\Builders\StateBuilder as State
use Automata\Builders\TransitionBuilder as Transition

$stateMachine = StateMachine::configure('CATRACA')
        ->addStates([
            State::make('Locked')->action(fn () => 'Trava Catraca'),
            State::make('Unlocked')->action(fn () => 'Destrava Catraca')->timeout(1),
        ])
        ->addTransitions([
            Transition::make('insert_coin')
                ->source('Locked')
                ->target('Unlocked')
                ->guard(fn () => 'Moeda valida'),

            Transition::make('pass')
                ->source('Unlocked')
                ->target('Locked'),

            Transition::make('unlocked_timeout')
                ->source('Unlocked')
                ->target('Locked'),
        ]);
```

Nesse exemplo, temos duas states: "Bloqueada" e "Desbloqueada". A state "Bloqueada" possui um timeout de 5 segundos e
uma action que exibe uma mensagem de erro na catraca. A state "Desbloqueada" possui um trigger "passagem_realizada".

Temos também duas transitions: "passagem_valida" e "passagem_realizada". A transition "passagem_valida" é disparada
quando o usuário apresenta o cartão de transporte válido na catraca, e ela faz a transição do estado "Bloqueada" para "
Desbloqueada", desde que a condição definida no guard seja verdadeira (no caso, verificar se o usuário possui saldo
suficiente). Já a transition "passagem_realizada" é disparada quando o usuário passa pela catraca, e ela faz a transição
do estado "Desbloqueada" para "Bloqueada", registrando a passagem do usuário e bloqueando a catraca.

## Terminologia

- **State Machine:** A State Machine é responsável por gerenciar todos os estados e transições, além de fornecer métodos
  para executar ações específicas em cada estado. Ela é o componente que define o comportamento geral da máquina de
  estados.

- **State:** é a principal entidade da máquina de estados onde suas transições são dirigidas por eventos. Representa um
  modelo em que a máquina de estados pode permanecer.

    - **Simples:** Como o nome sugere, o estado simples é o mais básico e contém apenas um atributo que é o seu nome,
      geralmente representado por um enum ou uma string.

    - **Complexo:** O estado complexo é aquele que possui atributos adicionais, além do nome, como o timeout e actions.

    - **Composto:** O estado composto é aquele que depende de outros estados para ser concluído. Ele é composto por
      estados internos e uma transição para um estado externo.

- **Events:** é uma entidade enviada para a máquina de estados que determina, a partir de um estado origem, a
  mudança de estado.

- **Guards:** é uma expressão booleana avaliada dinamicamente que afeta o comportamento da máquina de estados,
  habilitando ações e transições apenas quando ela for avaliada como verdadeira.

- **Transitions:** é a relação entre um estado origem e um estado alvo.

- **Actions:** é um comportamento executado durante o disparo de uma transição. Ela pode ser uma ação de entrada, isto
  é, a ação executada ao entrar no estado; ou de saída, isto é, a ação executada ao sair do estado.

- **Trigger:** Representa um evento gatilho que pode desencadear uma transição de estado na máquina de estados, podendo
  ser acionado por um evento ou por um timer.
