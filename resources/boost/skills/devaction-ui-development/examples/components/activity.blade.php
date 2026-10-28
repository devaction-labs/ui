{{-- Activity: task thread with a comment, system events, an AI review comment and a composer — see references/activity.md --}}
@php
    $checks = [
        ['label' => 'Post and settle in the same transaction', 'checked' => true],
        ['label' => 'Roll back if the receipt fails', 'checked' => true],
        ['label' => 'Generate the receipt after the commit', 'checked' => false],
    ];
@endphp

<x-ui::activity composer-name="Alex" composer-color="violet" class="w-full max-w-2xl">
    <x-ui::activity.comment name="Carlos André" color="cyan" when="2 days ago" role="Author">
        <p>Posting the installment and settling the cash account run in two transactions. If the second one fails, the installment stays posted without the settlement. Proposal: wrap both in DB::transaction.</p>
        <x-ui::checklist :progress="false">
            @foreach ($checks as $check)
                <x-ui::checklist.item :checked="$check['checked']">{{ $check['label'] }}</x-ui::checklist.item>
            @endforeach
        </x-ui::checklist>
        <x-slot:footer>
            <x-ui::reactions :reactions="['🚀' => 1]" />
        </x-slot:footer>
    </x-ui::activity.comment>

    <x-ui::activity.event icon="tag-01" color="risk" name="Alex" avatar-color="violet" when="2 days ago">
        added <x-ui::badge color="risk" size="sm">risk: money</x-ui::badge>
    </x-ui::activity.event>
    <x-ui::activity.event icon="arrow-right-01" color="info" name="Fernando Pinto" avatar-color="amber" when="yesterday">
        moved from To do to <x-ui::badge color="info" size="sm">In progress</x-ui::badge>
    </x-ui::activity.event>

    <x-ui::activity.comment variant="ai" name="DevAction AI" verb="reviewed PR #142" when="2 h ago">
        <x-slot:badge><x-ui::badge color="risk" size="sm">2 issues</x-ui::badge></x-slot:badge>
        <p class="font-medium text-ink">The receipt is generated inside the transaction: if the PDF fails, the entry rolls back.</p>
        <p class="font-mono text-xs text-muted">app/Actions/PostInstallment.php:88</p>
    </x-ui::activity.comment>

    {{-- Variation: a plain follow-up comment marked as edited, with reactions --}}
    <x-ui::activity.comment name="Fernando Pinto" color="amber" when="1 h ago" edited>
        <p>The client wants the receipt right away. Can we keep that with the receipt after the commit?</p>
        <x-slot:footer>
            <x-ui::reactions :reactions="['👍' => 1, '🎉' => 1]" :selected="['👍']" />
        </x-slot:footer>
    </x-ui::activity.comment>

    <x-slot:composer>
        <x-ui::editor toolbar="bold italic code | bullet | link" placeholder="Write a comment…" />
    </x-slot:composer>
</x-ui::activity>
