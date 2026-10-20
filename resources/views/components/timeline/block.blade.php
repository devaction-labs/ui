{{--
    Bloco de largura total dentro de um item, sem indicador: cards, callouts e comentários.

    <x-ui::timeline.item>
        <x-ui::timeline.block>
            <x-ui::callout heading="Alex respondeu" />
        </x-ui::timeline.block>
    </x-ui::timeline.item>
--}}
<div data-timeline-block {{ $attributes->class(['col-span-2 pb-4 group-last/timeline-item:pb-0']) }}>
    {{ $slot }}
</div>
