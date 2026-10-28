{{-- Stat: highlighted number with label, trend and context — see references/stat.md --}}
@php
    $stats = [
        ['label' => 'Ready to ship', 'value' => '78.4', 'unit' => '%', 'trend' => '+6 pts', 'description' => 'since Monday'],
        ['label' => 'AI reviewed, waiting on you', 'value' => '29', 'color' => 'ai', 'description' => 'scope items'],
        ['label' => 'No PR yet', 'value' => '17', 'color' => 'risk', 'description' => '3 due this week'],
        ['label' => 'Highlight', 'value' => '4', 'variant' => 'inverted', 'description' => 'PRs awaiting review'],
    ];
@endphp

<x-ui::stat.group columns="4" class="w-full">
    @foreach ($stats as $stat)
        <x-ui::stat
            :label="$stat['label']"
            :value="$stat['value']"
            :unit="$stat['unit'] ?? null"
            :trend="$stat['trend'] ?? null"
            :color="$stat['color'] ?? 'default'"
            :variant="$stat['variant'] ?? 'card'"
            :description="$stat['description']"
        />
    @endforeach
</x-ui::stat.group>

{{-- Variation: plain stats with icons inside a panel --}}
<x-ui::panel heading="Open work" class="w-full max-w-md">
    <div class="grid grid-cols-2 gap-4">
        <x-ui::stat label="Open PRs" value="6" icon="git-pull-request" variant="plain" />
        <x-ui::stat label="Lessons" value="3" icon="mortarboard-02" color="ai" variant="plain" />
    </div>
</x-ui::panel>
