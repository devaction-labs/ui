## DevAction UI

- This app uses the DevAction UI component kit (`devaction/ui`): anonymous Blade components under the `x-ui::` prefix (e.g. `<x-ui::button>`, `<x-ui::table>`, `<x-ui::modal>`), built on Tailwind CSS v4 tokens, Alpine and Livewire 4, with Hugeicons Stroke Rounded icons.
- Always prefer an existing `x-ui::` component over hand-written markup. Activate the `devaction-ui-development` skill before building UI: it lists every component, its props and slots, and links to a full reference per component.
- The API follows Flux: props, named slots (`<x-slot:actions>`), `wire:model` on the component root, `variant`/`size` props. Do not install or reference Flux itself.
- Style only with the kit's tokens (`bg-card`, `text-ink`, `text-muted`, `border-line`, `bg-ai`, `text-risk-fg`…), never raw palette colors, and never add `dark:` variants: tokens switch with the `.dark` class.
- Icons: `<x-ui::icon name="calendar-03" />` with Hugeicons Stroke Rounded names. Missing icon? Run `php artisan ui:icon {name}`.
- Kit texts follow the app locale (English by default, Portuguese with `pt_BR`); override any of them in `lang/{locale}.json`.
- With `laravel/mcp` installed, the `devaction-ui` MCP server (`php artisan mcp:start devaction-ui`) exposes `list-components`, `get-component`, `generate-component` and `generate-template`: use it to look up props and to get ready-made `x-ui::` markup instead of guessing.
