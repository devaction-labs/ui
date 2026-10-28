---
status: DONE
progress: 19/19
updated: 2026-10-27
---
# Review · ui-skill-mcp

Revisão do bundle `ui-skill-mcp` antes do commit. Branch `spec/ui-skill-mcp`, base `main` @ 1512ebc. Nenhum commit feito; commit decide o usuário.

## Estado prévio

- Baseline antes da feature: 432 passed.
- PHPStan: 0 erros. Pint: ok. Commit de referência: 1512ebc.
- PHP 8.5.12 (Herd).
- Sem driver de coverage no PATH. Resolvido carregando `/Applications/Herd.app/Contents/Resources/xdebug/xdebug-85-arm64.so`.
- Estado final após a feature e as correções de revisão: 997 passed (2644 assertions). Cobertura em `coverage-report.md`.

## Desvios aceitos

1. **TDD sem RED estrito em T-004 e T-011.** Testes foram escritos junto com o código, não antes. Motivo: as tasks eram de comando novo e scaffold, com contrato de saída só definido em código. Os testes cobrem os cenários da spec.
2. **TDD sem RED também em F3 (achados desta revisão).** As correções de revisão foram escritas antes dos testes que as cobrem, então o RED não foi observado. Os testes passam na versão corrigida e cobrem o comportamento pedido.
3. **`skill/` movido com `git mv`, mas sem histórico preservado.** O espelho Boost foi regenerado nos caminhos antigos. Um `git stash`/`pop` acidental de um subagente desfez o staging dos renames. Consequência: `git log --follow` (I1, REQ-S1) não é provável antes do commit. Conteúdo intacto (diff vs main só no índice). Commit decide o usuário.
4. **SKILL.md: 3 links de prosa viraram texto.** Links para livewire, layouts e theming viraram "Guides index below", para cada reference ter exatamente 1 link (V3). Conteúdo do espelho Boost muda além das seções novas Primitives, Layouts e Guides.
5. **REQ-M7 / B13: `brand` troca todo atributo `="Acme"`.** Layouts usam `sidebar.brand name=`, e a regra antiga não cobria esse caso. Spec atualizada para refletir o comportamento.
6. **Fix fora do escopo, aprovado pelo usuário.** `x-ui::pagination` em locale `en` quebrava em FS case-insensitive: `__('Pagination')` colidia com `lang/en/pagination.php` do framework. Chave trocada para `Pagination navigation`; pt-BR mantém "Paginação". Breaking leve: apps que sobrescreviam a chave "Pagination" precisam ajustar.
7. **Fix fora do escopo, aprovado.** `IconCommand.php:100` tinha `is_array` redundante, removido (PHPStan).
8. **`AGENTS.md` criado a pedido do usuário.** Fica fora da DAG de tasks.
9. **laravel/mcp v0.6.7 em require-dev + suggest.** `testbench.yaml` e `tests/TestCase.php` registram o McpServiceProvider.
10. **Spec sincronizada com a revisão.** `spec/02` e `spec/04`, B14–B15 e S10–S12 foram atualizados para o comportamento final. Não houve mudança de arquitetura.

## Achados de revisão (AppSec e code-quality)

