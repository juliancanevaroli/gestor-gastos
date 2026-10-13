# Regras de Negócio — Gestor de Gastos Pessoais

## 1. Objetivo

Este documento descreve as regras de negócio identificadas no Gestor de Gastos Pessoais, aplicação web desenvolvida em PHP e MySQL para registrar e consultar movimentações financeiras.

## 2. Regras de negócio

### RB01 — Classificação das transações
Cada transação deve possuir um tipo, classificado como entrada ou saída.

### RB02 — Informações obrigatórias
Para registrar uma transação pelo formulário, o usuário deve informar descrição, valor, tipo e data.

### RB03 — Associação de categorias
Uma transação pode possuir uma categoria ou ser registrada sem categoria.

### RB04 — Cálculo do saldo
O saldo mensal é calculado pela diferença entre o total de entradas e o total de saídas do mês selecionado.

**Fórmula:**

Saldo = Total de entradas − Total de saídas

### RB05 — Filtragem de transações
A listagem permite consultar movimentações por mês, tipo e categoria. Quando mais de um filtro é selecionado, os critérios são aplicados em conjunto.

### RB06 — Ordenação das transações
As transações são exibidas da data mais recente para a mais antiga. Em caso de datas iguais, o horário de criação é utilizado como critério adicional de ordenação.

### RB07 — Exclusão de transações
O usuário pode solicitar a exclusão de uma transação pela interface. Após a exclusão, a listagem e os totais devem refletir os dados restantes.

### RB08 — Visualização de gastos por categoria
O sistema disponibiliza um gráfico de rosca com os totais de despesas agrupados por categoria, considerando o mês selecionado.

### RB09 — Comparação de entradas e saídas
O sistema disponibiliza um gráfico de barras para comparar os totais de entradas e saídas ao longo do período consultado.

## 3. Pontos que precisam de validação

Os seguintes comportamentos devem ser testados para confirmar se a aplicação atende às expectativas:

- O sistema rejeita valores negativos ou iguais a zero?
- O sistema impede datas inválidas ou futuras, caso isso seja uma regra desejada?
- Os totais são atualizados corretamente após excluir uma transação?
- O que acontece quando o mês selecionado não possui movimentações?
- Os filtros funcionam corretamente quando utilizados simultaneamente?
- O gráfico de barras apresenta exatamente os seis meses esperados?

## 4. Observação

Este documento foi elaborado a partir da leitura dos trechos de código disponíveis. Os comportamentos listados em "Pontos que precisam de validação" ainda precisam ser testados na aplicação. As regras devem ser revisadas caso o comportamento real do sistema seja diferente do descrito.
