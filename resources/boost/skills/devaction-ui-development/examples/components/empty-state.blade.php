{{-- Empty state: shows an empty area with what it is, why it is empty and the next step — see references/empty-state.md --}}
@php
    $project = [
        'label' => 'Projects',
        'heading' => 'Your first project starts here',
        'text' => 'A project brings together the contract, milestone scope, tasks and the PRs that deliver each item.',
    ];

    $search = 'refunds';
@endphp

<div class="flex w-full max-w-2xl flex-col gap-6">
    <x-ui::empty-state
        icon="folder-add"
        :label="$project['label']"
        :heading="$project['heading']"
        :text="$project['text']"
        class="w-full max-w-xl"
    >
        <x-slot:actions>
            <x-ui::button variant="primary" icon="add-01" href="#">Create project</x-ui::button>
            <x-ui::button icon="github" href="#">Import from GitHub</x-ui::button>
        </x-slot:actions>
    </x-ui::empty-state>

    <div class="grid gap-3 sm:grid-cols-2">
        <x-ui::empty-state variant="dashed" icon="search-01" :heading="'Nothing for “'.$search.'”'">
            Try another term or <a href="#" class="text-ink underline underline-offset-4">clear the filters</a>.
        </x-ui::empty-state>

        <x-ui::empty-state variant="dashed" icon="git-pull-request" heading="No PRs to review" text="The AI lets you know when a new one arrives." />
    </div>
</div>
