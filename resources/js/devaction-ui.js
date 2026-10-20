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
