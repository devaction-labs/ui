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
