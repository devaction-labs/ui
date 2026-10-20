{{--
    Editor de texto rico (API no estilo Flux), sem dependências.

    <x-ui::editor wire:model="notes" label="Notas da entrega" description="Vai para o cliente junto com o aceite." />
    <x-ui::editor toolbar="bold italic | bullet ordered | link" placeholder="Escreva um comentário…" />

    toolbar: itens separados por espaço, grupos por |
             heading bold italic strike code bullet ordered blockquote codeblock link undo redo
    "/" numa linha vazia abre o menu de comandos (título, lista, citação, código e os seus em commands).
    "@" abre as pessoas de mentions e insere a menção (<span data-mention="valor">@Nome</span>).

    mentions:       [['value' => 'alex', 'label' => 'Alex', 'description' => 'Dev · online agora']]
    mention-search: propriedade do Livewire que recebe o texto depois do @ (para buscar pessoas no servidor)
    commands:       comandos extras do "/": [['label' => '/tarefa', 'description' => 'Criar tarefa', 'icon' => 'task-01', 'name' => 'task']]
                    disparam editor-command com { name } e apagam o texto do comando
    Slot actions: botões no fim da barra (ex.: IA). wire:model recebe o HTML; sanitize no servidor.
--}}
@props([
    'label' => null,
    'description' => null,
    'error' => null,
    'placeholder' => null,
    'toolbar' => 'heading | bold italic strike code | bullet ordered blockquote codeblock | link | undo redo',
    'value' => '',
    'name' => null,
    'mentions' => [],
    'mentionSearch' => null,
    'commands' => [],
])

@php
    $placeholder ??= __('Write here… Type / for commands');
    $modelName = collect($attributes->getAttributes())
        ->first(fn ($attributeValue, string $key): bool => str_starts_with($key, 'wire:model'));
    $fieldName = $name ?? $modelName;
    $errorBag = \Illuminate\Support\Facades\View::shared('errors');
    $errorMessage = $error ?? ($fieldName && $errorBag ? $errorBag->first($fieldName) : null);
    $editorId = $attributes->get('id') ?? 'ui-editor-'.($fieldName ? \Illuminate\Support\Str::slug($fieldName) : substr(md5((string) $label), 0, 8));

    $buttons = [
        'bold' => ['icon' => 'text-bold', 'label' => __('Bold (⌘B)'), 'click' => "run('bold')", 'state' => 'active.bold'],
        'italic' => ['icon' => 'text-italic', 'label' => __('Italic (⌘I)'), 'click' => "run('italic')", 'state' => 'active.italic'],
        'strike' => ['icon' => 'text-strikethrough', 'label' => __('Strikethrough'), 'click' => "run('strikeThrough')", 'state' => 'active.strikeThrough'],
        'code' => ['icon' => 'source-code', 'label' => __('Code'), 'click' => 'toggleCode()', 'state' => 'active.code'],
        'bullet' => ['icon' => 'left-to-right-list-bullet', 'label' => __('List'), 'click' => "run('insertUnorderedList')", 'state' => 'active.insertUnorderedList'],
        'ordered' => ['icon' => 'left-to-right-list-number', 'label' => __('Numbered list'), 'click' => "run('insertOrderedList')", 'state' => 'active.insertOrderedList'],
        'blockquote' => ['icon' => 'quote-down', 'label' => __('Quote'), 'click' => "setBlock(block === 'blockquote' ? 'p' : 'blockquote')", 'state' => "block === 'blockquote'"],
        'codeblock' => ['icon' => 'code', 'label' => __('Code block'), 'click' => "setBlock(block === 'pre' ? 'p' : 'pre')", 'state' => "block === 'pre'"],
        'link' => ['icon' => 'link-01', 'label' => 'Link', 'click' => 'link()', 'state' => 'active.link'],
        'undo' => ['icon' => 'undo-02', 'label' => __('Undo (⌘Z)'), 'click' => "run('undo')", 'state' => null],
        'redo' => ['icon' => 'redo-02', 'label' => __('Redo (⇧⌘Z)'), 'click' => "run('redo')", 'state' => null],
    ];
    $groups = collect(explode('|', $toolbar))
        ->map(fn (string $group): array => array_values(array_filter(explode(' ', trim($group)))))
        ->filter()
        ->values();
    $blocks = ['p' => __('Paragraph'), 'h2' => __('Heading'), 'h3' => __('Subheading')];
    $slashItems = [
        ['label' => __('Heading'), 'icon' => 'heading-01', 'hint' => '##', 'click' => "chooseSlash('formatBlock', 'h2')"],
        ['label' => __('Subheading'), 'icon' => 'text-font', 'hint' => '###', 'click' => "chooseSlash('formatBlock', 'h3')"],
        ['label' => __('List'), 'icon' => 'left-to-right-list-bullet', 'hint' => '-', 'click' => "chooseSlash('insertUnorderedList')"],
        ['label' => __('Numbered list'), 'icon' => 'left-to-right-list-number', 'hint' => '1.', 'click' => "chooseSlash('insertOrderedList')"],
        ['label' => __('Quote'), 'icon' => 'quote-down', 'hint' => '>', 'click' => "chooseSlash('formatBlock', 'blockquote')"],
        ['label' => __('Code block'), 'icon' => 'code', 'hint' => '```', 'click' => "chooseSlash('formatBlock', 'pre')"],
    ];

    foreach ($commands as $command) {
        $slashItems[] = [
            'label' => $command['label'],
            'icon' => $command['icon'] ?? 'command',
            'hint' => $command['description'] ?? '',
            'click' => 'runCommand('.\Illuminate\Support\Js::from($command['name'] ?? ltrim($command['label'], '/')).')',
        ];
    }
