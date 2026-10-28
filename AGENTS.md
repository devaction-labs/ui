# AGENTS.md

Guia para agentes (e humanos) que mexem no kit `devaction/ui`. Responda em português (pt-BR); termos de domínio ficam como estão.

## 1. Projeto

- **O que é:** kit de componentes Blade anônimos (`<x-ui::*>`) para Laravel, com API no estilo Flux. Componentes em `resources/views/components`.
- **Stack:** PHP 8.3+ (pacote), Laravel 12/13, Livewire 4, Tailwind CSS v4 (tokens), Alpine (vem com Livewire), Hugeicons Stroke Rounded.
- **Testes:** precisam de PHP 8.4+. Local: Herd `php85` (`~/Library/Application Support/Herd/bin/php85`).
- **Comandos:**
  - `composer lint`: Pint (escreve).
  - `composer test`: `test:lint` (Pint --test) + `test:types` (PHPStan) + `test:skill` (`ui:skill --check`) + `test:unit` (Pest).
  - `vendor/bin/testbench ui:skill`: gera o manifesto e o espelho Boost (`--check` só confere, não grava).
- **Fonte da verdade:** `skill/` (SKILL.md, `references/`, `examples/`).
- **GERADO, nunca editar à mão:**
  - `resources/boost/skills/devaction-ui-development/` (espelho para o Laravel Boost), gerado por `ui:skill`.
  - `resources/ui-manifest.json`, gerado por `ui:skill`.
  - Se mudar `skill/`, rode `ui:skill` e commite o gerado junto.

## 2. Criar um componente novo

Checklist obrigatório, nesta ordem:

**a. Blade** `resources/views/components/{name}.blade.php` (ou `{name}/index.blade.php` + filhos `{name}/{child}.blade.php`).
Cabeçalho `{{-- --}}` com:
- uma linha de descrição;
- exemplos de uso `<x-ui::…>`;
- uma linha por prop no formato `prop: valores · …`.

Depois, `@props([...])`. Veja `resources/views/components/button.blade.php` como modelo.

**b. Teste Pest** `tests/Feature/{Studly}ComponentTest.php`, no estilo de `SeparatorComponentTest.php`:
- `Blade::render('<x-ui::…>…</x-ui::…>')`;
- asserte ganchos estáveis: `data-*`, `aria-*`, `wire:model`, classes de token. Não asserte copy traduzida (os testes rodam em `pt_BR`).

**c. Reference** `skill/references/{name}.md`, em inglês, com front matter:

```yaml
---
kind: component
group: Base        # Base | Overlay | Form | Navigation | Data | AI | Primitives
components: [name, name.child]
---
```

Seções obrigatórias: `## Examples`, `## API`, `## Accessibility`.

**d. Exemplo** `skill/examples/components/{name}.blade.php`:
- primeira linha: `{{-- Título: o que mostra — see references/{name}.md --}}`;
- autocontido: dados em `@php` inline, sem `$this->`, links `href="#"`;
- em inglês, só tokens do kit, sem `dark:`, botão só-ícone com `aria-label`.

**e. Índice** em `skill/SKILL.md`, sob o grupo correto (`### {group}`). Cada reference entra **uma única vez**.

**f. Textos** do kit via `__()` com chave em inglês, e tradução em `lang/pt_BR.json`.

**g. Validar:** `vendor/bin/testbench ui:skill` e depois `composer test`, tudo verde.

**Atalho:** a tool MCP `scaffold-component` (só no checkout do kit: `vendor/bin/testbench mcp:start devaction-ui`) gera a–e (5 alvos: blade, teste, reference, exemplo e linha no índice do `SKILL.md`) e roda `ui:skill`. Obrigatórios: `name`, `group` e `summary`. Rode primeiro com `dry_run` (default `true`), depois com `dry_run: false`. Você ainda deve revisar e completar o que ela gerou (marcadores TODO).

## 3. Atualizar um componente

