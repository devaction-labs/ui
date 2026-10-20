<p align="center">
    <a href="https://github.com/devaction-labs/ui/actions"><img alt="Tests" src="https://github.com/devaction-labs/ui/actions/workflows/tests.yml/badge.svg"></a>
    <a href="https://packagist.org/packages/devaction/ui"><img alt="Downloads" src="https://img.shields.io/packagist/dt/devaction/ui"></a>
    <a href="https://packagist.org/packages/devaction/ui"><img alt="Latest version" src="https://img.shields.io/packagist/v/devaction/ui"></a>
    <a href="https://packagist.org/packages/devaction/ui"><img alt="License" src="https://img.shields.io/packagist/l/devaction/ui"></a>
</p>

# DevAction UI

80 Blade components for Laravel and Livewire, built on Tailwind CSS v4, with light and dark tokens and [Hugeicons Stroke Rounded](https://hugeicons.com/icons?style=Stroke&type=Rounded) icons. The API follows [Flux](https://fluxui.dev): props, named slots and `wire:model` right where you expect them. The kit speaks English and Portuguese (pt_BR).

```blade
<x-ui::button variant="primary" icon="add-01" wire:click="create">New deal</x-ui::button>

<x-ui::select variant="listbox" searchable multiple wire:model.live="industries" placeholder="Industries">
    <x-ui::select.option value="health">Health</x-ui::select.option>
    <x-ui::select.option value="retail">Retail</x-ui::select.option>
</x-ui::select>
```

> This repository is a read-only mirror of `packages/devaction-ui` in the DevAction Labs monorepo. Issues and pull requests are welcome here; changes are merged through the monorepo.

## Requirements

- PHP 8.3+
- Laravel 12 or 13
- Tailwind CSS v4
- Livewire 4 (optional; without it, install Alpine)

## Installation

```bash
composer require devaction/ui
```

Import the CSS right after Tailwind. It ships the tokens, the `dark` variant driven by the `.dark` class and the `@source` for the components themselves:

```css
/* resources/css/app.css */
@import 'tailwindcss';
@import '../../vendor/devaction/ui/resources/css/devaction-ui.css';
```

Import the JavaScript (Alpine already ships with Livewire 4):

```js
// resources/js/app.js
import '../../vendor/devaction/ui/resources/js/devaction-ui.js';
```

Add the toast stack once in your layout:

```blade
<x-ui::toast.group />
```

The tokens use Geist and Geist Mono. Without them the kit falls back to system fonts.

## Components

| Group | Components |
| --- | --- |
| Base | Accordion, Alert, Avatar, Badge, Banner, Button, Callout, Code block, Description list, Empty state, Kbd, Panel, Progress, Reactions, Separator, Skeleton, Spinner, Stat, Tabs, Tag, Timeline |
| Overlay | Command palette, Confirm dialog, Context menu, Cookie consent, Danger dialog, Drawer, Dropdown, Hover card, Modal, Notification center, Popover, Progress toast, Toast group, Tooltip |
| Form | Autocomplete, Choice, Color picker, Combobox, Copy field, Date picker, Date range, Dropzone, Editor, Filter menu, Input group, Money input, Number input, OTP input, Pillbox, Segmented, Select, Signature field, Slider, Switch, Textarea, Timer |
| Navigation | Breadcrumb, Page header, Pagination, Resizable, Sidebar, Stepper, Steps |
| Data | Activity, Calendar, Chart, Checklist, Data table, Diff, File tree, Kanban, PDF viewer, Properties, Sortable list |
| AI | AI prompt, Aura, Chat bubble |
| Templates | Auth, Pricing |
| Layouts | Classic, Rail (classic collapsed), Top bar, List and detail, Centered, Docs |

Each component has a comment at the top of its file in `resources/views/components` with examples and props.

## Livewire

Client-side or server-side search, Flux style:

```blade
<x-ui::combobox wire:model="companyId" :filter="false">
    <x-slot:input>
        <x-ui::combobox.input wire:model.live.debounce.250ms="companySearch" placeholder="Search company…" />
    </x-slot:input>
    @foreach ($this->companies as $company)
        <x-ui::combobox.option :value="$company->id">{{ $company->name }}</x-ui::combobox.option>
    @endforeach
</x-ui::combobox>
```

Modals and toasts without JavaScript:

```php
use DevAction\Ui\Livewire\InteractsWithModals;
use DevAction\Ui\Livewire\InteractsWithToasts;
use DevAction\Ui\Ui;

class EditDeal extends Component
{
    use InteractsWithModals, InteractsWithToasts;

    public function save(): void
    {
        // ...
        $this->modal('edit-deal')->close();
        $this->toast('Deal saved.', variant: 'success');

        Ui::modals()->close(); // from any class running inside a Livewire request
    }
}
```

Tables with sorting and pagination: `<x-ui::table :paginate="$this->orders">`. Inside a Livewire component, pagination uses `setPage()` on its own.

## Layouts

Six ready-made shells that share the same pieces (sidebar, header, page header and content area):

```blade
<x-ui::layout.classic>
    <x-slot:sidebar><x-ui::sidebar>…</x-ui::sidebar></x-slot:sidebar>
    <x-slot:header><x-ui::breadcrumbs>…</x-ui::breadcrumbs></x-slot:header>
    <x-ui::page-header heading="Projects" />
</x-ui::layout.classic>
```

`x-ui::layout.classic` (add `collapsed` for the rail), `x-ui::layout.topbar`, `x-ui::layout.split`, `x-ui::layout.centered` and `x-ui::layout.docs`.

## Themes

Pick a base gray and an accent color with two attributes, on the html tag or on any element:

```blade
<html lang="en" data-ui-base="slate" data-ui-accent="blue">
```

- **Base:** `zinc` (default), `slate`, `gray`, `neutral`, `stone`, `mauve`, `olive`, `mist`, `taupe`. Changes backgrounds, borders and text.
- **Accent:** `red`, `orange`, `amber`, `yellow`, `lime`, `green`, `emerald`, `teal`, `cyan`, `sky`, `blue`, `indigo`, `violet`, `purple`, `fuchsia`, `pink`, `rose`. Without it, primary actions stay graphite. Changes primary buttons, checkboxes, switches, radios, current page, selected dates, progress and the focus ring.
- Status colors (ok, warn, risk, info) never change. Both attributes work in light and dark.

Coherent pairs: slate + blue or indigo, mist + teal, olive + emerald, mauve + violet, stone + orange or amber, taupe + rose.

## Translations

The kit's own texts (placeholders, empty states, aria labels, screen reader announcements) are English and follow your app locale. Portuguese ships in `lang/pt_BR.json`. To change any text, repeat its key in your app's `lang/{locale}.json`, which always wins:

```json
{
    "No results.": "Nothing here yet."
}
```

JavaScript messages follow `<html lang>`. Add another language with `DevActionUI.translations.es = { 'Today': 'Hoy' }`.

## Icons

The kit ships the most used icons. For any other Hugeicons Stroke Rounded icon, pass its name:

```bash
php artisan ui:icon calendar-add-01 user-star-01
```

The file lands in `resources/views/vendor/ui/icons` and works with `<x-ui::icon name="calendar-add-01" />`. The same folder overrides a kit icon.

## Laravel Boost

If your app uses [Laravel Boost](https://github.com/laravel/boost), `php artisan boost:install` picks up the DevAction UI guidelines and skill shipped with this package, so your AI assistant knows every component, prop and best practice.

## Blaze

With [livewire/blaze](https://github.com/livewire/blaze) installed, the package compiles its components into optimized PHP functions and memoizes the icon. Nothing to configure.

```bash
composer require livewire/blaze
```

## Development

Tests run on a minimal Laravel app with [Testbench](https://packages.tools/testbench) and need PHP 8.4+.

```bash
composer lint        # Pint
composer test:lint   # Pint in check mode
composer test:types  # PHPStan
composer test:unit   # Pest
composer test        # everything
```

## License

DevAction UI is made by [DevAction Labs](https://github.com/devaction-labs) and released under the [MIT license](LICENSE).

<p align="center"><sub>All honor <b>and glory</b> to the Lord Jesus.</sub></p>