| # | Achado | Sev | Local | Resultado |
|---|---|---|---|---|
| 1 | Prune do `SkillMirror` segue symlink (PoC confirmado) | média | `src/Support/SkillMirror.php` | corrigido (F1, `SupportEdgeCasesTest.php:138`) |
| 2 | Symlinks na origem do espelho | média | `src/Support/SkillMirror.php` | corrigido (`SupportEdgeCasesTest.php:152`) |
| 3 | Falhas de filesystem sem `RuntimeException` | baixa | `src/Support/SkillMirror.php`, `src/Mcp/Scaffolder.php` | corrigido (`SupportEdgeCasesTest.php:169`) |
| 4 | Memo do manifesto fica stale no mesmo processo | qualidade | `src/Support/ComponentManifest.php` | corrigido: invalida por mtime/size (linha 180) |
| 5 | Código morto no parser, linhas 147-148 | qualidade | `src/Support/BladeComponentParser.php:147-148` | removido |
| 6 | Front matter repetido nos testes (DRY) | qualidade | `tests/` | corrigido |
| 7 | Injeção de nome de atributo e de slot no `MarkupBuilder` (PoC) | média | `src/Mcp/MarkupBuilder.php` | corrigido (`MarkupBuilderTest.php:62,71`) |
| 8 | Limites de tamanho de entrada das tools ausentes | baixa | `src/Mcp/` | corrigido (`GenerateToolsTest.php:34`). Follow-up: sem teste para >50 props e >20 slots |
| 9 | `Scaffolder::sync` faz rebind global e ignora o retorno | qualidade | `src/Mcp/Scaffolder.php` | corrigido (`ScaffolderEdgeCasesTest.php:183`) |
| 10 | Campos obrigatórios do scaffold sem validação (M9) | média | `src/Mcp/Scaffolder.php` | corrigido (linha 133) |
| 11 | Mensagens de erro sem a regra violada | baixa | `src/Mcp/` | corrigido |
| 12 | Escape de markdown ausente | baixa | `src/Mcp/` | corrigido (linha 154) |
| 13 | Gating do pacote por nome do composer | baixa | `src/UiServiceProvider.php` | corrigido (linha 165) |
| 14 | CI não roda `test:skill` | baixa | `.github/workflows/tests.yml` | corrigido (linhas 44-46) |
| 15 | S9 | — | — | corrigido |
| 16 | E1 | — | — | corrigido |
| 17 | E4 | — | — | corrigido |
| 18 | B3 | — | — | corrigido |
| 19 | B4 | — | — | corrigido |
| — | AppSec re-check (3 rodadas) | med (rodada 2: Blade em valor de prop) | MarkupBuilder.php | corrigido em F5 (escape de `{` e `@`, regex com `\z`, `@evento`→`x-on:evento`, `"` vetado em `$`-expressão); rodada 3 `SECURITY: CLEAR` (props e chaves); slots seguem Blade cru por contrato |

Itens 1 e 7 têm PoC. Item 1 foi confirmado por PoC antes da correção.

## Validação

- Verifier round 2: **APPROVED**, 87/89 PASS (FAIL 0, MISSING 0). Os 2 N/P são I1 e REQ-S1, que só se confirmam após o commit (histórico `--follow`).
- Gate final:
  - `pest`: 997 passed (2644 assertions), 0 failed.
  - PHPStan: 0 erros.
  - Pint: ok.
  - `ui:skill --check`: exit 0.
  - Espelho Boost idêntico a `skill/` (diff vazio).
  - `check_bundle`: OK.
- Cobertura: 98,92% nos arquivos novos (gate ≥ 93%). Detalhe em `coverage-report.md`.

## Decisão aberta para o usuário

- **Duplicação de conteúdo no pacote.** `skill/` (~860 KB) e o espelho Boost `resources/boost/skills/devaction-ui-development` (~860 KB) são cópias do mesmo conteúdo. O manifesto `resources/ui-manifest.json` ocupa 356 KB.
- **MCP lê `skill/` do vendor.** Em projeto consumidor, o servidor lê a skill direto de `vendor/`.
- Opções: manter as duas cópias (espelho gerado), ou gerar o espelho só no build do dist. Decisão do usuário.

## Riscos herdados (não resolvidos)

- **U1.** O repo é espelho read-only do monorepo. Bloqueia go-live até a fonte única ser definida.
- **U2.** `laravel/mcp` está em 0.x (v0.6.7). API pode mudar sem aviso de major.

## Commits sugeridos (para o usuário decidir)

1. `fix(ui): pagination usa a chave "Pagination navigation" no aria-label` (`resources/views/components/pagination.blade.php`, `lang/pt_BR.json`, teste).
2. `feat(ui): skill/ com exemplo de cada componente, template e layout, manifesto, comando ui:skill e step test:skill no CI` (`skill/`, `resources/boost/skills/`, `resources/ui-manifest.json`, `src/Console/`, `src/Support/`, `.github/workflows/tests.yml`).
3. `feat(ui): servidor MCP local devaction-ui (consulta, geração e scaffold)` (`src/Mcp/`, `tests/` do MCP, `composer.json`, `testbench.yaml`).
4. `docs: AGENTS.md, README e ADR 001-skill-mcp` (`AGENTS.md`, `README.md`, `docs/adr/001-skill-mcp.md`). Decisão: commit separado de docs, não junto ao 3, para o histórico ficar limpo.

AppSec fechado (rodada 3 CLEAR). Duplicação de `skill/` e espelho no dist segue como decisão aberta (não bloqueia).