Qualquer mudança de props, slots, sub-componentes ou comportamento entra **na mesma mudança**:
- cabeçalho `{{-- --}}`;
- `@props`;
- teste Pest;
- reference (tabela de API e exemplos);
- exemplo em `skill/examples/`;
- lista `components:` do front matter, se um filho foi criado ou removido.

Depois rode `ui:skill` e `composer test`.

**Breaking change:** remover ou renomear prop, slot ou tag é quebra de API. Registre no changelog com a versão afetada (o repo ainda não tem `CHANGELOG.md`; hoje a nota vai no README/PR).

## 4. Templates e layouts (mesma regra)

**Template** (página pronta, ex.: `auth`, `pricing`):
- Blade em `resources/views/components/{name}/…` (ex.: `auth/login.blade.php`, `pricing/index.blade.php`);
- reference `skill/references/{name}.md` com `kind: template` e `group: Templates`;
- exemplos em `skill/examples/templates/{name}.blade.php`, mais `{name}.livewire.blade.php` se suportar Livewire;
- incluir o nome na tabela do gerador `generate-template` (`src/Mcp/Tools/GenerateTemplateTool.php`).

**Layout** (casca de página, ex.: `classic`, `topbar`):
- Blade em `resources/views/components/layout/{name}.blade.php`;
- documentado em `skill/references/layouts.md` (`kind: layout`), sem reference nova;
- exemplo em `skill/examples/layouts/{name}.blade.php`;
- incluir no `generate-template` se for exposto pela tool.

**Atalho:** `scaffold-template` (obrigatórios: `name`, `kind` = `template` ou `layout`, e `summary`; só no checkout do kit), também com `dry_run` primeiro. Template gera 5 alvos (`{name}/index.blade.php`, teste, reference, exemplo e índice do `SKILL.md`); layout gera blade, teste, exemplo e atualiza `skill/references/layouts.md` (lista `components:` e seção TODO). Depois, inclua o nome no mapa de `GenerateTemplateTool`.

## 5. Regras de ouro

- **Reuse antes de escrever:** procure no índice do `SKILL.md` antes de criar algo novo.
- **API estilo Flux:** props para opções (`variant`, `size`, `icon`, `label`), slots nomeados (`<x-slot:actions>`), sub-componentes com ponto (`x-ui::table.row`).
- **`wire:model` no root** do componente. Campos e seletores usam `x-modelable`.
- **Tokens, não cores:** `bg-card`, `text-ink`, `border-line`, `bg-ok-bg text-ok-fg` etc. Nunca `bg-red-500` nem `dark:`. O `.dark` no `<html>` troca os tokens.
- **Ícones:** `<x-ui::icon name="…" />` com nome Hugeicons em kebab-case. Ícone faltando: `php artisan ui:icon {nome}`, nunca SVG desenhado à mão.
- **Acessibilidade:** `aria-label` em botão só-ícone, `label` em campo, não remover focus ring, status nunca só por cor (sempre texto).
- **Traduções:** `__()` com chave em inglês; overrides em `lang/{locale}.json`.

## 6. Guardas automáticas

Falham se o padrão quebrar:

- `tests/Feature/SkillCoverageTest.php`: cada `.blade.php` em `resources/views/components` (exceto `icons/`) é reivindicado por exatamente uma reference; front matter válido; cada reference linkada 1× no `SKILL.md`.
- `tests/Feature/SkillExamplesTest.php`: todo exemplo em `skill/examples/**` renderiza com `Blade::render()` sem exceção.
- `tests/Feature/SkillExamplesLintTest.php`: tokens, sem `dark:`, `aria-label` em botão só-ícone; reference e exemplo pareados.
- `tests/Feature/SkillCommandTest.php` e `ui:skill --check`: manifesto e espelho Boost em sincronia.

Se uma guarda falhar, corrija o artefato. Não relaxe o teste.

## 7. Git

- Branches a partir de `main`. Não commite direto em `main`.
- Conventional Commits em português, como no histórico: `feat(ui): …`, `fix(ui): …`, `chore: …`.
- Não commite arquivo gerado fora de sincronia: rode `ui:skill` antes e inclua o resultado.
- Não commite, não faça push, não faça merge sem pedido explícito do usuário.
