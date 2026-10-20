/**
 * DevAction UI · componentes Alpine.
 *
 * O Alpine já vem com o Livewire 4. Importe este arquivo no app.js do projeto:
 *   import '../../vendor/devaction/ui/resources/js/devaction-ui.js';
 */
/**
 * Controle de modais fora do Alpine: DevActionUI.modal('nome').show(), DevActionUI.modals().close().
 * Do Livewire: $this->dispatch('ui-modal', name: 'nome', action: 'show').
 */
const modalControl = (name) => ({
    show: () => window.dispatchEvent(new CustomEvent('ui-modal', { detail: { name, action: 'show' } })),
    close: () => window.dispatchEvent(new CustomEvent('ui-modal', { detail: { name, action: 'close' } })),
});

window.DevActionUI = {
    ...(window.DevActionUI ?? {}),
    modal: modalControl,
    modals: () => ({ close: () => window.dispatchEvent(new CustomEvent('ui-modal', { detail: { action: 'close-all' } })) }),
};

/**
 * Trava a rolagem da página enquanto houver pelo menos um modal aberto.
 */
/**
 * Toasts: DevActionUI.toast({ text, heading, variant, duration, id, action }).
 * Do Livewire: $this->toast(...) (trait InteractsWithToasts) dispara o mesmo evento ui-toast.
 * Mesmo id atualiza o toast existente (carregando → sucesso).
 */
window.DevActionUI.toast = (toast) => {
    const detail = typeof toast === 'string' ? { text: toast } : toast;

    window.dispatchEvent(new CustomEvent('ui-toast', { detail }));

    return detail.id;
};

/**
 * Toast de progresso: chame de novo com o mesmo id para avançar; em 100 mostra o check e some.
 */
window.DevActionUI.progressToast = (id, { value = 0, ...toast } = {}) =>
    window.DevActionUI.toast({ ...toast, id, progress: value, duration: 0 });

window.DevActionUI.dismissToast = (id) => window.dispatchEvent(new CustomEvent('ui-toast-dismiss', { detail: { id } }));

let openModals = 0;

const lockScroll = () => {
    openModals += 1;
    document.documentElement.style.overflow = 'hidden';
};

const unlockScroll = () => {
    openModals = Math.max(0, openModals - 1);

    if (openModals === 0) {
        document.documentElement.style.overflow = '';
    }
};

