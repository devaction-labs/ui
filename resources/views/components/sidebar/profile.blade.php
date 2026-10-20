{{--
    Pessoa logada no rodapé da sidebar. O slot vira um menu (dropdown) quando preenchido.

    <x-ui::sidebar.profile name="Alex Nogueira" description="DevAction Labs" />
    <x-ui::sidebar.profile name="Alex Nogueira" avatar="/fotos/alex.jpg" description="Admin">
        <x-ui::menu.item icon="logout-01">Sair</x-ui::menu.item>
    </x-ui::sidebar.profile>
--}}
@props([
    'name',
    'description' => null,
    'avatar' => null,
])

@php
    $hasMenu = $slot->isNotEmpty();
@endphp

<div data-sidebar-profile {{ $attributes->class(['border-t border-line-strong pt-2']) }}>
    @if ($hasMenu)
        <x-ui::dropdown position="top" align="start" class="w-full">
            <button type="button" class="flex w-full cursor-pointer items-center gap-2.5 rounded-[10px] px-1.5 py-1.5 text-left transition hover:bg-wrap in-data-collapsed:justify-center in-data-collapsed:px-0" aria-label="Menu de {{ $name }}">
                <x-ui::avatar :name="$name" :src="$avatar" size="sm" color="indigo" circle />
                <span class="flex min-w-0 flex-1 flex-col text-[13px] whitespace-nowrap in-data-collapsed:hidden">
                    <span class="truncate font-medium text-ink">{{ $name }}</span>
                    @if ($description)
                        <span class="truncate text-muted">{{ $description }}</span>
                    @endif
                </span>
                <x-ui::icon name="more-horizontal" class="size-4 text-muted in-data-collapsed:hidden" />
            </button>
            <x-ui::menu>
                {{ $slot }}
            </x-ui::menu>
        </x-ui::dropdown>
    @else
        <div class="flex items-center gap-2.5 px-1.5 py-1.5 in-data-collapsed:justify-center in-data-collapsed:px-0">
            <x-ui::avatar :name="$name" :src="$avatar" size="sm" color="indigo" circle />
            <span class="flex min-w-0 flex-1 flex-col text-[13px] whitespace-nowrap in-data-collapsed:hidden">
                <span class="truncate font-medium text-ink">{{ $name }}</span>
                @if ($description)
                    <span class="truncate text-muted">{{ $description }}</span>
                @endif
            </span>
        </div>
    @endif
</div>
