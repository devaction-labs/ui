# ADR-001 · Skill-fonte em `skill/`, manifesto gerado e MCP em PHP opcional

Status: aceito · 2026-10-27

## Contexto
O Laravel Boost só lê `resources/boost/skills`. O usuário quer a skill e o servidor MCP como entregas separadas, mas coerentes entre si e com o código dos componentes.

## Decisão
- `skill/` é a fonte da verdade (SKILL.md, `references/`, `examples/`).
- `ui:skill` gera o manifesto (`resources/ui-manifest.json`) e o espelho Boost; `ui:skill --check` falha em divergência (teste de drift e `composer test:skill`).
- O servidor MCP `devaction-ui` é escrito em PHP sobre `laravel/mcp`, opcional (só registra se a classe existir), 100% local via stdio, lendo manifesto e arquivos do pacote.
- Tools de autoria (`scaffold-*`) só aparecem no checkout do kit.

## Consequências
- Mais um comando a rodar quando um componente muda; o teste de drift força o esquecimento a falhar.
- Dependência 0.x opcional (`laravel/mcp`): pode quebrar em minor novo, revisar a cada release.
- Uma única fonte alimenta Boost, cópia manual e MCP.

## Alternativas rejeitadas
- Symlink `resources/boost/skills` para `skill/`: quebra no Windows e em zip de distribuição.
- MCP em Node: segundo stack para manter.
- Parsear markdown em runtime: frágil; o manifesto gerado é verificável.
