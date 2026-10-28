{{-- Skeleton: loading placeholders for a list of rows and a paragraph — see references/skeleton.md --}}
@php
    $rows = [
        ['title' => 'w-3/5', 'meta' => 'w-2/5'],
        ['title' => 'w-4/5', 'meta' => 'w-1/3'],
        ['title' => 'w-1/2', 'meta' => 'w-2/5'],
    ];
@endphp

<div class="flex w-full max-w-md flex-col gap-6">
    {{-- Main: list rows, shimmer, with label for screen readers --}}
    <x-ui::skeleton.group animate="shimmer" label="Loading pull requests" class="flex w-full flex-col gap-3">
        @foreach ($rows as $row)
            <div class="flex items-center gap-3">
                <x-ui::skeleton class="size-9 shrink-0 rounded-full" />
                <div class="flex-1">
                    <x-ui::skeleton.line class="{{ $row['title'] }}" />
                    <x-ui::skeleton.line class="{{ $row['meta'] }}" />
                </div>
                <x-ui::skeleton class="h-[22px] w-16" />
            </div>
        @endforeach
    </x-ui::skeleton.group>

    {{-- Variation: paragraph with a large heading line, pulse animation --}}
    <div class="w-full">
        <x-ui::skeleton.group animate="pulse">
            <x-ui::skeleton.line size="lg" class="w-2/3" />
            <x-ui::skeleton.line />
            <x-ui::skeleton.line />
            <x-ui::skeleton.line class="w-4/5" />
        </x-ui::skeleton.group>
    </div>
</div>
