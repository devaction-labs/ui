{{-- Toast group: stacked short notices after an action — see references/toast-group.md --}}
{{-- Main usage: one toast group in the layout, toasts fired from Alpine with $toast(). --}}
<div class="flex flex-wrap items-center gap-3 rounded-lg border border-line bg-card p-4 text-ink">
    <p class="w-full text-sm text-muted">Fire a few toasts and hover the stack in the bottom right corner.</p>

    <x-ui::button variant="primary" x-on:click="$toast({ text: 'Changes saved.', variant: 'success' })">Saved</x-ui::button>
    <x-ui::button x-on:click="$toast({ text: 'The AI finished reviewing #214.', variant: 'ai' })">AI</x-ui::button>
    <x-ui::button x-on:click="$toast({ text: 'GitHub API is slow, retrying.', variant: 'warning' })">Warning</x-ui::button>
    <x-ui::button x-on:click="$toast({ text: 'Failed to publish the review.', variant: 'danger' })">Error</x-ui::button>
</div>

{{-- Variation: heading plus action button (fires the event on window, Livewire listens with #[On]). --}}
@php
    $deleted = ['heading' => 'Task deleted', 'text' => 'Task #88 moved to trash.', 'label' => 'Undo', 'event' => 'undo-delete'];
@endphp

<div class="mt-4 flex flex-wrap items-center gap-3 rounded-lg border border-line bg-card p-4 text-ink">
    <x-ui::button
        variant="primary"
        x-on:click="$toast({
            heading: @js($deleted['heading']),
            text: @js($deleted['text']),
            variant: 'default',
            action: { label: @js($deleted['label']), event: @js($deleted['event']) },
        })"
    >Delete task</x-ui::button>
</div>

{{-- Stack once per page, before </body>. --}}
<x-ui::toast.group position="bottom end" />
