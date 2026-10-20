{{--
    Linha do tempo de atividade: comentários, eventos e revisões numa conversa só, com o trilho à esquerda.

    <x-ui::activity>
        <x-ui::activity.comment name="Carlos André" color="cyan" when="há 2 dias" role="Autor">
            Hoje o lançamento e a baixa rodam em duas transações.
            <x-slot:footer><x-ui::reactions :reactions="['🚀' => 1]" /></x-slot:footer>
        </x-ui::activity.comment>
        <x-ui::activity.event icon="tag-01" name="Alex" color="violet" when="há 2 dias">
            adicionou <x-ui::badge color="risk" size="sm">risco: dinheiro</x-ui::badge>
        </x-ui::activity.event>
        <x-ui::activity.comment variant="ai" name="DevAction IA" verb="revisou o PR #142" when="há 2 h">…</x-ui::activity.comment>
        <x-slot:composer><x-ui::editor wire:model="draft" /></x-slot:composer>
    </x-ui::activity>

    Slot composer: fica no fim, ligado ao trilho (o avatar vem de composer-name)
--}}
@props([
    'label' => null,
    'composerName' => null,
    'composerColor' => 'auto',
])

<section data-activity aria-label="{{ $label ?? __('Activity') }}" {{ $attributes->class(['relative flex flex-col gap-[18px]']) }}>
    <span aria-hidden="true" class="absolute top-0 bottom-0 left-[19px] w-0.5 bg-line-soft"></span>
    <ol class="relative flex flex-col gap-[18px]">{{ $slot }}</ol>

    @isset($composer)
        <div class="relative flex gap-3.5">
            @if ($composerName)
                <x-ui::avatar :name="$composerName" :color="$composerColor" circle class="shrink-0 ring-4 ring-card" />
            @endif
            <div class="min-w-0 flex-1">{{ $composer }}</div>
        </div>
    @endisset
</section>
