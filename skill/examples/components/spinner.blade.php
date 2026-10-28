{{-- Spinner: ring and dots loading indicators, with a screen-reader label — see references/spinner.md --}}
@php
    $loading = [
        ['name' => 'Orders export', 'status' => 'Preparing the file'],
        ['name' => 'Customer import', 'status' => 'Reading 1,200 rows'],
    ];
@endphp

<div class="flex flex-col gap-6 rounded-lg border border-line bg-card p-6 text-ink">
    <section class="flex flex-col gap-3">
        <h2 class="text-sm font-semibold">Ring (default)</h2>

        @foreach ($loading as $job)
            <div class="flex items-center gap-3 rounded-md border border-line p-3">
                <x-ui::spinner :label="'Loading '.$job['name']" />
                <div class="flex flex-col">
                    <span class="text-sm font-medium">{{ $job['name'] }}</span>
                    <span class="text-xs text-muted">{{ $job['status'] }}</span>
                </div>
            </div>
        @endforeach
    </section>

    <section class="flex flex-col gap-3">
        <h2 class="text-sm font-semibold">Dots in AI color</h2>

        <div class="flex items-center gap-3 rounded-md border border-line p-3">
            <x-ui::spinner variant="dots" size="sm" color="ai" label="The AI is reviewing the answer" />
            <span class="text-sm text-muted">AI reviewing the answer…</span>
        </div>
    </section>
</div>
