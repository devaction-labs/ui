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
});
