{{--
    Opção de criar, no fim da lista: aparece quando o texto da busca tem min-length caracteres.

    <x-ui::combobox.option.create wire:click="createTag" min-length="2">
        Criar "<span wire:text="search"></span>"
    </x-ui::combobox.option.create>

    modal: abre esse x-ui::modal em vez de chamar uma ação
--}}
@props([
    'minLength' => 1,
    'modal' => null,
])

<button
    type="button"
    data-combobox-create
    x-show="query.trim().length >= {{ (int) $minLength }}"
    x-cloak
    @if ($modal) x-on:click="close(); $modal(@js($modal)).show()" @else x-on:click="$nextTick(() => query = '')" @endif
    {{ $attributes->class(['flex min-h-[38px] w-full shrink-0 cursor-pointer items-center gap-2.5 rounded-[9px] px-2.5 text-left text-sm text-ink hover:bg-wrap']) }}
>
    <x-ui::icon name="add-01" class="size-4 shrink-0 text-ink-3" />
    <span class="min-w-0 truncate">{{ $slot }}</span>
</button>
