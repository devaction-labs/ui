{{-- Choice: checkboxes, radio groups and checkbox groups for picking one or many options — see references/choice.md --}}
<div class="flex flex-col gap-8 rounded-lg border border-line bg-card p-4 text-ink">
    <div class="flex w-full max-w-md flex-col">
        <x-ui::checkbox label="Select all repositories" indeterminate />
        <x-ui::checkbox label="sge-api" description="Watched since March." checked />
        <x-ui::checkbox label="hub-api" description="No open PRs." />
        <x-ui::checkbox label="ayram-web" description="Available on the Agency plan." disabled />
    </div>

    <x-ui::radio.group label="Contract model" name="model" class="w-full max-w-md">
        <x-ui::radio value="fixed" label="Fixed scope" description="Fixed price per delivered milestone." checked />
        <x-ui::radio value="hours" label="Hour bank" description="Bills what was used in the month." />
        <x-ui::radio value="retainer" label="Retainer" description="Monthly fee with included hours." />
    </x-ui::radio.group>

    {{-- Variation: radio cards with icons, and checkbox groups as lists or pills --}}
    <x-ui::radio.group label="How to publish the review" variant="cards" name="publishing" class="w-full max-w-xl">
        <x-ui::radio value="draft" icon="file-edit" label="As a draft" description="You approve it before it goes to GitHub." checked />
        <x-ui::radio value="direct" icon="sent" label="Straight to the PR" description="The AI publishes as soon as it finishes." />
    </x-ui::radio.group>

    <div class="grid w-full max-w-xl gap-6">
        <x-ui::checkbox.group label="Notify via" description="Choose where to receive review notifications.">
            <x-ui::checkbox value="email" label="Email" checked />
            <x-ui::checkbox value="whatsapp" label="WhatsApp" />
            <x-ui::checkbox value="slack" label="Slack" />
        </x-ui::checkbox.group>

        <x-ui::checkbox.group label="Stack" variant="pills">
            <x-ui::checkbox value="elixir" label="Elixir" checked />
            <x-ui::checkbox value="phoenix" label="Phoenix" checked />
            <x-ui::checkbox value="laravel" label="Laravel" />
            <x-ui::checkbox value="livewire" label="Livewire" />
        </x-ui::checkbox.group>
    </div>
</div>
