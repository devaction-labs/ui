{{-- Alert: status variants, plus an alert with an action and a close control — see references/alert.md --}}
<div class="flex w-full max-w-xl flex-col gap-2.5">
    <x-ui::alert variant="success" heading="GitHub connected" text="6 repositories synced." />
    <x-ui::alert variant="ai" heading="The AI is reviewing" text="Takes about 1 minute per PR." />
    <x-ui::alert variant="info" heading="Tip" text="Link the PR to the task with DIA-123 in the title." />

    <x-ui::alert variant="danger" heading="Could not publish the review" class="w-full max-w-xl">
        GitHub rejected the token. Reconnect the integration to try again.
        <x-slot:actions>
            <x-ui::button size="sm" icon="github" href="#">Reconnect GitHub</x-ui::button>
        </x-slot:actions>
        <x-slot:controls>
            <x-ui::button variant="ghost" size="sm" icon="cancel-01" aria-label="Dismiss alert" wire:click="dismiss" />
        </x-slot:controls>
    </x-ui::alert>
</div>
