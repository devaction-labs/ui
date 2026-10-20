{{--
    Painel do date picker (mês, grade de dias e atalhos). Uso interno: vive dentro do x-data uiDatePicker.
--}}
@props([
    'range' => false,
    'presets' => true,
])

<div data-date-calendar {{ $attributes->class(['flex w-full max-w-[19rem] flex-col gap-2.5']) }}>
    <div class="flex items-center justify-between">
        <button type="button" x-on:click="shiftMonth(-1)" aria-label="Mês anterior" class="flex size-[34px] cursor-pointer items-center justify-center rounded-[9px] border border-line bg-card text-ink transition hover:bg-subtle">
            <x-ui::icon name="arrow-left-01" class="size-3.5" />
        </button>
        <span class="font-semibold text-ink first-letter:uppercase" x-text="monthLabel()" aria-live="polite"></span>
        <button type="button" x-on:click="shiftMonth(1)" aria-label="Próximo mês" class="flex size-[34px] cursor-pointer items-center justify-center rounded-[9px] border border-line bg-card text-ink transition hover:bg-subtle">
            <x-ui::icon name="arrow-right-01" class="size-3.5" />
        </button>
    </div>

    <div role="grid" x-bind:aria-label="monthLabel()" class="grid grid-cols-7 gap-y-0.5 text-center" x-on:mouseleave="hovered = null">
        <template x-for="weekday in weekdays()" x-bind:key="weekday">
            <span role="columnheader" class="py-1 font-mono text-[11px] text-muted" x-text="weekday"></span>
        </template>
        <template x-for="cell in days()" x-bind:key="cell.key">
            <div role="gridcell" class="flex justify-center" x-bind:class="isInRange(cell.iso) && 'bg-wrap'">
                <template x-if="cell.iso">
                    <button
                        type="button"
                        x-bind:data-date="cell.iso"
                        x-text="cell.day"
                        x-on:click="pick(cell.iso)"
                        x-on:mouseenter="hovered = cell.iso"
                        x-on:keydown.arrow-left.prevent="moveFocus(-1)"
                        x-on:keydown.arrow-right.prevent="moveFocus(1)"
                        x-on:keydown.arrow-up.prevent="moveFocus(-7)"
                        x-on:keydown.arrow-down.prevent="moveFocus(7)"
                        x-bind:tabindex="cell.iso === focused ? 0 : -1"
                        x-bind:disabled="isDisabled(cell.iso)"
                        x-bind:aria-pressed="isSelected(cell.iso)"
                        x-bind:aria-label="dayLabel(cell.iso)"
                        x-bind:aria-current="cell.iso === today ? 'date' : null"
                        class="h-9 w-full cursor-pointer rounded-lg text-[13px] text-ink transition outline-none hover:bg-subtle focus-visible:ring-2 focus-visible:ring-ring disabled:cursor-default disabled:text-line-hover disabled:hover:bg-transparent"
                        x-bind:class="{
                            'bg-ink! font-semibold text-on-ink!': isSelected(cell.iso),
                            'font-semibold ring-1 ring-ink ring-inset': cell.iso === today && ! isSelected(cell.iso),
                            'rounded-none hover:bg-transparent': isInRange(cell.iso),
                        }"
                    ></button>
                </template>
            </div>
        </template>
    </div>

    @if ($presets)
        <div class="flex flex-wrap gap-1.5 border-t border-line-soft pt-2">
            @if ($range)
                <button type="button" x-on:click="preset(0, 6)" class="inline-flex h-7 cursor-pointer items-center rounded-full bg-wrap px-2.5 text-xs text-ink transition hover:bg-line">Últimos 7 dias</button>
                <button type="button" x-on:click="preset(0, 29)" class="inline-flex h-7 cursor-pointer items-center rounded-full bg-wrap px-2.5 text-xs text-ink transition hover:bg-line">Últimos 30 dias</button>
                <button type="button" x-on:click="preset(6)" class="inline-flex h-7 cursor-pointer items-center rounded-full bg-wrap px-2.5 text-xs text-ink transition hover:bg-line">Próximos 7 dias</button>
            @else
                <button type="button" x-on:click="preset(0)" class="inline-flex h-7 cursor-pointer items-center rounded-full bg-wrap px-2.5 text-xs text-ink transition hover:bg-line">Hoje</button>
                <button type="button" x-on:click="preset(1)" class="inline-flex h-7 cursor-pointer items-center rounded-full bg-wrap px-2.5 text-xs text-ink transition hover:bg-line">Amanhã</button>
                <button type="button" x-on:click="preset(nextWeekday(5))" class="inline-flex h-7 cursor-pointer items-center rounded-full bg-wrap px-2.5 text-xs text-ink transition hover:bg-line">Sexta</button>
                <button type="button" x-on:click="preset(7)" class="inline-flex h-7 cursor-pointer items-center rounded-full bg-wrap px-2.5 text-xs text-ink transition hover:bg-line">+7 dias</button>
            @endif
            {{ $slot }}
        </div>
    @endif
</div>
