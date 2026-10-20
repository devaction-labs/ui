{{--
    Grupo de sugestões com título em mono. Some quando nenhum item do grupo combina com o texto.

    <x-ui::autocomplete.group heading="Pull requests">…</x-ui::autocomplete.group>
--}}
@props([
    'heading' => null,
])

<div data-autocomplete-group role="group" @if ($heading) aria-label="{{ $heading }}" @endif x-show="groupHasItems($el)" {{ $attributes->class(['flex flex-col']) }}>
    @if ($heading)
        <span class="px-2.5 pt-2 pb-1 font-mono text-[11px] tracking-[0.08em] text-muted uppercase" aria-hidden="true">{{ $heading }}</span>
    @endif
    {{ $slot }}
</div>