@endphp

<div data-field @class(['flex min-w-0 flex-col gap-1.5', $attributes->get('class')])>
    @if ($label)
        <x-ui::label :for="$editorId">{{ $label }}</x-ui::label>
    @endif
    @if ($description)
        <x-ui::description>{{ $description }}</x-ui::description>
    @endif

    <div
        data-editor
        x-data="uiEditor({ value: @js($value), mentionSearch: @js($mentionSearch) })"
        x-modelable="value"
        {{ $attributes->except(['id', 'class'])->class([
            'relative mt-1 rounded-xl border bg-card transition',
            'border-missing ring-4 ring-missing/10' => $errorMessage,
            'border-line focus-within:border-ink focus-within:ring-4 focus-within:ring-ink/10' => ! $errorMessage,
        ]) }}
    >
        @if ($name)
            <input type="hidden" name="{{ $name }}" value="{{ $value }}" x-bind:value="value" />
        @endif

        <div role="toolbar" aria-label="{{ __('Formatting') }}" aria-controls="{{ $editorId }}" class="flex flex-wrap items-center gap-0.5 rounded-t-xl border-b border-line bg-subtle px-2 py-1.5">
            @foreach ($groups as $group)
                @if (! $loop->first)
                    <span class="mx-1 h-[18px] w-px bg-line" aria-hidden="true"></span>
                @endif
                @foreach ($group as $item)
                    @if ($item === 'heading')
                        <div class="relative" x-data="{ menu: false }" x-on:click.outside="menu = false" x-on:keydown.escape.stop="menu = false">
                            <button
                                type="button"
                                x-on:mousedown.prevent
                                x-on:click="menu = ! menu"
                                aria-haspopup="menu"
                                x-bind:aria-expanded="menu"
                                aria-label="{{ __('Text style') }}"
                                class="inline-flex h-[34px] cursor-pointer items-center gap-1 rounded-lg px-2 text-[13px] text-ink-2 transition hover:bg-wrap hover:text-ink"
                            >
                                <span x-text="@js($blocks)[block] ?? @js(__('Paragraph'))">{{ __('Paragraph') }}</span>
                                <x-ui::icon name="arrow-down-01" class="size-3.5" />
                            </button>
                            <div x-show="menu" x-ui-float="{ gap: 4 }" x-cloak role="menu" class="absolute top-full left-0 z-50 mt-1 flex w-44 flex-col rounded-xl border border-line bg-card p-1.5 shadow-[0_16px_40px_rgb(24_24_27/0.12)]">
                                @foreach ($blocks as $tag => $blockLabel)
                                    <button
                                        type="button"
                                        role="menuitemradio"
                                        x-bind:aria-checked="block === @js($tag)"
                                        x-on:mousedown.prevent
                                        x-on:click="setBlock(@js($tag)); menu = false"
                                        @class([
                                            'flex min-h-9 cursor-pointer items-center rounded-lg px-2.5 text-left text-ink hover:bg-wrap',
                                            'text-sm' => $tag === 'p',
                                            'text-base font-semibold' => $tag === 'h2',
                                            'text-sm font-semibold' => $tag === 'h3',
                                        ])
                                    >{{ $blockLabel }}</button>
                                @endforeach
                            </div>
                        </div>
                    @elseif (isset($buttons[$item]))
                        <button
                            type="button"
                            x-on:mousedown.prevent
                            x-on:click="{{ $buttons[$item]['click'] }}"
                            aria-label="{{ $buttons[$item]['label'] }}"
                            title="{{ $buttons[$item]['label'] }}"
                            @if ($buttons[$item]['state']) x-bind:aria-pressed="Boolean({{ $buttons[$item]['state'] }})" @endif
                            class="flex size-[34px] cursor-pointer items-center justify-center rounded-lg text-ink-2 transition hover:bg-wrap hover:text-ink aria-pressed:bg-ink aria-pressed:text-on-ink"
                        >
                            <x-ui::icon :name="$buttons[$item]['icon']" class="size-4" />
                        </button>
                    @endif
                @endforeach
            @endforeach
            @isset($actions)
                <span class="flex-1"></span>
                {{ $actions }}
            @endisset
        </div>

        <div class="relative">
            <div
                id="{{ $editorId }}"
                x-ref="content"
                contenteditable="true"
                role="textbox"
                aria-multiline="true"
                @if ($label) aria-label="{{ $label }}" @endif
                @if ($errorMessage) aria-invalid="true" aria-describedby="{{ $editorId }}-hint" @endif
                data-placeholder="{{ $placeholder }}"
                x-bind:data-empty="value ? null : ''"
                x-on:input="input()"
                x-on:keydown="keydown($event)"
                x-on:paste="paste($event)"
                x-on:blur="slashOpen = false; mentionOpen = false"
                @class([
                    'min-h-40 px-5 py-4 text-[15px] leading-relaxed text-ink-2 outline-none',
                    'data-empty:before:pointer-events-none data-empty:before:absolute data-empty:before:top-4 data-empty:before:left-5 data-empty:before:text-muted data-empty:before:content-[attr(data-placeholder)]',
                    '[&_h2]:mt-0 [&_h2]:mb-2.5 [&_h2]:text-xl [&_h2]:font-semibold [&_h2]:tracking-[-0.01em] [&_h2]:text-ink',
                    '[&_h3]:mb-2 [&_h3]:text-base [&_h3]:font-semibold [&_h3]:text-ink',
                    '[&_p]:mb-3 [&_p:last-child]:mb-0',
                    '[&_ul]:mb-3 [&_ul]:list-disc [&_ul]:pl-[22px] [&_ol]:mb-3 [&_ol]:list-decimal [&_ol]:pl-[22px]',
                    '[&_blockquote]:mb-3 [&_blockquote]:border-l-2 [&_blockquote]:border-line-strong [&_blockquote]:pl-3.5 [&_blockquote]:text-ink-3',
                    '[&_pre]:mb-3 [&_pre]:overflow-x-auto [&_pre]:rounded-lg [&_pre]:bg-wrap [&_pre]:p-3 [&_pre]:font-mono [&_pre]:text-[13px]',
                    '[&_code]:rounded-[5px] [&_code]:bg-wrap [&_code]:px-1.5 [&_code]:py-px [&_code]:font-mono [&_code]:text-[13px]',
                    '[&_a]:text-info-fg [&_a]:underline-offset-2 hover:[&_a]:underline [&_strong]:font-semibold [&_strong]:text-ink',
                    '[&_[data-mention]]:rounded-[5px] [&_[data-mention]]:bg-info-bg [&_[data-mention]]:px-1 [&_[data-mention]]:font-medium [&_[data-mention]]:text-info-fg',
                ])
            ></div>

            <div
                x-show="slashOpen && slashItems().length"
                x-cloak
                role="menu"
                aria-label="{{ __('Commands') }}"
                class="absolute z-20 flex w-[270px] flex-col rounded-xl border border-line bg-card p-1.5 shadow-[0_16px_40px_rgb(0_0_0/0.18)]"
                x-bind:style="{ top: slashTop + 'px', left: slashLeft + 'px' }"
            >
                <span class="px-2 pt-1.5 pb-1 font-mono text-[10px] tracking-[0.08em] text-muted uppercase">{{ __('Insert') }}</span>
                @foreach ($slashItems as $slashItem)
                    <button
                        type="button"
                        role="menuitem"
                        data-slash-item
                        data-label="{{ $slashItem['label'] }}"
                        x-show="slashItems().includes($el)"
                        x-on:mousedown.prevent
                        x-on:click="{{ $slashItem['click'] }}"
                        x-bind:class="slashItems()[slashIndex] === $el && 'bg-wrap'"
                        class="flex min-h-[38px] cursor-pointer items-center gap-2.5 rounded-lg px-2 text-left text-sm text-ink"
                    >
                        <x-ui::icon :name="$slashItem['icon']" class="size-4 text-ink-3" />
                        <span class="flex-1">{{ $slashItem['label'] }}</span>
                        <span class="font-mono text-[11px] text-muted">{{ $slashItem['hint'] }}</span>
                    </button>
                @endforeach
            </div>

            @if ($mentions !== [] || $mentionSearch)
                <div
                    x-show="mentionOpen && mentionItems().length"
                    x-cloak
                    role="listbox"
                    data-mention-list
                    aria-label="{{ __('People') }}"
                    class="absolute z-20 flex w-[330px] max-w-[calc(100%-1rem)] flex-col rounded-xl border border-line bg-card p-1.5 shadow-[0_16px_40px_-12px_rgb(24_24_27/0.28)]"
                    x-bind:style="{ top: mentionTop + 'px', left: mentionLeft + 'px' }"
                >
                    <span class="px-2 pt-1 pb-1.5 font-mono text-[10px] tracking-[0.08em] text-muted uppercase">{{ __('People') }}</span>
                    @foreach ($mentions as $mention)
                        <button
                            type="button"
                            role="option"
                            data-mention-item
                            data-value="{{ $mention['value'] }}"
                            data-label="{{ $mention['label'] }}"
                            data-search="{{ \Illuminate\Support\Str::squish(\Illuminate\Support\Str::lower(\Illuminate\Support\Str::ascii($mention['label'].' '.($mention['description'] ?? '').' '.$mention['value']))) }}"
                            x-show="mentionItems().includes($el)"
                            x-on:mousedown.prevent
                            x-on:click="chooseMention($el)"
                            x-bind:aria-selected="(mentionItems()[mentionIndex] === $el).toString()"
                            x-bind:class="mentionItems()[mentionIndex] === $el && 'bg-wrap'"
                            class="flex h-10 cursor-pointer items-center gap-2.5 rounded-lg px-2 text-left text-sm"
                        >
                            <x-ui::avatar :name="$mention['label']" :src="$mention['avatar'] ?? null" :icon="$mention['icon'] ?? null" :color="$mention['color'] ?? 'auto'" size="xs" class="size-[26px]" />
                            <span class="min-w-0 flex-1">
                                <span class="block truncate font-medium text-ink">{{ $mention['label'] }}</span>
                                @if (! empty($mention['description']))
                                    <span class="block truncate text-xs text-muted">{{ $mention['description'] }}</span>
                                @endif
                            </span>
                            <kbd class="font-mono text-[11px] text-muted" x-show="mentionItems()[mentionIndex] === $el.closest('[data-mention-item]')">↵</kbd>
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @if ($errorMessage)
        <x-ui::error :id="$editorId.'-hint'" :message="$errorMessage" />
    @endif
</div>
