{{-- Callout: status variants, inline actions and a dismissable callout — see references/callout.md --}}
<div class="flex w-full max-w-xl flex-col gap-2.5">
    <x-ui::callout icon="information-circle" heading="Closed scope: this contract doesn't accept new items without an amendment." />

    <x-ui::callout variant="success" icon="checkmark-circle-02" heading="M2 accepted on Sep 30 by the client's email." />

    <x-ui::callout variant="danger" icon="alert-02" class="w-full">
        <x-ui::callout.heading>Why it needs you</x-ui::callout.heading>
        <x-ui::callout.text>
            PR #145 adds refunds, which are not in the M3 scope. Without an amendment, it becomes unbilled work.
            <x-ui::callout.link href="#">View the contract</x-ui::callout.link>
        </x-ui::callout.text>
        <x-slot:actions>
            <x-ui::button size="sm" icon="agreement-02">Draft amendment</x-ui::button>
            <x-ui::button size="sm" variant="ghost">Ignore</x-ui::button>
        </x-slot:actions>
    </x-ui::callout>

    <div x-data="{ visible: true }" x-show="visible">
        <x-ui::callout variant="info" icon="notification-03" heading="Meeting with the client at 10am">
            <x-slot:controls>
                <x-ui::button variant="ghost" size="sm" icon="cancel-01" aria-label="Dismiss" x-on:click="visible = false" />
            </x-slot:controls>
        </x-ui::callout>
    </div>
</div>
