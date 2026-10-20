{{--
    Pilha de toasts. Coloque uma vez no layout, antes do </body>.

    <x-ui::toast.group />
    <x-ui::toast.group position="top end" />
    <x-ui::toast.group :toasts="session('toasts', [])" />    toasts vindos de um redirect

    Dispare de qualquer lugar:
      JS:       DevActionUI.toast({ text: 'Alterações salvas.', variant: 'success' })   ou   $toast({...}) no Alpine
      Livewire: $this->toast('Alterações salvas.', variant: 'success')   (trait InteractsWithToasts)

    variant:  default · success · ai · warning · danger · loading (não some sozinho)
    duration: ms (padrão 5000); 0 não some
    id:       mesmo id atualiza o toast (carregando → sucesso)
    action:   { label: 'Desfazer', event: 'undo-delete', params: {...} } dispara um evento no window
--}}
@props([
    'position' => 'bottom end',
    'toasts' => [],
    'max' => 12,
])

@php
    [$vertical, $horizontal] = array_pad(explode(' ', trim($position)), 2, 'end');
    $fromTop = $vertical === 'top';

    $placementClasses = match ($horizontal) {
        'start' => 'left-4',
        'center' => 'left-1/2 -translate-x-1/2',
        default => 'right-4',
    };
@endphp

<section
    data-toast-group
    aria-label="Notificações"
    x-data="uiToastGroup({ max: {{ (int) $max }}, toasts: @js(array_values($toasts)) })"
    x-on:mouseenter="expand()"
    x-on:mouseleave="collapse()"
    x-on:focusin="expand()"
    x-on:focusout="$nextTick(() => collapse())"
    x-on:keydown.alt.t.window.prevent="focusStack()"
    {{ $attributes->class([
        'pointer-events-none fixed z-[60] w-[360px] max-w-[calc(100vw-2rem)]',
        $fromTop ? 'top-4' : 'bottom-4',
        $placementClasses,
    ]) }}
>
    <ol class="relative m-0 list-none p-0 transition-[height] duration-200" x-bind:style="{ height: stackHeight() + 'px' }">
        <template x-for="(toast, index) in toasts" x-bind:key="toast.id">
            <li
                data-toast
                tabindex="0"
                x-bind:role="toast.variant === 'danger' ? 'alert' : 'status'"
                x-bind:aria-live="toast.variant === 'danger' ? 'assertive' : 'polite'"
                x-init="$nextTick(() => heights[toast.id] = $el.offsetHeight)"
                x-bind:style="style(toast, index, @js($fromTop))"
                x-on:keydown.escape.stop="dismiss(toast.id)"
                x-on:pointerdown="swipeStart(toast, $event)"
                x-on:pointermove="swipeMove(toast, $event)"
                x-on:pointerup="swipeEnd(toast)"
                x-on:pointercancel="swipeEnd(toast)"
                x-transition:enter="transition duration-200 ease-out motion-reduce:transition-none"
                x-transition:enter-start="{{ $fromTop ? '-translate-y-2' : 'translate-y-2' }} opacity-0"
                x-transition:leave="transition duration-150 ease-in motion-reduce:transition-none"
                x-transition:leave-end="opacity-0"
                @class([
                    'dark absolute inset-x-0 flex min-h-[52px] touch-pan-y items-start gap-3 rounded-[14px] border bg-card px-4 py-3.5 text-sm text-ink shadow-[0_12px_30px_rgb(9_9_11/0.25)] outline-none transition-[transform,opacity] duration-200 focus-visible:ring-2 focus-visible:ring-ring',
                    $fromTop ? 'top-0 origin-top' : 'bottom-0 origin-bottom',
                ])
                x-bind:class="toast.variant === 'danger' ? 'border-risk-line' : 'border-line'"
            >
                <span class="mt-px flex shrink-0" x-show="toast.variant !== 'default'">
                    <template x-if="toast.variant === 'success'"><x-ui::icon name="checkmark-circle-02" class="size-[18px] text-ok-fg" /></template>
                    <template x-if="toast.variant === 'ai'"><x-ui::icon name="sparkles" class="size-[18px] text-ai" /></template>
                    <template x-if="toast.variant === 'warning'"><x-ui::icon name="alert-02" class="size-[18px] text-warn-fg" /></template>
                    <template x-if="toast.variant === 'danger'"><x-ui::icon name="cancel-circle" class="size-[18px] text-risk-fg" /></template>
                    <template x-if="toast.variant === 'loading'"><x-ui::spinner size="sm" color="current" class="text-muted" /></template>
                </span>

                <div class="flex min-w-0 flex-1 flex-col gap-0.5">
                    <strong class="font-semibold" x-show="toast.heading" x-text="toast.heading"></strong>
                    <span x-bind:class="toast.heading ? 'text-ink-2' : ''" x-text="toast.text"></span>
                    <template x-if="toast.progress !== null">
                        <span class="mt-2 block h-1 overflow-hidden rounded-full bg-line" role="progressbar" aria-valuemin="0" aria-valuemax="100" x-bind:aria-valuenow="toast.progress">
                            <span class="block h-full rounded-full bg-ai transition-[width] duration-300 motion-reduce:transition-none" x-bind:style="{ width: Math.min(100, toast.progress) + '%' }"></span>
                        </span>
                    </template>
                </div>

                <template x-if="toast.action">
                    <button
                        type="button"
                        x-on:click="runAction(toast)"
                        x-text="toast.action.label"
                        class="-my-1 h-[30px] shrink-0 cursor-pointer rounded-lg bg-line-strong px-2.5 text-xs font-medium text-ink transition hover:bg-line-hover"
                    ></button>
                </template>

                <button
                    type="button"
                    x-on:click="dismiss(toast.id)"
                    aria-label="Fechar notificação"
                    class="-my-1 -mr-1.5 inline-flex size-[30px] shrink-0 cursor-pointer items-center justify-center rounded-lg text-muted transition hover:bg-line-soft hover:text-ink"
                >
                    <x-ui::icon name="cancel-01" class="size-4" />
                </button>
            </li>
        </template>
    </ol>
</section>
