{{-- Danger dialog: type-to-confirm destructive dialog with a simple red variant — see references/danger-dialog.md --}}
@php
    $repo = 'sge-api';
    $draftCount = 2;
@endphp

<div class="flex flex-col gap-6 rounded-lg border border-line bg-card p-6 text-ink">
    {{-- Main: the action unlocks only after the person types the repository name --}}
    <div class="flex flex-col gap-3">
        <h3 class="text-base font-semibold">Repository monitoring</h3>
        <p class="text-sm text-muted">Stop automatic review for {{ $repo }}.</p>

        <x-ui::modal.trigger name="stop-watching">
            <x-ui::button variant="danger">Stop watching</x-ui::button>
        </x-ui::modal.trigger>

        <x-ui::danger-dialog
            name="stop-watching"
            heading="Stop watching {{ $repo }}?"
            text="Automatic review stops for this repository and the {{ $draftCount }} draft reviews are discarded. PR history and lessons stay saved."
            resource="{{ $repo }}"
            confirm="Stop watching"
            confirm:wire:click="stopWatching"
        />
    </div>

    <hr class="border-line">

    {{-- Variation: no resource, so it becomes a simple red confirmation --}}
    <div class="flex flex-col gap-3">
        <h3 class="text-base font-semibold">Review draft</h3>
        <p class="text-sm text-muted">Low-impact destructive action, no typing required.</p>

        <x-ui::modal.trigger name="discard-draft">
            <x-ui::button icon="delete-02">Discard draft</x-ui::button>
        </x-ui::modal.trigger>

        <x-ui::danger-dialog
            name="discard-draft"
            icon="delete-02"
            heading="Discard the review draft?"
            text="The {{ $draftCount }} unpublished comments will be deleted."
            confirm="Discard"
            confirm:wire:click="discardDraft"
        />
    </div>
</div>
