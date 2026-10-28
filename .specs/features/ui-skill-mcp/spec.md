---
status: DONE
progress: 5/5
updated: 2026-10-27
---
# Spec · ui-skill-mcp — skill local + MCP local do DevAction UI

Branch `spec/ui-skill-mcp` (de `main` @ 1512ebc). Issue kind: **feature** (Laya `issue_kind` 0.06 < gate 0.30 → escalado, classificado pela LLM). Breaking: Laya 0.09 < corte 0.10 (sem sinal; contrato conferido em [04](spec/04-technical.md#compat)).

## Intent
Duas partes separadas, 100% locais (sem rede, stdio):
1. **Skill** — pasta `skill/` na raiz: `SKILL.md` + `references/` + `examples/` com um exemplo renderizável de **cada componente, template e layout**. Fonte única; `resources/boost/skills/devaction-ui-development` vira espelho gerado.
2. **MCP** — servidor `devaction-ui` (laravel/mcp, `Mcp::local`) com tools de **consumo** (listar, detalhar, gerar markup de componente/template) e de **autoria** (scaffold de componente/template novo no kit).
Ambas leem o mesmo **manifesto** `resources/ui-manifest.json`, gerado dos `.blade` + front matter das references.

## Decided (usuário, 2026-10-27)
| # | Decisão | Escolha |
|---|---|---|
| D1 | Skill | Nova `skill/` na raiz; Boost = espelho gerado (sem duplicar à mão) |
| D2 | Escopo MCP | Consumo + autoria |
| D3 | Runtime | PHP via `laravel/mcp` (`php artisan mcp:start devaction-ui`) |
| D4 | Fonte única | Manifesto gerado dos `.blade`; teste falha se desatualizado |

## Index
| Arquivo | Conteúdo | REQs |
|---|---|---|
| [spec/01-skill-folder.md](spec/01-skill-folder.md) | Parte 1 — pasta skill, references, examples | REQ-S1..S7 |
| [spec/02-mcp-server.md](spec/02-mcp-server.md) | Parte 2 — servidor e 6 tools | REQ-M1..M10 |
| [spec/03-manifest.md](spec/03-manifest.md) | Manifesto + comando `ui:skill` + espelho Boost | REQ-F1..F6 |
| [spec/04-technical.md](spec/04-technical.md) | Arquitetura, UML, Mermaid, contratos JSON Schema | — |
| [spec/05-research-impact.md](spec/05-research-impact.md) | RCG, reuse, edge cases, impacto, gaps, ADR, Swept, Unresolved | — |
| [tasks/README.md](tasks/README.md) | 12 tasks atômicas (DAG) | — |
| [tests.md](tests.md) | Design de testes (9 tipos) | — |

## §0 Pre-flight (prior-art gate)
- [x] `git log --all --diff-filter=D -- '.specs/*'` → vazio (nenhum bundle anterior).
- [x] `git log -S'mcp'` → vazio (nenhum MCP prévio).
- [x] Prior art achado: skill Boost `resources/boost/skills/devaction-ui-development` (SKILL.md 193 l + 83 references) — **reaproveitada** (movida, não reescrita).
- [x] `head -1` de 211 `.blade.php` → 211/211 têm cabeçalho `{{-- --}}` com exemplos e props.
- [x] Gap de references: 11 primitivos sem doc (`field label error description heading text eyebrow icon logo main spacer`); demais nomes são aliases (card→panel, table→data-table…).
- [x] API `laravel/mcp` v0.6.5 lida em `~/PhpstormProjects/backend/vendor/laravel/mcp` (`Registrar::local`, `StartCommand mcp:start`, `Primitive::eligibleForRegistration→shouldRegister`, `Server::tool()` + `TestResponse::assertSee/assertHasErrors`).
- [x] Boost descobre skill de pacote só em `{pkg}/resources/boost/skills` (`laravel/boost` v2.4.3 `Support/Composer::packagesDirectoriesWithBoostSkills`).
- [x] Sem DB: pacote não tem schema/migrations → regras de DB não se aplicam.
- [ ] Baseline de testes: `vendor/` ausente; PHP local 8.3 < 8.4 exigido. Herd tem `php85` → T-001 roda `composer install` + `composer test` e registra baseline.

## Observable
- `ls skill/examples/**/*.blade.php | wc -l` = 96 (81 componentes + 9 templates + 6 layouts); cada um renderiza sem exceção.
- `vendor/bin/testbench ui:skill --check` → exit 0 sem drift; exit 1 + lista de arquivos com drift.
- `diff -r skill resources/boost/skills/devaction-ui-development` → vazio.
- Cliente MCP (stdio) `tools/list` → 4 tools no app consumidor; 6 tools no checkout do kit.
- `tools/call generate-component {"name":"button","props":{"variant":"primary"},"slot":"Save"}` → `<x-ui::button variant="primary">Save</x-ui::button>`.
- `tools/call scaffold-component` com `dry_run` omitido → nenhum arquivo escrito; lista de arquivos planejados.
- Nenhuma tool abre conexão de rede (`Http::preventStrayRequests()` + grep `Http::|curl_|file_get_contents\('http` em `src/Mcp` = 0).

## Skills (verdicts, Phase 3)
| Skill | Verdict |
|---|---|
| tlc-spec-driven | aplicado (este bundle) |
| security-engineer | **aplicável** — tools de autoria escrevem arquivo: path traversal, overwrite, escrita em `vendor/`. Mitigação em REQ-M7..M9. Painel julgado inline pelo orquestrador (skill não carregada — economia de contexto). |
| code-quality | **aplicável** — PHPStan nível 7 + Pint em `src/Mcp`, `src/Support/ComponentManifest*`, `src/Console/SkillCommand`; parser isolado das tools (SRP). |
| performance | **aplicável, baixo** — manifesto pré-computado (JSON), lido 1× por processo; nada parseia `.blade` por chamada. Sem k6 (stdio, sem HTTP). |
| domain-expert-erp | não aplicável — sem regra de negócio ERP; PSR-4 mantido (`DevAction\Ui\Mcp\…`). |
| mysql | não aplicável — pacote sem banco. |
| frontend-architect | OFF — nenhuma tela/componente visual novo; exemplos só compõem componentes existentes. Consistência visual coberta pelo lint de tokens (tests/ui-design.md). |
| context7-mcp | substituído por leitura direta do `vendor/laravel/mcp` v0.6.5 local (evidência mais forte). |
