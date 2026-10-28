{{-- Timer: running time tracker with a stop event that shows a toast, plus a mini variant — see references/timer.md --}}
<div class="flex flex-col gap-6 rounded-lg border border-line bg-card p-4 text-ink">
    <x-ui::timer
        label="Fix N+1 in contributions · SGE"
        :seconds="2710"
        x-on:timer-stopped="$toast({ text: 'Logged: ' + Math.round($event.detail.seconds / 60) + ' min', variant: 'success' })"
        class="w-full max-w-sm"
    />

    {{-- Variation: compact timer for the app bar, already running --}}
    <x-ui::timer variant="mini" label="Mini timer in the app bar" :seconds="1520" running class="w-full max-w-sm" />
</div>
