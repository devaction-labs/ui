{{-- Avatar: people with status, and a stacked group — see references/avatar.md --}}
@php
    $team = ['Ana Souza', 'Bruno Lima', 'Carla Dias', 'Diego Rocha', 'Elisa Prado', 'Fábio Reis'];
@endphp

<div class="flex w-full max-w-md flex-col gap-4">
    {{-- Main usage: person row with status dot --}}
    <div class="flex items-center gap-3 rounded-xl border border-line bg-card px-3 py-2.5">
        <x-ui::avatar name="Alex Nogueira" color="indigo" circle badge badge:color="green" badge:label="Online" />
        <span class="flex flex-1 flex-col">
            <span class="font-medium text-ink">Alex Nogueira</span>
            <span class="text-[13px] text-muted">alex@devaction.com.br</span>
        </span>
        <x-ui::tag>Admin</x-ui::tag>
    </div>

    <div class="flex items-center gap-3 rounded-xl border border-line bg-card px-3 py-2.5">
        <x-ui::avatar name="Carlos André" color="emerald" circle badge badge:color="amber" badge:label="Away" />
        <span class="flex flex-1 flex-col">
            <span class="font-medium text-ink">Carlos André</span>
            <span class="text-[13px] text-muted">carlos@devaction.com.br</span>
        </span>
        <x-ui::tag>Member</x-ui::tag>
    </div>

    {{-- Variation: stacked group, collapsed with +N --}}
    <x-ui::avatar.group max="3" size="xs" label="Compact, in tables">
        @foreach ($team as $person)
            <x-ui::avatar :name="$person" color="auto" size="xs" circle />
        @endforeach
    </x-ui::avatar.group>

    {{-- Icon-only avatar needs aria-label --}}
    <x-ui::avatar icon="sparkles" color="ai" circle aria-label="AI agent" />
</div>