document.addEventListener('alpine:init', () => {
    const Alpine = window.Alpine;

    Alpine.magic('toast', () => window.DevActionUI.toast);

    /**
     * Pilha de toasts: fechada mostra o da frente e até 2 espiam atrás; mouse ou foco abre em lista.
     * Timers pausam com a pilha aberta ou a aba escondida. Alt+T foca a pilha; Esc fecha o focado.
     */
    Alpine.data('uiToastGroup', ({ max = 12, peek = 3, toasts = [] } = {}) => ({
        toasts: [],
        expanded: false,
        heights: {},
        sequence: 0,

        init() {
            this.onToast = (event) => this.add(event.detail ?? {});
            this.onDismiss = (event) => this.dismiss(event.detail?.id);
            this.onVisibility = () => (document.hidden ? this.pauseAll() : this.resumeAll());

            window.addEventListener('ui-toast', this.onToast);
            window.addEventListener('ui-toast-dismiss', this.onDismiss);
            document.addEventListener('visibilitychange', this.onVisibility);

            toasts.forEach((toast) => this.add(toast));
        },

        destroy() {
            window.removeEventListener('ui-toast', this.onToast);
            window.removeEventListener('ui-toast-dismiss', this.onDismiss);
            document.removeEventListener('visibilitychange', this.onVisibility);
            this.toasts.forEach((toast) => clearTimeout(toast.timer));
        },

        add(detail) {
            const id = detail.id ?? `ui-toast-${++this.sequence}`;
            const variant = detail.variant ?? 'default';
            const persistent = variant === 'loading' || detail.duration === 0;
            const toast = {
                id,
                text: detail.text ?? '',
                heading: detail.heading ?? null,
                variant,
                action: detail.action ?? null,
                progress: detail.progress ?? null,
                duration: persistent ? 0 : (detail.duration ?? 5000),
                remaining: persistent ? 0 : (detail.duration ?? 5000),
                startedAt: null,
                timer: null,
                offsetX: 0,
            };

            const existing = this.toasts.findIndex((item) => item.id === id);

            if (existing !== -1) {
                clearTimeout(this.toasts[existing].timer);
                this.toasts[existing] = toast;
            } else {
                this.toasts = [toast, ...this.toasts].slice(0, max);
            }

            if (!this.expanded && !document.hidden) {
                this.start(toast);
            }

            if (toast.progress !== null && toast.progress >= 100 && detail.dismissOnComplete !== false) {
                toast.remaining = 1200;
                toast.duration = 1200;
                this.start(toast);
            }
        },

        start(toast) {
            if (!toast.duration) {
                return;
            }

            clearTimeout(toast.timer);
            toast.startedAt = Date.now();
            toast.timer = setTimeout(() => this.dismiss(toast.id), toast.remaining);
        },

        pause(toast) {
            if (!toast.duration || !toast.startedAt) {
                return;
            }

            clearTimeout(toast.timer);
            toast.remaining = Math.max(0, toast.remaining - (Date.now() - toast.startedAt));
            toast.startedAt = null;
        },

        pauseAll() {
            this.toasts.forEach((toast) => this.pause(toast));
        },

        resumeAll() {
            if (!this.expanded && !document.hidden) {
                this.toasts.forEach((toast) => this.start(toast));
            }
        },

        expand() {
            this.expanded = true;
            this.pauseAll();
        },

        collapse() {
            if (this.$root.contains(document.activeElement)) {
                return;
            }

            this.expanded = false;
            this.resumeAll();
        },

        dismiss(id) {
            const toast = this.toasts.find((item) => item.id === id);

            if (toast) {
                clearTimeout(toast.timer);
                this.toasts = this.toasts.filter((item) => item.id !== id);
            }

            if (this.toasts.length === 0) {
                this.expanded = false;
            }
        },

        runAction(toast) {
            if (toast.action?.event) {
                window.dispatchEvent(new CustomEvent(toast.action.event, { detail: toast.action.params ?? {}, bubbles: true }));
            }

            this.dismiss(toast.id);
        },

        focusStack() {
            this.$root.querySelector('[data-toast]')?.focus();
        },

        /**
         * Distância do toast até a base da pilha, conforme ela está fechada ou aberta.
         */
        offset(index) {
            if (!this.expanded) {
                return index * 12;
            }

            return this.toasts.slice(0, index).reduce((total, toast) => total + (this.heights[toast.id] ?? 52) + 12, 0);
        },

        style(toast, index, fromTop) {
            const direction = fromTop ? 1 : -1;
            const scale = this.expanded ? 1 : 1 - index * 0.05;
            const visible = this.expanded || index < peek;

            return {
                transform: `translate(${toast.offsetX}px, ${direction * this.offset(index)}px) scale(${scale})`,
                opacity: visible ? Math.max(0, 1 - Math.abs(toast.offsetX) / 160) : 0,
                zIndex: 100 - index,
                pointerEvents: visible ? 'auto' : 'none',
            };
        },

        stackHeight() {
            if (this.toasts.length === 0) {
                return 0;
            }

            if (this.expanded) {
                return this.offset(this.toasts.length - 1) + (this.heights[this.toasts.at(-1).id] ?? 52);
            }

            return (this.heights[this.toasts[0].id] ?? 52) + Math.min(this.toasts.length - 1, peek - 1) * 12;
        },

        swipeStart(toast, event) {
            toast.swipeFrom = event.clientX;
        },

        swipeMove(toast, event) {
            if (toast.swipeFrom !== undefined && toast.swipeFrom !== null) {
                toast.offsetX = event.clientX - toast.swipeFrom;
            }
        },

        swipeEnd(toast) {
            if (Math.abs(toast.offsetX) > 80) {
                this.dismiss(toast.id);
            } else {
                toast.offsetX = 0;
            }

            toast.swipeFrom = null;
        },
    }));

    Alpine.magic('modal', () => modalControl);
    Alpine.magic('modals', () => window.DevActionUI.modals);

    /**
     * Modal sobre <dialog>: showModal() deixa o resto da página inerte e trata o Esc.
     * open é x-modelable, então funciona com wire:model.
     */
    Alpine.data('uiModal', ({ name = null, open = false, dismissible = true, escapable = true } = {}) => ({
        name,
        open,
        dismissible,
        escapable,

        init() {
            this.$watch('open', (isOpen) => (isOpen ? this.showDialog() : this.$refs.dialog.close()));

            if (this.open) {
                this.$nextTick(() => this.showDialog());
            }

            this.onWindowEvent = (event) => {
                const { name: target, action } = event.detail ?? {};

                if (action === 'close-all') {
                    this.open = false;
                } else if (target !== null && target === this.name) {
                    this.open = action === 'show';
                }
            };

            window.addEventListener('ui-modal', this.onWindowEvent);
        },

        destroy() {
            window.removeEventListener('ui-modal', this.onWindowEvent);

            if (this.$refs.dialog?.open) {
                unlockScroll();
            }
        },

        showDialog() {
            const dialog = this.$refs.dialog;

            if (!dialog.open) {
                dialog.showModal();
                lockScroll();
            }
        },

        /**
         * O <dialog> fechou (botão, Esc, clique fora ou open = false).
         */
        closed() {
            unlockScroll();
            this.open = false;
            this.$root.dispatchEvent(new CustomEvent('close'));
        },

        cancel(event) {
            if (!this.escapable) {
                event.preventDefault();

                return;
            }

            this.$root.dispatchEvent(new CustomEvent('cancel'));
        },

        clickOutside(event) {
            if (event.target === this.$refs.dialog && this.dismissible) {
                this.$root.dispatchEvent(new CustomEvent('cancel'));
                this.open = false;
            }
        },
    }));

    Alpine.data('uiAccordion', ({ exclusive = false } = {}) => ({
        exclusive,
        activeItem: null,

        /**
         * Setas, Home e End movem o foco entre os cabeçalhos deste acordeão (sem entrar nos aninhados).
         */
        moveFocus(event) {
            const trigger = event.target.closest('[data-accordion-trigger]');

            if (!trigger || trigger.closest('[data-accordion]') !== this.$el) {
                return;
            }

            const triggers = [...this.$el.querySelectorAll('[data-accordion-trigger]')].filter(
                (candidate) => candidate.closest('[data-accordion]') === this.$el && !candidate.disabled,
            );
            const index = triggers.indexOf(trigger);
            const targets = {
                ArrowDown: triggers[(index + 1) % triggers.length],
                ArrowUp: triggers[(index - 1 + triggers.length) % triggers.length],
                Home: triggers[0],
                End: triggers[triggers.length - 1],
            };

            if (targets[event.key]) {
                event.preventDefault();
                targets[event.key].focus();
            }
        },
    }));

    Alpine.data('uiAccordionItem', ({ expanded = false, disabled = false } = {}) => ({
        open: expanded,
        disabled,
        itemId: null,

        init() {
            this.itemId = this.$id('accordion-item');

            if (this.open && this.exclusive) {
                this.activeItem = this.itemId;
            }

            this.$watch('activeItem', (activeItem) => {
                if (this.exclusive && activeItem !== this.itemId) {
                    this.open = false;
                }
            });
        },

        toggle() {
            if (this.disabled) {
                return;
            }

            this.open = !this.open;

            if (this.open) {
                this.activeItem = this.itemId;
            }
        },
    }));

    /**
     * Dropdown: contêiner de popover e menu. O primeiro elemento focável fora do painel é o gatilho.
     * open é x-modelable, então funciona com wire:model.
     */
    Alpine.data('uiDropdown', ({ hover = false } = {}) => ({
        open: false,
        hoverTimer: null,

        init() {
            const trigger = this.trigger();

            if (trigger) {
                trigger.setAttribute('aria-haspopup', 'true');
                trigger.setAttribute('aria-expanded', 'false');
                this.$watch('open', (isOpen) => trigger.setAttribute('aria-expanded', String(isOpen)));
            }

            this.$watch('open', (isOpen) => this.$root.toggleAttribute('data-open', isOpen));
        },

        trigger() {
            return [...this.$root.querySelectorAll('button, a, [tabindex]')].find(
                (element) => !element.closest('[data-dropdown-panel]') && element.closest('[data-dropdown]') === this.$root,
            );
        },

        clicked(event) {
            if (event.target.closest('[data-dropdown-panel]')?.closest('[data-dropdown]') === this.$root) {
                return;
            }

            if (this.trigger()?.contains(event.target)) {
                this.open = !this.open;
            }
        },

        close(restoreFocus = false) {
            if (!this.open) {
                return;
            }

            this.open = false;

            if (restoreFocus) {
                this.trigger()?.focus();
            }
        },

        enter() {
            if (hover) {
                clearTimeout(this.hoverTimer);
                this.open = true;
            }
        },

        leave() {
            if (hover) {
                this.hoverTimer = setTimeout(() => (this.open = false), 150);
            }
        },
    }));

    /**
     * Menu de ações: setas, Home, End e busca pela letra inicial. Foca o primeiro item ao abrir.
     * Em submenu, → abre e ← volta para o item que o abriu.
     */
    Alpine.data('uiMenu', ({ submenu = false } = {}) => ({
        init() {
            this.$watch(submenu ? 'subOpen' : 'open', (isOpen) => {
                if (isOpen) {
                    this.$nextTick(() => this.items()[0]?.focus());
                }
            });
        },

        items() {
            return [...this.$el.querySelectorAll('[data-menu-item]')].filter(
                (item) => item.closest('[data-menu]') === this.$el && !item.disabled && item.getClientRects().length > 0,
            );
        },

        onKeydown(event) {
            const items = this.items();
            const index = items.indexOf(document.activeElement);
            let target = null;

            if (event.key === 'ArrowDown') {
                target = items[(index + 1) % items.length];
            } else if (event.key === 'ArrowUp') {
                target = items[(index - 1 + items.length) % items.length];
            } else if (event.key === 'Home') {
                target = items[0];
            } else if (event.key === 'End') {
                target = items[items.length - 1];
            } else if (event.key === 'ArrowRight' && document.activeElement?.hasAttribute('data-submenu-trigger')) {
                document.activeElement.click();
            } else if (event.key === 'ArrowLeft' && submenu) {
                this.subOpen = false;
                this.$el.closest('[data-menu-submenu]')?.querySelector('[data-submenu-trigger]')?.focus();
            } else if (event.key.length === 1 && /\S/.test(event.key)) {
                const letter = event.key.toLowerCase();
                const ordered = [...items.slice(index + 1), ...items.slice(0, index + 1)];
                target = ordered.find((item) => item.textContent.trim().toLowerCase().startsWith(letter));
            } else {
                return;
            }

            event.preventDefault();
            event.stopPropagation();
            target?.focus();
        },
    }));

    /**
     * Grupo de checkboxes ou radios: value é x-modelable (wire:model no grupo) e fica em sincronia
     * com os inputs nativos de dentro, que continuam funcionando sozinhos em formulários comuns.
     */
    Alpine.data('uiChoiceGroup', ({ multiple = false } = {}) => ({
        value: multiple ? [] : null,

        init() {
            const sharedName = `ui-choice-${Math.random().toString(36).slice(2, 10)}`;
            this.inputs().forEach((input) => {
                if (input.type === 'radio' && !input.name) {
                    input.name = sharedName;
                }
            });

            const checked = this.inputs().filter((input) => input.checked).map((input) => input.value);

            if (multiple ? checked.length : checked[0] !== undefined) {
                this.value = multiple ? checked : checked[0];
            }

            this.$watch('value', () => this.sync());
            this.$nextTick(() => this.sync());
        },

        inputs() {
            return [...this.$root.querySelectorAll('input[type=checkbox], input[type=radio]')].filter(
                (input) => input.closest('[data-choice-group]') === this.$root,
            );
        },

        sync() {
            this.inputs().forEach((input) => {
                input.checked = multiple
                    ? Array.isArray(this.value) && this.value.map(String).includes(input.value)
                    : this.value !== null && String(this.value) === input.value;
            });
        },

        changed() {
            const checked = this.inputs().filter((input) => input.checked).map((input) => input.value);
            this.value = multiple ? checked : (checked[0] ?? null);
        },
    }));

    /**
     * Combobox: seleção única com busca. value é x-modelable; o input escondido leva o name.
     * ↑↓ navega, ↵ escolhe, Esc fecha e devolve o foco ao gatilho.
     */
    Alpine.data('uiCombobox', ({ value = null } = {}) => ({
        open: false,
        query: '',
        value,
        activeValue: null,
        selectedLabel: '',
        selectedHtml: '',

        init() {
            this.$watch('value', () => this.refreshSelected());
            this.$watch('query', () => this.$nextTick(() => (this.activeValue = this.options()[0]?.dataset.value ?? null)));
            this.$nextTick(() => this.refreshSelected());
        },

        normalize(text) {
            return (text ?? '').toString().normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase().trim();
        },

        isVisible(option) {
            const query = this.normalize(this.query);

            return query === '' || this.normalize(option.dataset.search).includes(query);
        },

        options() {
            return [...this.$root.querySelectorAll('[data-combobox-option]')].filter((option) => !option.disabled && this.isVisible(option));
        },

        hasResults() {
            return this.options().length > 0;
        },

        refreshSelected() {
            const option = [...this.$root.querySelectorAll('[data-combobox-option]')].find((item) => item.dataset.value === String(this.value ?? ''));
            this.selectedLabel = option?.dataset.label ?? '';
            this.selectedHtml = option?.querySelector('[data-combobox-label]')?.innerHTML ?? '';
        },

        toggle() {
            this.open ? this.close() : this.show();
        },

        show() {
            this.open = true;
            this.query = '';
            this.activeValue = String(this.value ?? '') || (this.options()[0]?.dataset.value ?? null);
            this.$nextTick(() => this.$refs.search?.focus());
        },

        close(restoreFocus = false) {
            this.open = false;

            if (restoreFocus) {
                this.$refs.trigger.focus();
            }
        },

        choose(option) {
            this.value = option.dataset.value;
            this.$refs.raw.value = option.dataset.value;
            this.$refs.raw.dispatchEvent(new Event('change', { bubbles: true }));
            this.close(true);
        },

        move(step) {
            const options = this.options();
            const index = options.findIndex((option) => option.dataset.value === this.activeValue);
            const next = options[(index + step + options.length) % options.length];

            if (next) {
                this.activeValue = next.dataset.value;
                next.scrollIntoView({ block: 'nearest' });
            }
        },

        chooseActive() {
            const option = this.options().find((item) => item.dataset.value === this.activeValue);

            if (option) {
                this.choose(option);
            }
        },
    }));

    /**
     * Autocomplete: texto livre com sugestões. Filtra os itens pelo texto, destaca o trecho
     * encontrado e, ao escolher, preenche o campo com o valor do item.
     */
    Alpine.data('uiAutocomplete', ({ minChars = 1 } = {}) => ({
        open: false,
        query: '',
        activeIndex: 0,

        init() {
            this.query = this.$refs.input.value;
            this.$watch('query', () => (this.activeIndex = 0));
        },

        normalize(text) {
            return (text ?? '').toString().normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase();
        },

        matches(item) {
            const query = this.normalize(this.query.trim());

            return item.hasAttribute('data-autocomplete-always') || query === '' || this.normalize(item.dataset.search).includes(query);
        },

        items() {
            return [...this.$root.querySelectorAll('[data-autocomplete-item]')].filter((item) => this.matches(item));
        },

        groupHasItems(group) {
            return [...group.querySelectorAll('[data-autocomplete-item]')].some((item) => this.matches(item));
        },

        isActive(item) {
            return this.items()[this.activeIndex] === item;
        },

        hasResults() {
            return this.items().some((item) => !item.hasAttribute('data-autocomplete-always'));
        },

        highlight(text) {
            const escaped = (text ?? '').replace(/[&<>"']/g, (character) => `&#${character.charCodeAt(0)};`);
            const query = this.query.trim();

            if (query === '') {
                return escaped;
            }

            const start = this.normalize(text).indexOf(this.normalize(query));

            if (start < 0) {
                return escaped;
            }

            const before = text.slice(0, start);
            const match = text.slice(start, start + query.length);
            const after = text.slice(start + query.length);
            const escape = (part) => part.replace(/[&<>"']/g, (character) => `&#${character.charCodeAt(0)};`);

            return `${escape(before)}<strong class="font-semibold">${escape(match)}</strong>${escape(after)}`;
        },

        typed() {
            this.open = this.query.trim().length >= minChars;
        },

        move(step) {
            const items = this.items();

            if (!this.open) {
                this.open = items.length > 0;

                return;
            }

            this.activeIndex = (this.activeIndex + step + items.length) % (items.length || 1);
            items[this.activeIndex]?.scrollIntoView({ block: 'nearest' });
        },

        choose(item) {
            item.dispatchEvent(new CustomEvent('select', { bubbles: true, detail: { value: item.dataset.value, query: this.query } }));

            if (!item.hasAttribute('data-autocomplete-keep')) {
                this.query = item.dataset.value;
                this.$nextTick(() => {
                    this.$refs.input.dispatchEvent(new Event('input', { bubbles: true }));
                    this.$refs.input.dispatchEvent(new Event('change', { bubbles: true }));
                });
            }

            this.open = false;
            this.$refs.input.focus();
        },

        chooseActive() {
            const item = this.items()[this.activeIndex];

            if (this.open && item) {
                this.choose(item);
            }
        },
    }));

    /**
     * Dropzone: arrastar e soltar sobre um input file nativo (o wire:model do Livewire continua
     * funcionando). Mostra o resumo do que está sendo arrastado e o progresso do upload do Livewire.
     */
    Alpine.data('uiDropzone', () => ({
        dragging: false,
        depth: 0,
        summary: '',
        uploading: false,
        progress: 0,

        humanSize(bytes) {
            const units = ['B', 'KB', 'MB', 'GB'];
            let size = bytes;
            let unit = 0;

            while (size >= 1024 && unit < units.length - 1) {
                size /= 1024;
                unit++;
            }

            return `${new Intl.NumberFormat('pt-BR', { maximumFractionDigits: unit ? 1 : 0 }).format(size)} ${units[unit]}`;
        },

        enter(event) {
            this.depth++;
            this.dragging = true;
            const items = [...(event.dataTransfer?.items ?? [])].filter((item) => item.kind === 'file');
            this.summary = items.length === 1 ? '1 arquivo' : `${items.length} arquivos`;
        },

        leave() {
            this.depth = Math.max(0, this.depth - 1);
            this.dragging = this.depth > 0;
        },

        drop(event) {
            this.depth = 0;
            this.dragging = false;
            const input = this.$refs.input;

            if (input.disabled || !event.dataTransfer?.files?.length) {
                return;
            }

            const transfer = new DataTransfer();
            const files = input.multiple ? [...event.dataTransfer.files] : [event.dataTransfer.files[0]];
            files.forEach((file) => transfer.items.add(file));
            input.files = transfer.files;
            input.dispatchEvent(new Event('change', { bubbles: true }));
        },

        chosen() {
            const files = [...this.$refs.input.files];
            const total = files.reduce((sum, file) => sum + file.size, 0);
            this.summary = files.length ? `${files.length === 1 ? files[0].name : files.length + ' arquivos'} · ${this.humanSize(total)}` : '';
        },
    }));

    /**
     * Timer: conta segundos a partir de seconds; com started-at (timestamp) retoma depois de recarregar.
     * value (x-modelable) são os segundos. Dispara timer-started, timer-paused e timer-stopped.
     */
    Alpine.data('uiTimer', ({ seconds = 0, running = false, startedAt = null } = {}) => ({
        value: Number(seconds) || 0,
        running: false,
        base: Number(seconds) || 0,
        since: null,
        ticker: null,

        init() {
            if (running) {
                this.since = startedAt ? Number(startedAt) * 1000 : Date.now();
                this.run();
            }
        },

        destroy() {
            clearInterval(this.ticker);
        },

        run() {
            this.running = true;
            this.tick();
            this.ticker = setInterval(() => this.tick(), 1000);
        },

        tick() {
            this.value = this.base + Math.floor((Date.now() - this.since) / 1000);
        },

        toggle() {
            this.running ? this.pause() : this.start();
        },

        start() {
            this.base = this.value;
            this.since = Date.now();
            this.run();
            this.$dispatch('timer-started', { seconds: this.value });
        },

        pause() {
            clearInterval(this.ticker);
            this.running = false;
            this.$dispatch('timer-paused', { seconds: this.value });
        },

        stop() {
            clearInterval(this.ticker);
            this.running = false;
            this.$dispatch('timer-stopped', { seconds: this.value });
            this.base = 0;
            this.value = 0;
        },

        format(withSeconds = true) {
            const hours = Math.floor(this.value / 3600);
            const minutes = Math.floor((this.value % 3600) / 60);
            const secs = this.value % 60;
            const parts = [hours, minutes].concat(withSeconds ? [secs] : []);

            return parts.map((part) => String(part).padStart(2, '0')).join(':');
        },
    }));

    /**
     * Campo de assinatura: desenhar (traços viram paths SVG) ou digitar o nome.
     * value (x-modelable) é o SVG da assinatura, ou '' quando vazio.
     */
    Alpine.data('uiSignature', ({ mode = 'draw', name = '' } = {}) => ({
        mode,
        name,
        strokes: [],
        current: null,
        value: '',

        point(event) {
            const box = this.$refs.pad.getBoundingClientRect();

            return [Math.round(((event.clientX - box.left) / box.width) * 600), Math.round(((event.clientY - box.top) / box.height) * 200)];
        },

        down(event) {
            this.$refs.pad.setPointerCapture(event.pointerId);
            this.current = [this.point(event)];
        },

        move(event) {
            if (this.current) {
                this.current.push(this.point(event));
                this.$refs.live.setAttribute('d', this.path(this.current));
            }
        },

        up() {
            if (this.current && this.current.length > 1) {
                this.strokes = [...this.strokes, this.current];
                this.sync();
            }

            this.current = null;
            this.$refs.live.setAttribute('d', '');
        },

        path(points) {
            return points.map(([x, y], index) => `${index ? 'L' : 'M'}${x} ${y}`).join(' ');
        },

        paths() {
            return this.strokes.map((points) => this.path(points));
        },

        isEmpty() {
            return this.mode === 'draw' ? this.strokes.length === 0 : this.name.trim() === '';
        },

        sync() {
            if (this.isEmpty()) {
                this.value = '';

                return;
            }

            if (this.mode === 'draw') {
                const body = this.paths().map((d) => `<path d="${d}" fill="none" stroke="#18181B" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>`).join('');
                this.value = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 200">${body}</svg>`;

                return;
            }

            const escaped = this.name.replace(/[&<>"']/g, (character) => `&#${character.charCodeAt(0)};`);
            this.value = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 200"><text x="24" y="150" font-family="Caveat, 'Segoe Script', cursive" font-size="72" fill="#18181B">${escaped}</text></svg>`;
        },

        setMode(next) {
            this.mode = next;
            this.sync();
        },

        clear() {
            this.strokes = [];
            this.name = '';
            this.sync();
        },
    }));

    /**
     * Menu de filtros: grupos com submenu de opções. Marcar não fecha o menu; as escolhas ficam
     * num rascunho até Aplicar. value (x-modelable) é { grupo: [valores] }.
     */
    Alpine.data('uiFilterMenu', ({ value = {} } = {}) => ({
        open: false,
        activeGroup: null,
        value: value && typeof value === 'object' && !Array.isArray(value) ? value : {},
        draft: {},

        toggle() {
            this.open ? this.close() : this.show();
        },

        show() {
            this.draft = JSON.parse(JSON.stringify(this.value ?? {}));
            this.open = true;
            this.$nextTick(() => this.$root.querySelector('[data-filter-group-trigger]')?.focus());
        },

        close(restoreFocus = false) {
            this.open = false;
            this.activeGroup = null;

            if (restoreFocus) {
                this.$refs.trigger.focus();
            }
        },

        escape() {
            if (this.activeGroup !== null) {
                const group = this.activeGroup;
                this.activeGroup = null;
                this.$root.querySelector(`[data-filter-group-trigger="${group}"]`)?.focus();

                return;
            }

            this.close(true);
        },

        openGroup(name, focus = false) {
            this.activeGroup = name;

            if (focus) {
                this.$nextTick(() => this.$root.querySelector(`[data-filter-group="${name}"] [data-filter-option]`)?.focus());
            }
        },

        selected(group) {
            return Array.isArray(this.draft[group]) ? this.draft[group] : [];
        },

        isChecked(group, option) {
            return this.selected(group).includes(option);
        },

        toggleOption(group, option, single = false) {
            const current = this.selected(group);

            if (single) {
                this.draft = { ...this.draft, [group]: current.includes(option) ? [] : [option] };

                return;
            }

            this.draft = { ...this.draft, [group]: current.includes(option) ? current.filter((item) => item !== option) : [...current, option] };
        },

        groupCount(group) {
            return this.selected(group).length;
        },

        appliedCount() {
            return Object.values(this.value ?? {}).reduce((total, items) => total + (Array.isArray(items) ? items.length : 0), 0);
        },

        clear() {
            this.draft = {};
        },

        apply() {
            this.value = Object.fromEntries(Object.entries(this.draft).filter(([, items]) => Array.isArray(items) && items.length));
            this.$dispatch('filters-applied', this.value);
            this.close(true);
        },

        focusSibling(step) {
            const current = document.activeElement;
            const isOption = current.hasAttribute('data-filter-option');
            const scope = isOption ? current.closest('[data-filter-group]') : this.$root;
            const items = [...scope.querySelectorAll(isOption ? '[data-filter-option]' : '[data-filter-group-trigger]')];
            const index = items.indexOf(current);
            items[(index + step + items.length) % items.length]?.focus();
        },
    }));

    /**
     * Seletor de cor: área de saturação/brilho, matiz, opacidade opcional, conta-gotas e cores prontas.
     * value (x-modelable) sai sempre em hex: #RRGGBB, ou #RRGGBBAA com alpha.
     */
    Alpine.data('uiColorPicker', ({ value = '#18181B', alpha = false } = {}) => {
        const clamp = (number, low, high) => Math.min(high, Math.max(low, number));
        const toHex = (number) => Math.round(number).toString(16).padStart(2, '0').toUpperCase();
        const hsvToRgb = (h, s, v) => {
            const f = (n) => {
                const k = (n + h / 60) % 6;

                return v - v * s * Math.max(0, Math.min(k, 4 - k, 1));
            };

            return [f(5) * 255, f(3) * 255, f(1) * 255];
        };
        const rgbToHsv = (r, g, b) => {
            [r, g, b] = [r / 255, g / 255, b / 255];
            const max = Math.max(r, g, b);
            const delta = max - Math.min(r, g, b);
            let h = 0;

            if (delta) {
                h = max === r ? ((g - b) / delta) % 6 : max === g ? (b - r) / delta + 2 : (r - g) / delta + 4;
            }

            return [(h * 60 + 360) % 360, max ? delta / max : 0, max];
        };

        return {
            open: false,
            value,
            text: value,
            hue: 0,
            saturation: 0,
            brightness: 0,
            opacity: 100,
            dragging: false,

            init() {
                this.parse(this.value);
                this.$watch('value', (next) => {
                    if (next && next.toUpperCase() !== this.hex().toUpperCase()) {
                        this.parse(next);
                    }
                });
            },

            parse(hex) {
                const clean = String(hex ?? '').replace('#', '').trim();

                if (!/^([0-9a-f]{3}|[0-9a-f]{6}|[0-9a-f]{8})$/i.test(clean)) {
                    return false;
                }

                const full = clean.length === 3 ? clean.split('').map((character) => character + character).join('') : clean;
                const [r, g, b] = [0, 2, 4].map((index) => parseInt(full.slice(index, index + 2), 16));
                [this.hue, this.saturation, this.brightness] = rgbToHsv(r, g, b);
                this.opacity = full.length === 8 ? Math.round((parseInt(full.slice(6, 8), 16) / 255) * 100) : 100;
                this.text = this.hex();

                return true;
            },

            solid() {
                return `#${hsvToRgb(this.hue, this.saturation, this.brightness).map(toHex).join('')}`;
            },

            hueColor() {
                return `#${hsvToRgb(this.hue, 1, 1).map(toHex).join('')}`;
            },

            hex() {
                return alpha && this.opacity < 100 ? `${this.solid()}${toHex((this.opacity / 100) * 255)}` : this.solid();
            },

            sync() {
                this.value = this.hex();
                this.text = this.value;
            },

            typed() {
                const candidate = this.text.startsWith('#') ? this.text : `#${this.text}`;

                if (this.parse(candidate)) {
                    this.value = this.hex();
                }
            },

            blurred() {
                this.text = this.hex();
            },

            pointer(event) {
                const box = this.$refs.area.getBoundingClientRect();
                this.saturation = clamp((event.clientX - box.left) / box.width, 0, 1);
                this.brightness = 1 - clamp((event.clientY - box.top) / box.height, 0, 1);
                this.sync();
            },

            startDrag(event) {
                this.dragging = true;
                this.$refs.area.setPointerCapture(event.pointerId);
                this.pointer(event);
            },

            drag(event) {
                if (this.dragging) {
                    this.pointer(event);
                }
            },

            areaKey(dx, dy) {
                this.saturation = clamp(this.saturation + dx, 0, 1);
                this.brightness = clamp(this.brightness + dy, 0, 1);
                this.sync();
            },

            choose(hex) {
                this.parse(hex);
                this.sync();
            },

            isCurrent(hex) {
                return this.solid().toUpperCase() === hex.toUpperCase();
            },

            canPickFromScreen() {
                return 'EyeDropper' in window;
            },

            async pickFromScreen() {
                try {
                    const { sRGBHex } = await new window.EyeDropper().open();
                    this.choose(sRGBHex);
                } catch {
                    // A pessoa cancelou o conta-gotas.
                }
            },
        };
    });

    /**
     * Date picker: data única ('2026-10-16') ou faixa ({ start, end }), com min, max e atalhos.
     * value é x-modelable. Datas em texto ISO, sem fuso, para não andar um dia.
     */
    Alpine.data('uiDatePicker', ({ value = null, range = false, min = null, max = null, locale = 'pt-BR', today = null } = {}) => {
        const pad = (number) => String(number).padStart(2, '0');
        const toIso = (date) => `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`;
        const fromIso = (iso) => {
            const [year, month, day] = String(iso).split('-').map(Number);

            return new Date(year, month - 1, day);
        };
        const addDays = (iso, days) => {
            const date = fromIso(iso);
            date.setDate(date.getDate() + days);

            return toIso(date);
        };

        return {
            open: false,
            value: value ?? (range ? { start: null, end: null } : null),
            today: today ?? toIso(new Date()),
            month: null,
            focused: null,
            hovered: null,

            init() {
                const anchor = (range ? this.value?.start : this.value) || this.today;
                this.month = anchor.slice(0, 7);
                this.focused = anchor;
            },

            start() {
                return range ? this.value?.start ?? null : this.value;
            },

            end() {
                return range ? this.value?.end ?? null : this.value;
            },

            monthLabel() {
                return new Intl.DateTimeFormat(locale, { month: 'long', year: 'numeric' }).format(fromIso(`${this.month}-01`));
            },

            weekdays() {
                const sunday = new Date(2026, 9, 4);

                return [...Array(7)].map((_, index) => {
                    const date = new Date(sunday);
                    date.setDate(sunday.getDate() + index);

                    return new Intl.DateTimeFormat(locale, { weekday: 'short' }).format(date).replace('.', '').slice(0, 3);
                });
            },

            days() {
                const first = fromIso(`${this.month}-01`);
                const total = new Date(first.getFullYear(), first.getMonth() + 1, 0).getDate();
                const blanks = [...Array(first.getDay())].map((_, index) => ({ key: `blank-${index}`, iso: null }));

                return blanks.concat([...Array(total)].map((_, index) => {
                    const iso = `${this.month}-${pad(index + 1)}`;

                    return { key: iso, iso, day: index + 1 };
                }));
            },

            shiftMonth(step) {
                const date = fromIso(`${this.month}-01`);
                date.setMonth(date.getMonth() + step);
                this.month = toIso(date).slice(0, 7);
            },

            isDisabled(iso) {
                return (min !== null && iso < min) || (max !== null && iso > max);
            },

            isSelected(iso) {
                return iso !== null && (iso === this.start() || iso === this.end());
            },

            isInRange(iso) {
                if (!range || iso === null) {
                    return false;
                }

                const startIso = this.start();
                const endIso = this.end() ?? (startIso && this.hovered && this.hovered > startIso ? this.hovered : null);

                return startIso !== null && endIso !== null && iso > startIso && iso < endIso;
            },

            dayLabel(iso) {
                return new Intl.DateTimeFormat(locale, { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }).format(fromIso(iso));
            },

            pick(iso) {
                if (iso === null || this.isDisabled(iso)) {
                    return;
                }

                this.focused = iso;

                if (!range) {
                    this.value = iso;
                    this.commit();
                    this.close(true);

                    return;
                }

                const startIso = this.start();

                if (startIso === null || this.end() !== null || iso < startIso) {
                    this.value = { start: iso, end: null };
                } else {
                    this.value = { start: startIso, end: iso };
                    this.commit();
                    this.close(true);
                }
            },

            preset(days, length = null) {
                if (range) {
                    const endIso = addDays(this.today, days);
                    this.value = { start: length === null ? this.today : addDays(endIso, -length), end: endIso };
                } else {
                    this.value = addDays(this.today, days);
                }

                this.month = (range ? this.value.start : this.value).slice(0, 7);
                this.commit();
                this.close(true);
            },

            nextWeekday(weekday) {
                const date = fromIso(this.today);
                const distance = (weekday - date.getDay() + 7) % 7 || 7;

                return distance;
            },

            clear() {
                this.value = range ? { start: null, end: null } : null;
                this.commit();
            },

            commit() {
                this.$root.querySelectorAll('[data-date-raw]').forEach((input) => {
                    input.value = input.dataset.dateRaw === 'end' ? this.end() ?? '' : this.start() ?? '';
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                });
            },

            format(iso, options = { day: '2-digit', month: '2-digit', year: 'numeric' }) {
                return iso ? new Intl.DateTimeFormat(locale, options).format(fromIso(iso)) : '';
            },

            relative(iso) {
                if (!iso) {
                    return '';
                }

                const days = Math.round((fromIso(iso) - fromIso(this.today)) / 86400000);

                return new Intl.RelativeTimeFormat(locale, { numeric: 'auto' }).format(days, 'day');
            },

            display() {
                if (!range) {
                    return this.format(this.start(), { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' });
                }

                return this.start() ? `${this.format(this.start())} → ${this.end() ? this.format(this.end()) : '…'}` : '';
            },

            toggle() {
                this.open ? this.close() : this.show();
            },

            show() {
                this.open = true;
                this.focused = this.start() || this.today;
                this.month = this.focused.slice(0, 7);
                this.$nextTick(() => this.focusDay());
            },

            close(restoreFocus = false) {
                this.open = false;
                this.hovered = null;

                if (restoreFocus && this.$refs.trigger) {
                    this.$refs.trigger.focus();
                }
            },

            moveFocus(days) {
                this.focused = addDays(this.focused, days);
                this.month = this.focused.slice(0, 7);
                this.$nextTick(() => this.focusDay());
            },

            focusDay() {
                this.$root.querySelector(`[data-date="${this.focused}"]`)?.focus();
            },
        };
    });

    /**
     * Pillbox: lista de textos em pílulas. ↵ ou vírgula adiciona, ⌫ no campo vazio remove a última.
     * value (array) é x-modelable; suggestions aparecem filtradas enquanto digita.
     */
    Alpine.data('uiPillbox', ({ value = [], suggestions = [], max = null, unique = true } = {}) => ({
        value: Array.isArray(value) ? value : [],
        suggestions,
        draft: '',
        activeIndex: -1,

        normalize(text) {
            return (text ?? '').toString().normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase().trim();
        },

        filtered() {
            const draft = this.normalize(this.draft);

            if (draft === '') {
                return [];
            }

            return this.suggestions
                .filter((item) => this.normalize(item).includes(draft))
                .filter((item) => !this.value.some((pill) => this.normalize(pill) === this.normalize(item)))
                .slice(0, 8);
        },

        isFull() {
            return max !== null && this.value.length >= max;
        },

        add(text = null) {
            const pill = (text ?? (this.filtered()[this.activeIndex] || this.draft)).toString().replace(/,$/, '').trim();

            if (pill === '' || this.isFull()) {
                return;
            }

            if (unique && this.value.some((item) => this.normalize(item) === this.normalize(pill))) {
                this.draft = '';

                return;
            }

            this.value = [...this.value, pill];
            this.draft = '';
            this.activeIndex = -1;
            this.$nextTick(() => this.$refs.draft.focus());
        },

        remove(index) {
            this.value = this.value.filter((_, position) => position !== index);
            this.$nextTick(() => this.$refs.draft.focus());
        },

        backspace() {
            if (this.draft === '' && this.value.length) {
                this.remove(this.value.length - 1);
            }
        },

        typed() {
            this.activeIndex = -1;

            if (this.draft.endsWith(',')) {
                this.add();
            }
        },

        move(step) {
            const total = this.filtered().length;

            if (total) {
                this.activeIndex = (this.activeIndex + step + total) % total;
            }
        },
    }));

    /**
     * Slider: valor único ou faixa (value = [mín, máx]) com distância mínima entre os botões.
     * value é x-modelable, então funciona com wire:model.
     */
    Alpine.data('uiSlider', ({ min = 0, max = 100, range = false, minDistance = 0, value = null } = {}) => ({
        value: value ?? (range ? [min, max] : min),
        marks: [],

        percent(number) {
            return ((Number(number) - min) / (max - min || 1)) * 100;
        },

        low() {
            return range ? Number(this.value[0]) : Number(this.value);
        },

        high() {
            return range ? Number(this.value[1]) : Number(this.value);
        },

        setLow(number) {
            const next = Math.min(Number(number), this.high() - minDistance);
            this.value = range ? [next, this.high()] : Number(number);

            return next;
        },

        setHigh(number) {
            const next = Math.max(Number(number), this.low() + minDistance);
            this.value = [this.low(), next];

            return next;
        },

        fillStyle() {
            return range
                ? { background: `linear-gradient(90deg, var(--line) ${this.percent(this.low())}%, var(--ink) ${this.percent(this.low())}%, var(--ink) ${this.percent(this.high())}%, var(--line) ${this.percent(this.high())}%) center / 100% 6px no-repeat` }
                : { '--fill': `${this.percent(this.low())}%` };
        },
    }));

    /**
     * Campo de dinheiro: digitação preenche pelos centavos e formata no locale; o input escondido
     * guarda o número cru (1234.56), que é o que vai para o wire:model e para o formulário.
     */
    Alpine.data('uiMoney', ({ locale = 'pt-BR', decimals = 2, value = null } = {}) => ({
        display: '',

        init() {
            if (value !== null && value !== '') {
                this.apply(Math.round(parseFloat(value) * 10 ** decimals));
            }
        },

        format(cents) {
            return new Intl.NumberFormat(locale, { minimumFractionDigits: decimals, maximumFractionDigits: decimals }).format(cents / 10 ** decimals);
        },

        apply(cents) {
            this.display = cents === null ? '' : this.format(cents);
            this.$refs.raw.value = cents === null ? '' : (cents / 10 ** decimals).toFixed(decimals);
            this.$refs.raw.dispatchEvent(new Event('input', { bubbles: true }));
        },

        typed(event) {
            const digits = event.target.value.replace(/\D/g, '');
            this.apply(digits === '' ? null : parseInt(digits, 10));
        },
    }));

    /**
     * Campo numérico: − e +, setas ↑↓, limites min/max e passo. Dispara input para o wire:model.
     */
    Alpine.data('uiNumber', ({ min = null, max = null, step = 1 } = {}) => ({
        current: 0,

        init() {
            this.current = this.read();
        },

        read() {
            const value = parseFloat(String(this.$refs.input.value).replace(',', '.'));

            return Number.isNaN(value) ? 0 : value;
        },

        clamp(value) {
            const decimals = (String(step).split('.')[1] ?? '').length;
            let result = Number(value.toFixed(decimals));

            if (min !== null) {
                result = Math.max(min, result);
            }

            if (max !== null) {
                result = Math.min(max, result);
            }

            return result;
        },

        set(value) {
            this.current = this.clamp(value);
            this.$refs.input.value = this.current;
            this.$refs.input.dispatchEvent(new Event('input', { bubbles: true }));
            this.$refs.input.dispatchEvent(new Event('change', { bubbles: true }));
        },

        increment() {
            this.set(this.read() + step);
        },

        decrement() {
            this.set(this.read() - step);
        },

        normalize() {
            this.set(this.read());
        },

        atMin() {
            return min !== null && this.current <= min;
        },

        atMax() {
            return max !== null && this.current >= max;
        },
    }));

    /**
     * Paleta de comandos: filtra as opções pelo texto digitado, ↑↓ navega e ↵ escolhe.
     */
    Alpine.data('uiCommand', () => ({
        query: '',
        activeId: null,

        init() {
            this.$watch('query', () => this.$nextTick(() => this.activateFirst()));
            this.$nextTick(() => this.activateFirst());
        },

        normalize(text) {
            return (text ?? '').toString().normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase().trim();
        },

        isVisible(element) {
            const query = this.normalize(this.query);

            return query === '' || this.normalize(element.dataset.search).includes(query);
        },

        options() {
            return [...this.$root.querySelectorAll('[data-command-item]')].filter((item) => !item.disabled && this.isVisible(item));
        },

        hasResults() {
            return this.options().length > 0;
        },

        groupHasResults(group) {
            return [...group.querySelectorAll('[data-command-item]')].some((item) => this.isVisible(item));
        },

        activateFirst() {
            this.activeId = this.options()[0]?.id ?? null;
        },

        move(step) {
            const options = this.options();
            const index = options.findIndex((option) => option.id === this.activeId);
            const next = options[(index + step + options.length) % options.length];

            if (next) {
                this.activeId = next.id;
                next.scrollIntoView({ block: 'nearest' });
            }
        },

        choose() {
            document.getElementById(this.activeId)?.click();
        },
    }));

    /**
     * Tooltip: espera no hover, aparece na hora com o foco do teclado e Esc fecha.
     * Liga o primeiro elemento focável ao texto com aria-describedby.
     */
    Alpine.data('uiTooltip', ({ delay = 400, toggleable = false, disabled = false } = {}) => ({
        open: false,
        timer: null,

        init() {
            const trigger = this.$el.querySelector('button, a, input, select, textarea, [tabindex]');

            if (trigger) {
                const describedBy = trigger.getAttribute('aria-describedby');
                trigger.setAttribute('aria-describedby', [describedBy, this.$id('ui-tooltip')].filter(Boolean).join(' '));
            }
        },

        schedule() {
            if (disabled || toggleable) {
                return;
            }

            clearTimeout(this.timer);
            this.timer = setTimeout(() => (this.open = true), delay);
        },

        show() {
            if (!disabled) {
                clearTimeout(this.timer);
                this.open = true;
            }
        },

        hide() {
            clearTimeout(this.timer);
            this.open = false;
        },

        toggle() {
            if (toggleable && !disabled) {
                this.open = !this.open;
            }
        },
    }));

    /**
     * Grupo de abas: guarda a aba ativa para os painéis (tab.panel).
     */
    Alpine.data('uiTabGroup', () => ({
        selected: null,
    }));

    /**
     * Lista de abas: seleção, wire:model (x-modelable="selected") e teclado.
     */
    Alpine.data('uiTabs', ({ selected = null } = {}) => ({
        selected,

        init() {
            const tabs = this.tabs();

            tabs.forEach((tab, index) => {
                tab.dataset.tabName ??= String(index);
            });

            if (this.selected === null || this.selected === undefined) {
                const initial = tabs.find((tab) => tab.hasAttribute('data-selected')) ?? tabs.find((tab) => !tab.disabled);
                this.selected = initial?.dataset.tabName ?? null;
            }

            this.$watch('selected', (name) => this.$dispatch('ui-tab-change', name));
            this.$nextTick(() => this.$dispatch('ui-tab-change', this.selected));
        },

        tabs() {
            return [...this.$root.querySelectorAll('[data-tab]')].filter((tab) => tab.closest('[data-tabs]') === this.$root);
        },

        select(name) {
            this.selected = name;
        },

        /**
         * Setas, Home e End movem o foco e selecionam a aba (ativação automática).
         */
        moveFocus(event) {
            const tabs = this.tabs().filter((tab) => !tab.disabled);
            const index = tabs.indexOf(event.target.closest('[data-tab]'));

            if (index === -1) {
                return;
            }

            const targets = {
                ArrowRight: tabs[(index + 1) % tabs.length],
                ArrowLeft: tabs[(index - 1 + tabs.length) % tabs.length],
                Home: tabs[0],
                End: tabs[tabs.length - 1],
            };

            if (targets[event.key]) {
                event.preventDefault();
                targets[event.key].focus();
                this.select(targets[event.key].dataset.tabName);
            }
        },
    }));
});
