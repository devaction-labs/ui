{{-- Separator: horizontal line with text, and vertical dividers between metadata items — see references/separator.md --}}
@php
    $items = ['Today', '3 PRs', '12 h logged'];
@endphp

<div class="flex w-full max-w-sm flex-col gap-5 rounded-lg border border-line bg-card p-5">
    <div class="flex flex-col gap-3">
        <h2 class="text-base font-semibold text-ink">Sign in</h2>
        <input type="email" placeholder="Email" class="rounded-md border border-line bg-card px-3 py-2 text-sm text-ink">
        <a href="#" class="text-sm text-ink underline">Continue with email</a>
    </div>

    <x-ui::separator text="or continue with" />

    <div class="flex flex-col gap-2">
        <a href="#" class="rounded-md border border-line px-3 py-2 text-center text-sm text-ink">Google</a>
        <a href="#" class="rounded-md border border-line px-3 py-2 text-center text-sm text-ink">GitHub</a>
    </div>
</div>

<span class="flex items-center gap-2.5 text-[13px] text-muted">
    @foreach ($items as $index => $item)
        @if ($index > 0)
            <x-ui::separator vertical />
        @endif
        <span>{{ $item }}</span>
    @endforeach
</span>
