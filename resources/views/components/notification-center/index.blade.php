{{--
    Central de notificações: abas com não lidas, agrupada por dia, marcar uma ou todas como lidas.
    Use solta numa página ou dentro de um x-ui::popover aberto pelo sino.

    <x-ui::notification-center :tabs="['all' => 'Tudo', 'mention' => 'Menções', 'review' => 'Reviews']" mark-all="markAllAsRead">
        <x-ui::notification-center.group heading="Hoje">
            <x-ui::notification-center.item type="review" icon="sparkles" color="warn" unread meta="sge-api · há 4 min" wire:click="open(1)">
                A IA terminou o review do #214: 2 achados bloqueantes
            </x-ui::notification-center.item>
        </x-ui::notification-center.group>
        <x-slot:footer><a href="/notificacoes">Ver todas</a></x-slot:footer>
    </x-ui::notification-center>

    tabs:     [chave => rótulo]; "all" mostra tudo, as outras filtram pelo type do item
    mark-all: método do Livewire de "Marcar tudo como lido"; sempre dispara notifications-read-all
    empty:    texto quando a aba não tem nada
--}}
@props([
    'heading' => 'Notificações',
    'tabs' => ['all' => 'Tudo'],
    'markAll' => null,
    'empty' => 'Nada por aqui.',
])

<section
    data-notification-center
    aria-label="{{ $heading }}"
    x-data="uiNotificationCenter({ tab: @js(array_key_first($tabs)), markAll: @js($markAll) })"
    {{ $attributes->class(['flex w-full flex-col overflow-hidden bg-card text-ink']) }}
>
    <div class="flex items-center px-4 pt-4">
        <h2 class="text-[17px] font-semibold">{{ $heading }}</h2>
        <button type="button" x-on:click="readAll()" x-bind:disabled="unread('all') === 0" class="ml-auto cursor-pointer text-[13px] text-ink transition hover:underline disabled:cursor-default disabled:text-muted disabled:no-underline">Marcar tudo como lido</button>
    </div>

    @if (count($tabs) > 1)
        <div role="tablist" aria-label="Filtrar notificações" class="flex gap-4 border-b border-line-soft px-4 pt-3">
            @foreach ($tabs as $tabKey => $tabLabel)
                <button
                    type="button"
                    role="tab"
                    x-on:click="tab = @js($tabKey)"
                    x-bind:aria-selected="(tab === @js($tabKey)).toString()"
                    x-bind:class="tab === @js($tabKey) ? 'border-ink font-medium text-ink' : 'border-transparent text-muted hover:text-ink'"
                    class="-mb-px flex h-[34px] cursor-pointer items-center gap-1.5 border-b-2 text-sm transition"
                >
                    {{ $tabLabel }}
                    <span x-show="unread(@js($tabKey))" x-text="unread(@js($tabKey))" class="inline-flex h-[18px] min-w-[18px] items-center justify-center rounded-full bg-ink px-[5px] text-[11px] text-on-ink"></span>
                </button>
            @endforeach
        </div>
    @endif

    <div class="max-h-[520px] overflow-y-auto">
        {{ $slot }}
        <p x-show="visibleCount() === 0" x-cloak class="px-4 py-10 text-center text-sm text-muted">{{ $empty }}</p>
    </div>

    @isset($footer)
        <div class="flex items-center gap-3 border-t border-line-soft px-4 py-3 text-[13px] text-ink-3 [&_a]:font-medium [&_a]:text-ink [&_a:last-child]:ml-auto">{{ $footer }}</div>
    @endisset
</section>
