{{-- Confirm dialog: yes/no gate before actions that leave the app, with color tones — see references/confirm-dialog.md --}}
{{-- Publish to GitHub: color="ai" for actions that take the AI's work outside the app. --}}
<x-ui::modal.trigger name="confirm-publish">
    <x-ui::button variant="primary" icon="git-pull-request">Publish review</x-ui::button>
</x-ui::modal.trigger>

<x-ui::confirm-dialog
    name="confirm-publish"
    color="ai"
    icon="git-pull-request"
    heading="Publish the review to GitHub?"
    text="2 comments will go to PR #142 as DevAction AI. Carlos André gets notified right away."
    confirm="Publish"
    confirm:wire:click="publish"
/>

{{-- Variation: success tone, with the default icon for the tone. --}}
<x-ui::modal.trigger name="confirm-delivery">
    <x-ui::button icon="checkmark-circle-02">Mark M2 as delivered</x-ui::button>
</x-ui::modal.trigger>

<x-ui::confirm-dialog
    name="confirm-delivery"
    color="success"
    heading="Mark M2 as delivered?"
    text="The client gets notified and installment 3 is released for billing."
    confirm="Mark delivered"
    cancel="Not now"
/>
