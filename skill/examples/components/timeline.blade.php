{{-- Timeline: PR events with colored dots, plus a block with an AI callout — see references/timeline.md --}}
@php
    $events = [
        ['heading' => 'PR #142 opened', 'meta' => 'carlos-andre · 09:12', 'color' => null],
        ['heading' => 'AI reviewed: 2 issues', 'meta' => '09:14', 'color' => 'amber'],
        ['heading' => 'Alex requested changes', 'meta' => '09:40', 'color' => 'red'],
    ];
@endphp

<x-ui::timeline class="w-full max-w-md">
    @foreach ($events as $event)
        <x-ui::timeline.item>
            <x-ui::timeline.indicator :color="$event['color']" />
            <x-ui::timeline.content :heading="$event['heading']" :meta="$event['meta']" />
        </x-ui::timeline.item>
    @endforeach

    <x-ui::timeline.item>
        <x-ui::timeline.block>
            <x-ui::callout variant="ai" icon="sparkles" heading="Wrap the entry in a database transaction" text="If the second write fails, the cash account is left without the credit." />
        </x-ui::timeline.block>
    </x-ui::timeline.item>
</x-ui::timeline>
