---
status: DONE
progress: 0/0
updated: 2026-10-27
---
# Load (k6)

N/A — o MCP roda por stdio, local, um processo por cliente; não há endpoint HTTP para carga. Performance coberta por design: manifesto pré-gerado e memoizado (spec/04). Medição simples na T-004: tamanho do JSON e tempo de `ComponentManifest::load()` (< 50 ms esperado; registrar, não bloquear).
