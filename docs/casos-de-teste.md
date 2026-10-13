# Casos de Teste — Gestor de Gastos Pessoais

## Objetivo

Verificar o funcionamento dos filtros de transações por tipo e categoria, incluindo a utilização simultânea dos dois critérios.

## CT01 — Filtro por tipo

**Procedimento:**
1. Selecionar outubro de 2026.
2. Selecionar o tipo "Entradas".
3. Manter "Todas as categorias".
4. Clicar em "Filtrar".

**Resultado esperado:** exibir as transações classificadas como entradas no período selecionado.

**Resultado obtido:** a transação "Salário", no valor de R$ 5.000,00, foi exibida.

**Status:** Aprovado.

## CT02 — Filtro por categoria

**Procedimento:**
1. Selecionar outubro de 2026.
2. Selecionar "Todos os tipos".
3. Selecionar a categoria "Freelance".
4. Clicar em "Filtrar".

**Resultado esperado:** exibir as transações associadas à categoria selecionada.

**Resultado obtido:** a transação "Salário", no valor de R$ 5.000,00, foi exibida.

**Status:** Aprovado.

## CT03 — Combinação de filtros

**Procedimento:**
1. Selecionar outubro de 2026.
2. Selecionar o tipo "Entradas".
3. Selecionar a categoria "Freelance".
4. Clicar em "Filtrar".

**Resultado esperado:** exibir as transações que atendam simultaneamente aos dois critérios.

**Resultado obtido:** a transação "Salário", no valor de R$ 5.000,00, foi exibida.

**Status:** Aprovado.

## Conclusão

Nos cenários executados, os filtros por tipo, categoria e combinação de critérios apresentaram os resultados esperados.

Os testes documentam os comportamentos verificados e não representam uma validação completa de todas as funcionalidades da aplicação.
