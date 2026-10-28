---
status: DONE
progress: 3/3
updated: 2026-10-27
---
# E2E regression (mapa de impacto)

Fluxos existentes tocados pela mudança e a prova de que seguem iguais.

| ID | Fluxo tocado | Prova |
|---|---|---|
| R1 | Boost (`boost:install`) lê `resources/boost/skills/devaction-ui-development` | `ui:skill --check` exit 0 ⇒ espelho idêntico a `skill/`; `diff` com `git show main:resources/boost/skills/devaction-ui-development/SKILL.md` só difere pelas seções novas (Primitives/Layouts/Guides) — revisão manual registrada |
| R2 | Boot do pacote (todas as 211 views) em app sem laravel/mcp | suíte atual inteira (`tests/Feature/*ComponentTest.php`) verde vs baseline; I4 |
| R3 | `ui:icon` no mesmo provider | `IconCommandTest` verde (comando registrado ao lado de `ui:skill`) |
