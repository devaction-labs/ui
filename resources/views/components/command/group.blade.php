{{--
    Grupo de opções com título em mono. Some quando nenhuma opção do grupo bate com a busca.

    <x-ui::command.group heading="Projetos">…</x-ui::command.group>
--}}
@props([
    'heading' => null,
])

<div
    role="group"
    @if ($heading) aria-label="{{ $heading }}" @endif
    x-show="groupHasResults($el)"
    {{ $attributes->class(['flex flex-col']) }}
>
    @if ($heading)
        <span aria-hidden="true" class="px-2.5 pt-2.5 pb-1 font-mono text-[10px] tracking-[.08em] text-muted uppercase">{{ $heading }}</span>
    @endif
    {{ $slot }}
</div>
