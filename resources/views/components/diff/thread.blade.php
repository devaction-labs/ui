{{--
    Conversa presa a uma linha do x-ui::diff. Resolver recolhe a conversa (o + da linha reabre).

    <x-ui::diff.thread line="18" resolve="resolveThread(7)">…comentários…</x-ui::diff.thread>

    resolve:  expressão do Livewire chamada ao Resolver (ex.: resolveThread(7)); sempre dispara thread-resolved
    resolved: começa recolhida
--}}
@props([
    'line',
    'resolve' => null,
    'resolved' => false,
])

<div
    data-diff-thread="{{ (int) $line }}"
    x-init="registerThread({{ (int) $line }}, @js((bool) $resolved))"
    x-show="isThreadOpen({{ (int) $line }})"
    style="order: var(--diff-line-{{ (int) $line }}, 99999)"
    class="border-y border-line-soft bg-subtle py-2.5 pr-4 pl-[110px] font-sans text-sm leading-normal"
>
    <div class="max-w-[680px] overflow-hidden rounded-xl border border-line bg-card">
        {{ $slot }}
        <div class="p-3">
            <x-ui::diff.composer :line="(int) $line" reply>
                <x-ui::button size="sm" x-on:click="resolveThread({{ (int) $line }})" :wire:click="$resolve">{{ __('Resolve') }}</x-ui::button>
            </x-ui::diff.composer>
        </div>
    </div>
</div>
