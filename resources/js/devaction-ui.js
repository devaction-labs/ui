/**
 * DevAction UI · componentes Alpine.
 *
 * O Alpine já vem com o Livewire 4. Importe este arquivo no app.js do projeto:
 *   import '../../vendor/devaction/ui/resources/js/devaction-ui.js';
 */
document.addEventListener('alpine:init', () => {
    const Alpine = window.Alpine;

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
