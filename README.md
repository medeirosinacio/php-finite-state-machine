## Conceito

Uma State Machine é um padrão que descreve o comportamento de um sistema em relação a eventos e condições,
garantindo que o comportamento seja consistente e previsível. Isso é feito definindo estados finitos e transições
entre eles, acionadas por gatilhos pré-definidos, como eventos, temporizadores e ações.

Uma vantagem de usar uma máquina de estados é que a lógica de alto nível pode ser definida fora do sistema, tornando-o
mais simples e fácil de depurar. Além disso, a interação com a máquina de estados pode ser feita através de eventos,
mudanças de estado ou solicitações de estado atual.

Um exemplo de máquina de estados é a catraca de uma estação de metrô, que tem os estados "travada" e "destravada".
Quando o passageiro insere uma moeda, o estado da catraca é alterado para "destravada", permitindo a passagem do
usuário.
Quando o passageiro passa pela catraca, o estado da catraca volta para "travada".

![Turnstile_state_machine_colored.svg](https://upload.wikimedia.org/wikipedia/commons/thumb/9/9e/Turnstile_state_machine_colored.svg/1920px-Turnstile_state_machine_colored.svg.png)

## Componentes

### State Machine

A State Machine é responsável por gerenciar todos os estados e transições, além de fornecer métodos para executar ações
específicas em cada estado. Ela é o componente que define o comportamento geral da máquina de estados.

### States

> O estado específico da máquina de estado. Valores finitos e predeterminados.

Representa um estado específico em que a máquina pode estar. Cada estado tem um nome e pode ter um ou mais eventos
associados a ele. É possível definir ações a serem executadas quando a máquina entrar, sair ou estiver em um estado.

#### Tipos de States

Os estados podem ser classificados em diferentes tipos dentro do sistema, sendo:

##### Simples

Como o nome sugere, o estado simples é o mais básico e contém apenas um atributo que é o seu nome, geralmente
representado por um enum ou uma string.

##### Complexo

O estado complexo é aquele que possui atributos adicionais, além do nome, como o timeout e actions.

##### Composto

O estado composto é aquele que depende de outros estados para ser concluído. Ele é composto por estados internos e uma
transição para um estado externo.

É importante mencionar que a definição de estados compostos varia de acordo com a metodologia de desenvolvimento
adotada. Algumas metodologias definem estados compostos como estados que possuem outros estados internos, enquanto
outras consideram estados compostos apenas aqueles que possuem transições para estados externos.

### Events

> Algo que acontece com o sistema — pode ou não alterar o estado.

São as ações ou sinais que ativam as transições entre os estados. Os eventos podem ser internos (gerados pela própria
máquina de estado) ou externos (provenientes do ambiente externo).

### Guards

> Condições para troca de estado.

São regras que devem ser verdadeiras para que a transição possa ocorrer. Elas são usadas para especificar restrições
na transição.

### Transitions

> Tipo de ação que muda de estado. Relacionamento entre um estado de origem e um estado de destino.

É a mudança de um estado para outro, geralmente disparada por um evento. Uma transição possui um estado de
origem, um estado de destino, um evento que a desencadeia e possivelmente um conjunto de condições que devem ser
satisfeitas (guards) antes que a transição ocorra.

### Actions

> A resposta da State Machine, pode ser uma mudança de estado ou uma execução de ação dependendo da transição.

As ações são atividades realizadas enquanto o sistema está em um estado específico ou durante uma transição. As Actions
de Estado são executadas continuamente enquanto a State Machine permanece no mesmo estado, enquanto as Actions de
Transição são executadas após um evento que dispara a transição.

### Trigger

> É um gatilho que dispara um evento de transição de estado.

Representa um evento gatilho que pode desencadear uma transição de estado na máquina de estados, podendo ser
acionado por um evento ou por um timer.

