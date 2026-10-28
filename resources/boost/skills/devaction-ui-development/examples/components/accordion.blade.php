{{-- Accordion: collapsible sections with dividers, chevron and status cards — see references/accordion.md --}}
@php
    $faqs = [
        ['heading' => 'Does the AI keep the code from my repositories?', 'body' => 'No. It reads the PR diff, reviews it and discards it. Only the comments and generated lessons are stored.', 'expanded' => true],
        ['heading' => 'Who sees reviews before they go to GitHub?', 'body' => 'Anyone with the reviewer role on the project. In draft mode, nothing is published without approval.'],
        ['heading' => 'Reviewing private client PRs', 'body' => 'Available once the client authorizes the GitHub App in their organization.', 'disabled' => true],
    ];

    $milestones = [
        ['title' => 'M3 · Finance', 'meta' => 'due Fri, Oct 16 · 2 open PRs', 'expanded' => true],
        ['title' => 'M4 · Reports and pastoral dashboard', 'meta' => '18 items planned for November.'],
    ];
@endphp

<div class="flex w-full max-w-xl flex-col gap-6">
    {{-- Default: dividers and chevron on the right; exclusive keeps one item open at a time --}}
    <x-ui::accordion exclusive transition class="w-full">
        @foreach ($faqs as $faq)
            <x-ui::accordion.item :heading="$faq['heading']" :expanded="$faq['expanded'] ?? false" :disabled="$faq['disabled'] ?? false">
                {{ $faq['body'] }}
            </x-ui::accordion.item>
        @endforeach
    </x-ui::accordion>

    {{-- Variation: cards with status; trailing slot sits outside the button --}}
    <x-ui::accordion variant="cards" class="w-full">
        @foreach ($milestones as $milestone)
            <x-ui::accordion.item :expanded="$milestone['expanded'] ?? false">
                <x-ui::accordion.heading>
                    <span class="flex flex-col">
                        <span class="font-semibold">{{ $milestone['title'] }}</span>
                        <span class="text-[13px] font-normal text-muted">{{ $milestone['meta'] }}</span>
                    </span>
                    <x-slot:trailing>
                        <x-ui::tag color="risk">At risk</x-ui::tag>
                        <x-ui::button variant="ghost" size="sm" icon="more-vertical" aria-label="More actions for {{ $milestone['title'] }}" />
                    </x-slot:trailing>
                </x-ui::accordion.heading>
                <x-ui::accordion.content>
                    Donation entries, cash reconciliation and the treasurer report.
                </x-ui::accordion.content>
            </x-ui::accordion.item>
        @endforeach
    </x-ui::accordion>
</div>
