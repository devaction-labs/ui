{{-- Hover card: card that opens on hover or focus with person and PR context — see references/hover-card.md --}}
@php
    $person = ['name' => 'Alex', 'description' => 'Dev · DevAction Labs', 'stats' => ['open PRs' => 4, 'this week' => '32 h', 'projects' => 2]];
    $pr = ['number' => 214, 'repo' => 'sge-api', 'title' => 'Migrate old milestones with due_on', 'files' => 7, 'added' => 166, 'removed' => 68];
@endphp
<p class="max-w-md text-[15px] leading-[1.7] text-ink">
    <x-ui::hover-card>
        <a href="#" class="font-medium underline decoration-dotted underline-offset-4">{{ $person['name'] }}</a>
        <x-slot:card>
            <x-ui::hover-card.person :name="$person['name']" :description="$person['description']" online :stats="$person['stats']" />
        </x-slot:card>
    </x-ui::hover-card>
    requested a review of
    <x-ui::hover-card position="top" align="center">
        <a href="#" class="font-mono text-sm font-medium underline decoration-dotted underline-offset-4">#{{ $pr['number'] }}</a>
        <x-slot:card class="w-[320px]">
            <span class="flex items-center gap-2">
                <x-ui::icon name="git-pull-request" class="size-4 text-ok-fg" />
                <span class="font-mono text-xs text-muted">{{ $pr['repo'] }} #{{ $pr['number'] }}</span>
                <x-ui::tag color="risk" size="sm" :uppercase="false" class="ml-auto">2 blocking</x-ui::tag>
            </span>
            <span class="mt-2 block font-semibold text-ink">{{ $pr['title'] }}</span>
            <span class="mt-1.5 block text-[13px] text-ink-3">{{ $pr['files'] }} files · <span class="text-ok-fg">+{{ $pr['added'] }}</span> <span class="text-risk-fg">−{{ $pr['removed'] }}</span> · M3</span>
        </x-slot:card>
    </x-ui::hover-card>
    in the SGE project.
</p>
