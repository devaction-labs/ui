---
id: T-004
status: DONE
progress: 6/6
updated: 2026-10-27
depends_on: T-002, T-003
---
# T-004 · `ComponentManifest`, `SkillMirror`, `ui:skill`

REQ-F3..F6. Spec: [spec/03](../spec/03-manifest.md), [spec/04](../spec/04-technical.md).

## Passos (TDD)
1. RED `tests/Unit/ComponentManifestTest.php`: (U7) `table.row` → group `Data`, reference `skill/references/data-table.md`, `example` herdado do pai; (U8) `build()` 2× → `json_encode` idêntico; (U9) ordem por `name`, chaves ordenadas.
2. `src/Support/ComponentManifest.php`: `build()` percorre `resources/views/components/**/*.blade.php` (exceto `icons/`), chama `BladeComponentParser`, junta front matter (reference dona via `components:`), `summary` = 1º parágrafo após o `# título` da reference, `example` = `skill/examples/{components|templates|layouts}/{ref}.blade.php` se existir senão `null`. `load()` lê `resources/ui-manifest.json` com memo estático; `find($nameOrReference)`; `search(?group, ?query)` com `Str::ascii(Str::lower())`.
3. `src/Support/SkillMirror.php`: compara árvore `skill/` × `resources/boost/skills/devaction-ui-development/` (faltando, sobrando, conteúdo diferente) → `diff(): list<string>`; `sync()` copia e remove sobras **só dentro do espelho** (nunca fora dele).
4. `src/Console/SkillCommand.php` `ui:skill {--check}` no estilo de `IconCommand` (`$this->components->info/error`). Sem `--check`: escreve JSON (REQ-F4 flags) + `sync()`. Com `--check`: não escreve; diverge → lista + `FAILURE`.
5. Registrar no `UiServiceProvider::boot()` junto de `IconCommand`.
6. Rodar `vendor/bin/testbench ui:skill`; commitar nada. Teste `tests/Feature/SkillCommandTest.php`: (V4) repo em sincronia → exit 0; (V5) manifesto alterado em cópia temporária → exit 1 com caminho; (V6) arquivo extra no espelho → exit 1. Usar `--path`-like via binding de raiz temporária (`ComponentManifest` recebe raiz no construtor; default `dirname(__DIR__, 2)`).

## Done quando
- U7–U9, V4–V6 verdes; `resources/ui-manifest.json` e espelho Boost gerados; `diff -r skill resources/boost/skills/devaction-ui-development` vazio.
- Tamanho do JSON registrado (Swept/performance).
