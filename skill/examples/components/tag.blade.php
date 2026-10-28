{{-- Tag: status labels with color, icon and optional uppercase — see references/tag.md --}}
@php
    $statuses = [
        ['color' => 'risk', 'label' => 'At risk'],
        ['color' => 'warn', 'label' => 'AI reviewed'],
        ['color' => 'ok', 'label' => 'On track'],
        ['color' => 'info', 'label' => 'Learning'],
        ['color' => 'plain', 'label' => 'Planned'],
    ];

    $pullRequest = ['number' => '#142', 'version' => 'since v0.1'];
@endphp

<div class="flex flex-col gap-6 rounded-xl border border-line bg-card p-4 text-ink">
    {{-- Main usage: one tag per status, color always paired with text --}}
    <section class="flex flex-col gap-2">
        <h3 class="text-sm font-medium text-ink">Status</h3>
        <div class="flex flex-wrap items-center gap-2">
            @foreach ($statuses as $status)
                <x-ui::tag :color="$status['color']">{{ $status['label'] }}</x-ui::tag>
            @endforeach
        </div>
    </section>

    {{-- Variation: icon and no uppercase, for PR numbers and versions --}}
    <section class="flex flex-col gap-2">
        <h3 class="text-sm font-medium text-ink">Pull request and version</h3>
        <div class="flex flex-wrap items-center gap-2">
            <x-ui::tag color="risk" icon="git-pull-request" :uppercase="false">{{ $pullRequest['number'] }}</x-ui::tag>
            <x-ui::tag :uppercase="false">{{ $pullRequest['version'] }}</x-ui::tag>
        </div>
        <p class="text-sm text-muted">Turn uppercase off for identifiers and versions.</p>
    </section>
</div>
