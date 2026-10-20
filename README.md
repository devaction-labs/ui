<p align="center">
    <a href="https://github.com/devaction-labs/ui/actions"><img alt="Testes" src="https://github.com/devaction-labs/ui/actions/workflows/tests.yml/badge.svg"></a>
    <a href="https://packagist.org/packages/devaction/ui"><img alt="Downloads" src="https://img.shields.io/packagist/dt/devaction/ui"></a>
    <a href="https://packagist.org/packages/devaction/ui"><img alt="Última versão" src="https://img.shields.io/packagist/v/devaction/ui"></a>
    <a href="https://packagist.org/packages/devaction/ui"><img alt="Licença" src="https://img.shields.io/packagist/l/devaction/ui"></a>
</p>

# DevAction UI

76 componentes Blade para Laravel e Livewire, com Tailwind CSS v4, tokens claro e escuro e ícones [Hugeicons Stroke Rounded](https://hugeicons.com/icons?style=Stroke&type=Rounded). A API segue a do [Flux](https://fluxui.dev): props, slots nomeados e `wire:model` onde você espera.

```blade
<x-ui::button variant="primary" icon="add-01" wire:click="create">Novo negócio</x-ui::button>

<x-ui::select variant="listbox" searchable multiple wire:model.live="industries" placeholder="Setores">
    <x-ui::select.option value="saude">Saúde</x-ui::select.option>
    <x-ui::select.option value="varejo">Varejo</x-ui::select.option>
</x-ui::select>
```

> Este repositório é um espelho somente leitura de `packages/devaction-ui` do monorepo da DevAction Labs. Issues e pull requests são bem-vindos aqui; as mudanças entram pelo monorepo.

## Requisitos

- PHP 8.3+
- Laravel 12 ou 13
- Tailwind CSS v4
- Livewire 4 (opcional; sem ele, instale o Alpine)

## Instalação

```bash
composer require devaction/ui
```

Importe o CSS logo depois do Tailwind. Ele traz os tokens, a variante `dark` pela classe `.dark` e o `@source` dos próprios componentes:

```css
/* resources/css/app.css */
@import 'tailwindcss';
@import '../../vendor/devaction/ui/resources/css/devaction-ui.css';
```

Importe o JavaScript (o Alpine já vem com o Livewire 4):

```js
// resources/js/app.js
import '../../vendor/devaction/ui/resources/js/devaction-ui.js';
```

Coloque a pilha de toasts uma vez no layout:

```blade
<x-ui::toast.group />
```

As fontes dos tokens são Geist e Geist Mono. Sem elas o kit usa as fontes do sistema.

## Componentes

| Grupo | Componentes |
| --- | --- |
| Base | Accordion, Alert, Avatar, Badge, Banner, Button, Callout, Code block, Description list, Empty state, Kbd, Panel, Progress, Separator, Skeleton, Spinner, Stat, Tabs, Tag, Timeline |
| Overlay | Command palette, Confirm dialog, Context menu, Cookie consent, Danger dialog, Drawer, Dropdown, Hover card, Modal, Notification center, Popover, Progress toast, Toast group, Tooltip |
| Form | Autocomplete, Choice, Color picker, Combobox, Copy field, Date picker, Date range, Dropzone, Editor, Filter menu, Input group, Money input, Number input, OTP input, Pillbox, Segmented, Select, Signature field, Slider, Switch, Textarea, Timer |
| Navigation | Breadcrumb, Page header, Pagination, Resizable, Sidebar, Stepper, Steps |
| Data | Calendar, Chart, Data table, Diff, File tree, Kanban, PDF viewer, Sortable list |
| AI | AI prompt, Aura, Chat bubble |
| Templates | Auth, Pricing |

Cada componente tem um comentário no topo do arquivo em `resources/views/components` com exemplos e as props.

## Livewire

Busca local ou no servidor, como no Flux:

```blade
<x-ui::combobox wire:model="companyId" :filter="false">
    <x-slot:input>
        <x-ui::combobox.input wire:model.live.debounce.250ms="companySearch" placeholder="Buscar empresa…" />
    </x-slot:input>
    @foreach ($this->companies as $company)
        <x-ui::combobox.option :value="$company->id">{{ $company->name }}</x-ui::combobox.option>
    @endforeach
</x-ui::combobox>
```

Modais e toasts sem JavaScript:

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
        $this->toast('Negócio salvo.', variant: 'success');

        Ui::modals()->close(); // de qualquer classe chamada na requisição do Livewire
    }
}
```

Tabelas com ordenação e paginação: `<x-ui::table :paginate="$this->orders">`. Dentro de um componente Livewire a paginação usa `setPage()` sozinha.

## Ícones

O kit traz os ícones mais usados. Para qualquer outro do Hugeicons Stroke Rounded, passe o nome:

```bash
php artisan ui:icon calendar-add-01 user-star-01
```

O arquivo vai para `resources/views/vendor/ui/icons` e funciona em `<x-ui::icon name="calendar-add-01" />`. A mesma pasta sobrescreve um ícone do kit.

## Blaze

Com [livewire/blaze](https://github.com/livewire/blaze) instalado, o pacote compila os próprios componentes em funções PHP e memoiza o ícone. Não há nada para configurar.

```bash
composer require livewire/blaze
```

## Desenvolvimento

Os testes rodam num Laravel mínimo com o [Testbench](https://packages.tools/testbench) e precisam de PHP 8.4+.

```bash
composer lint        # Pint
composer test:lint   # Pint em modo de verificação
composer test:types  # PHPStan
composer test:unit   # Pest
composer test        # tudo
```

## Licença

DevAction UI foi criado pela [DevAction Labs](https://github.com/devaction-labs) e é distribuído sob a [licença MIT](LICENSE).

<p align="center"><sub>All honor <b>and glory</b> to the Lord Jesus.</sub></p>
