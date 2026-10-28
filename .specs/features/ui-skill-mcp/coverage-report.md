---
status: DONE
progress: 23/23
updated: 2026-10-27
---
# Coverage report · ui-skill-mcp

Medição de cobertura do bundle `ui-skill-mcp` na árvore de trabalho (branch `spec/ui-skill-mcp`, base `main` @ 1512ebc). Nenhum código ou teste alterado para esta medição.

## Comando

```
XDEBUG_MODE=coverage php85 -d zend_extension=/Applications/Herd.app/Contents/Resources/xdebug/xdebug-85-arm64.so vendor/bin/pest --coverage-clover
```

Clover em `$TMPDIR/clover.xml`. Sumário calculado por script Python contando linhas `type="stmt"` por arquivo (`count > 0` = coberta).

## Gate

| Escopo | Cobertas / statements | % | Gate | Resultado |
|---|---|---|---|---|
| Arquivos novos (15) | 644 / 651 | 98,92% | ≥ 93% | PASS |
| `src/` inteiro | 845 / 874 | 96,68% | — | informativo |

Arquivos novos = os 15 arquivos de `src/` que não existem em `main`: `Console/SkillCommand.php`, `Mcp/*` (9 arquivos + `UiServer.php`), `Support/BladeComponentParser.php`, `Support/ComponentManifest.php`, `Support/SkillMirror.php`. Soma dos statements: 651. Soma das cobertas: 644.

## Por arquivo novo

| Arquivo | Cobertas / total | % | Linhas não cobertas |
|---|---|---|---|
| `Console/SkillCommand.php` | 19 / 19 | 100% | — |
| `Mcp/MarkupBuilder.php` | 45 / 45 | 100% | — |
| `Mcp/Scaffolder.php` | 178 / 180 | 98,89% | 187, 216 |
| `Mcp/Tools/GenerateComponentTool.php` | 21 / 21 | 100% | — |
| `Mcp/Tools/GenerateTemplateTool.php` | 21 / 22 | 95,45% | 67 |
| `Mcp/Tools/GetComponentTool.php` | 20 / 20 | 100% | — |
| `Mcp/Tools/ListComponentsTool.php` | 16 / 16 | 100% | — |
| `Mcp/Tools/ScaffoldComponentTool.php` | 22 / 22 | 100% | — |
| `Mcp/Tools/ScaffoldTemplateTool.php` | 14 / 14 | 100% | — |
| `Mcp/Tools/ScaffoldTool.php` | 14 / 15 | 93,33% | 42 |
| `Mcp/UiServer.php` | 0 / 0 | — | — (sem statements; registro do servidor) |
| `Support/BladeComponentParser.php` | 121 / 121 | 100% | — |
| `Support/ComponentManifest.php` | 111 / 111 | 100% | — |
| `Support/SkillMirror.php` | 42 / 45 | 93,33% | 57, 65, 121 |
| **Total** | **644 / 651** | **98,92%** | |

## Linhas não cobertas e motivo

- **`Mcp/Scaffolder.php:187`**: `InvalidArgumentException` "File already exists". Ramo de checagem de existência; o caso de colisão cai antes, no `fopen` com modo `'x'`.
- **`Mcp/Scaffolder.php:216`**: `RuntimeException` "Falha ao gravar". Falha de escrita após o `fopen` com modo `'x'`. Não é reproduzível de forma determinística sem mock de filesystem; a corrida entre checagem e gravação também não é.
- **`Mcp/Tools/GenerateTemplateTool.php:67`**: "Example file not found". Ramo de defesa. Os arquivos de exemplo do kit estão presentes, então o caminho não é atingido pelo fluxo normal.
- **`Mcp/Tools/ScaffoldTool.php:42`**: `catch` que devolve `$e->getMessage()` como erro da tool. Não há caso de teste que dispare exceção nesse ponto.
- **`Support/SkillMirror.php:57`**: "Falha ao remover" no prune de arquivo. Depende de falha real do filesystem.
- **`Support/SkillMirror.php:65`**: "Falha ao criar" diretório no espelho. Idem.
- **`Support/SkillMirror.php:121`**: "Falha ao remover" no caminho de limpeza. Idem.

Os seis ramos de falha de filesystem seguem o mesmo padrão: exigem falha de I/O que não se força sem mock. Os cenários de falha que a spec exige (RuntimeException com mensagem clara) foram cobertos nos testes de borda do `SkillMirror` e do `Scaffolder` (ver `review.md`, achado 3). A linha 187 e a 42 são ramos de mensagem.

## Fora do escopo novo (informativo)

Arquivos pré-existentes com linhas não cobertas, sem ação nesta feature:

- `Console/IconCommand.php`: 50/54 (linhas 48, 50, 57, 149)
- `Support/ChartGeometry.php`: 46/57
- `Support/CodeHighlighter.php`: 57/60 (linhas 110, 129, 150)
- `UiServiceProvider.php`: 14/18 (linhas 50, 51, 52, 63)

Esses números não entram no gate desta feature. Podem ser atacados em follow-up se o usuário quiser.

## Rastreabilidade REQ → TASK → TEST

Fonte: `tasks/README.md`, seção "Rastreabilidade REQ → TASK → TEST".

- Cobertura: **23/23** requisitos (S1–S7, F1–F6, M1–M10) com task e teste associados.
- Detalhe de prova por REQ: `validation.md` (matriz REQ, 23 linhas) e `checks.md`.
- Pendente por natureza: I1 e REQ-S1 (histórico `git log --follow`), que só se confirma após o commit.

## Suite

- `pest`: **997 passed** (2644 assertions), 0 failed.
- Baseline antes da feature: **432** passed (`baseline.txt`).
- Delta sobre a baseline: +565 testes passando. Nenhuma falha pré-existente foi listada como fora de escopo.

## Conclusão

Gate de cobertura **PASS**: 98,92% nos arquivos novos, acima de 93%. As sete linhas descobertas são ramos de falha de I/O ou de defesa, listados acima com o motivo. Não foi feita análise de regra de negócio linha a linha; o veredito se apoia no clover e nos testes de REQ.
