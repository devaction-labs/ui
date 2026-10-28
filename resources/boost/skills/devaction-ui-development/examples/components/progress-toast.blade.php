{{-- Progress toast: long-task progress with fill and completion check — see references/progress-toast.md --}}
@php
    $jobs = [
        ['heading' => 'Syncing repositories', 'value' => 64, 'text' => '4 of 6 · sge-api'],
        ['heading' => 'M3 report ready', 'value' => 100, 'text' => '12-page PDF'],
    ];
@endphp

{{-- Main: in-page toast, one per running job --}}
<div class="flex w-full max-w-md flex-col gap-3">
    @foreach ($jobs as $job)
        <x-ui::toast.progress
            :value="$job['value']"
            :heading="$job['heading']"
            :text="$job['text']"
        />
    @endforeach
</div>

{{-- Variation: floating toast in the stack, advanced by Alpine --}}
<div class="mt-6">
    <x-ui::button
        variant="primary"
        icon="upload-04"
        x-on:click="
            let value = 0;
            const timer = setInterval(() => {
                value = Math.min(100, value + 12);
                DevActionUI.progressToast('import-contacts', {
                    value,
                    heading: 'Importing contacts',
                    text: Math.round(value * 31) + ' of 3,100',
                });
                if (value === 100) clearInterval(timer);
            }, 400);
        "
        aria-label="Import contacts"
    >Import contacts</x-ui::button>
</div>
