{{-- Modal: trigger button opens a short form in a dialog, with cancel and save in the footer — see references/modal.md --}}
@php
    $project = 'SGE · M3 Finance';
    $defaultTitle = 'Fix N+1 in contributions';
@endphp

<x-ui::modal.trigger name="new-task">
    <x-ui::button icon="add-01">New task</x-ui::button>
</x-ui::modal.trigger>

<x-ui::modal name="new-task">
    <div class="flex flex-col gap-1">
        <x-ui::heading size="lg">New task</x-ui::heading>
        <x-ui::text class="text-muted">{{ $project }}</x-ui::text>
    </div>

    <div class="mt-5">
        <x-ui::input label="Title" value="{{ $defaultTitle }}" wire:model="title" />
    </div>

    <x-slot:footer>
        <x-ui::spacer />

        <x-ui::modal.close>
            <x-ui::button>Cancel</x-ui::button>
        </x-ui::modal.close>

        <x-ui::button variant="primary" wire:click="create">Create task</x-ui::button>
    </x-slot:footer>
</x-ui::modal>

{{-- Variation: confirmation with a centered notice, no close button, and a required action --}}
@php
    $repo = 'PR #142';
@endphp

<x-ui::modal.trigger name="publish-review">
    <x-ui::button variant="primary" icon="git-pull-request">Publish review</x-ui::button>
</x-ui::modal.trigger>

<x-ui::modal name="publish-review" size="sm" :closable="false" :dismissible="false">
    <div class="flex flex-col items-center gap-3 text-center">
        <span class="flex size-14 items-center justify-center rounded-2xl border border-line bg-card text-ink">
            <x-ui::icon name="git-pull-request" class="size-6" />
        </span>
        <x-ui::heading size="lg">Publish the review on GitHub?</x-ui::heading>
        <x-ui::text class="max-w-[380px] text-muted">2 comments go to {{ $repo }} right away.</x-ui::text>
    </div>

    <x-slot:footer>
        <x-ui::modal.close>
            <x-ui::button>Cancel</x-ui::button>
        </x-ui::modal.close>

        <x-ui::modal.close>
            <x-ui::button variant="primary">Publish</x-ui::button>
        </x-ui::modal.close>
    </x-slot:footer>
</x-ui::modal>
