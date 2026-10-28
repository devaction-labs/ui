{{-- Pagination: numbered pages over a length-aware paginator, plus a simple paginator — see references/pagination.md --}}
@php
    $paginator = new \Illuminate\Pagination\LengthAwarePaginator(range(1, 10), 120, 10, 3, ['path' => '#']);
@endphp

<div class="flex flex-col gap-6 rounded-lg border border-line bg-card p-4 text-ink">
    <x-ui::pagination :paginator="$paginator" class="w-full" />

    {{-- Variation: simple paginator, previous and next only, with no total count --}}
    @php
        $simple = new \Illuminate\Pagination\Paginator(range(1, 11), 10, 3, ['path' => '#']);
    @endphp
    <x-ui::pagination :paginator="$simple" class="w-full" />
</div>
