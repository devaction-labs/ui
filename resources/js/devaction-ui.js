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

/**
 * Ancestral que vira o "viewport" de um elemento fixed (transform, filter, contain…); null = a janela.
 */
const fixedContainingBlock = (el) => {
    for (let node = el.parentElement; node && node !== document.documentElement; node = node.parentElement) {
        const style = getComputedStyle(node);

        if (
            style.transform !== 'none' || style.translate !== 'none' || style.scale !== 'none' || style.filter !== 'none'
            || (style.backdropFilter && style.backdropFilter !== 'none') || style.perspective !== 'none'
            || /paint|layout|strict|content/.test(style.contain) || /transform|filter|perspective/.test(style.willChange)
        ) {
            return node;
        }
    }

    return null;
};

/**
 * Posiciona um painel flutuante (dropdown, popover, calendário, tooltip…) a partir do gatilho.
 * Usa position: fixed, então nenhum overflow-hidden dos pais corta o painel; vira para o lado
 * oposto quando não cabe e acompanha rolagem e redimensionamento enquanto está visível.
 */
const floatPanel = (el, reference, { placement = 'bottom', align = 'start', gap = 6, offset = 0, match = false } = {}, container = null) => {
    const margin = 8;
    const anchor = reference.getBoundingClientRect();

    if (match) {
        el.style.width = `${anchor.width}px`;
    }

    Object.assign(el.style, { position: 'fixed', margin: '0', translate: 'none', right: 'auto', bottom: 'auto' });

    const width = el.offsetWidth;
    const height = el.offsetHeight;
    const viewport = { width: document.documentElement.clientWidth, height: window.innerHeight };

    const fits = {
        top: anchor.top - gap - height >= margin,
        bottom: anchor.bottom + gap + height <= viewport.height - margin,
        left: anchor.left - gap - width >= margin,
        right: anchor.right + gap + width <= viewport.width - margin,
    };
    const opposite = { top: 'bottom', bottom: 'top', left: 'right', right: 'left' };
    const side = ! fits[placement] && fits[opposite[placement]] ? opposite[placement] : placement;
    const isVertical = side === 'top' || side === 'bottom';

    let top;
    let left;

    if (isVertical) {
        top = side === 'bottom' ? anchor.bottom + gap : anchor.top - gap - height;
        left = align === 'end'
            ? anchor.right - width - offset
            : align === 'center' ? anchor.left + (anchor.width - width) / 2 : anchor.left + offset;
    } else {
        left = side === 'right' ? anchor.right + gap : anchor.left - gap - width;
        top = align === 'end'
            ? anchor.bottom - height - offset
            : align === 'center' ? anchor.top + (anchor.height - height) / 2 : anchor.top + offset;
    }

    left = Math.min(Math.max(left, margin), Math.max(margin, viewport.width - width - margin));
    top = Math.min(Math.max(top, margin), Math.max(margin, viewport.height - height - margin));

    if (container) {
        const box = container.getBoundingClientRect();
        top -= box.top - container.scrollTop;
        left -= box.left - container.scrollLeft;
    }

    el.style.top = `${Math.round(top)}px`;
    el.style.left = `${Math.round(left)}px`;
    el.dataset.side = side;
};

document.addEventListener('alpine:init', () => {
    const Alpine = window.Alpine;

    Alpine.magic('toast', () => window.DevActionUI.toast);

    /**
     * x-ui-float="{ placement: 'bottom', align: 'start', gap: 6, offset: 0, match: false, reference: $refs.trigger }"
     * Sem reference, ancora no floatReference do componente (ex.: o ponto do menu de contexto) ou no elemento pai.
     */
    Alpine.directive('ui-float', (el, { expression }, { evaluate, cleanup }) => {
        let frame = null;
        let container = null;

        const isVisible = () => el.getClientRects().length > 0;
        const place = () => {
            const options = expression ? evaluate(expression) : {};
            floatPanel(el, options.reference ?? Alpine.$data(el).floatReference ?? el.parentElement, options, container);
        };
        const tick = () => {
            if (! isVisible()) {
                frame = null;

                return;
            }

            place();
            frame = requestAnimationFrame(tick);
        };
        const start = () => {
            if (frame === null && isVisible()) {
                container = fixedContainingBlock(el);
                place();
                frame = requestAnimationFrame(tick);
            }
        };

        const observer = new MutationObserver(start);
        observer.observe(el, { attributes: true, attributeFilter: ['style', 'class', 'hidden'] });
        start();

        cleanup(() => {
            observer.disconnect();
            cancelAnimationFrame(frame);
        });
    });

    /**
     * Sidebar: recolhida no desktop (lembrada no navegador) e em drawer abaixo de 768 px.
     * $store.uiSidebar.toggle() faz a coisa certa para o tamanho da tela.
     */
    Alpine.store('uiSidebar', {
        collapsed: false,
        open: false,
        desktop: true,

        init() {
            const media = window.matchMedia('(min-width: 768px)');
            this.desktop = media.matches;
            media.addEventListener('change', (event) => {
                this.desktop = event.matches;
                this.open = false;
            });

            try {
                this.collapsed = window.localStorage.getItem('ui-sidebar-collapsed') === '1';
            } catch {
                this.collapsed = false;
            }
        },

        isCollapsed() {
            return this.desktop && this.collapsed;
        },

        toggle() {
            if (!this.desktop) {
                this.open = !this.open;

                return;
            }

            this.collapsed = !this.collapsed;

            try {
                window.localStorage.setItem('ui-sidebar-collapsed', this.collapsed ? '1' : '0');
            } catch {
                // Sem armazenamento local: a escolha vale só nesta página.
            }
        },

        close() {
            this.open = false;
        },
    });

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
    /**
     * Menu de contexto: botão direito (ou Shift+F10 / tecla Menu) abre o x-ui::menu no ponto do clique.
     * Oferece open/close como o dropdown, então os itens do menu funcionam igual.
     */
    Alpine.data('uiContextMenu', () => ({
        open: false,
        x: 0,
        y: 0,
        target: null,

        get floatReference() {
            const x = this.x;
            const y = this.y;

            return { getBoundingClientRect: () => new DOMRect(x, y, 0, 0) };
        },

        openAt(event) {
            if (event.target.closest('[data-dropdown-panel]')) {
                return;
            }

            event.preventDefault();
            this.target = event.target.closest('[tabindex], a, button, tr, li') ?? event.target;
            this.x = event.clientX;
            this.y = event.clientY;
            this.open = true;
            this.$dispatch('context-menu-open', { target: this.target });
        },

        openFromKeyboard() {
            const element = document.activeElement && this.$root.contains(document.activeElement) ? document.activeElement : this.$root;
            const rect = element.getBoundingClientRect();

            this.target = element;
            this.x = rect.left + 16;
            this.y = rect.bottom;
            this.open = true;
            this.$dispatch('context-menu-open', { target: this.target });
        },

        close(restoreFocus = false) {
            if (! this.open) {
                return;
            }

            this.open = false;

            if (restoreFocus) {
                this.target?.focus?.();
            }
        },
    }));

    /**
     * Aviso de cookies: guarda a escolha no cookie ui_cookie_consent (1 ano) e não aparece de novo.
     */
    Alpine.data('uiCookieConsent', ({ categories = [], force = false } = {}) => ({
        visible: false,
        preferences: false,
        choices: Object.fromEntries(categories.map((key) => [key, false])),

        init() {
            const saved = this.read();

            if (saved) {
                categories.forEach((key) => (this.choices[key] = Boolean(saved[key])));
            }

            this.visible = force || ! saved;
        },

        read() {
            const match = document.cookie.split('; ').find((item) => item.startsWith('ui_cookie_consent='));

            try {
                return match ? JSON.parse(decodeURIComponent(match.split('=').slice(1).join('='))) : null;
            } catch (error) {
                return null;
            }
        },

        save(all = null) {
            if (all !== null) {
                categories.forEach((key) => (this.choices[key] = all));
            }

            const consent = { necessary: true, ...this.choices, at: new Date().toISOString() };
            document.cookie = `ui_cookie_consent=${encodeURIComponent(JSON.stringify(consent))}; max-age=31536000; path=/; SameSite=Lax`;

            this.$dispatch('cookie-consent-updated', consent);
            this.visible = force;
            this.preferences = false;
        },
    }));

    /**
     * Central de notificações: filtra pela aba, conta as não lidas e marca como lida no clique.
     */
    Alpine.data('uiNotificationCenter', ({ tab = 'all', markAll = null } = {}) => ({
        tab,
        revision: 0,

        items() {
            this.revision;

            return [...this.$root.querySelectorAll('[data-notification]')];
        },

        matches(item) {
            return this.tab === 'all' || item.dataset.type === this.tab;
        },

        isUnread(item) {
            this.revision;

            return item?.dataset.unread === 'true';
        },

        unread(type) {
            return this.items().filter((item) => item.dataset.unread === 'true' && (type === 'all' || item.dataset.type === type)).length;
        },

        visibleCount() {
            return this.items().filter((item) => this.matches(item)).length;
        },

        groupHasItems(group) {
            this.revision;

            return [...group.querySelectorAll('[data-notification]')].some((item) => this.matches(item));
        },

        read(item) {
            if (item.dataset.unread !== 'true') {
                return;
            }

            item.dataset.unread = 'false';
            this.revision++;
            this.$dispatch('notification-read', { id: item.id || null });
        },

        readAll() {
            this.items().forEach((item) => (item.dataset.unread = 'false'));
            this.revision++;
            this.$dispatch('notifications-read-all');

            if (markAll && this.$wire) {
                this.$wire.call(markAll);
            }
        },
    }));

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
    Alpine.data('uiCombobox', ({ value = null, multiple = false, filter = true, clearQuery = false } = {}) => ({
        open: false,
        query: '',
        value: multiple ? (Array.isArray(value) ? value.map(String) : []) : value,
        activeValue: null,
        labels: {},
        revision: 0,
        isMultiple: multiple,

        init() {
            this.$watch('query', () => this.$nextTick(() => (this.activeValue = this.options()[0]?.dataset.value ?? null)));
            this.rememberLabels();

            // Opções que chegam do servidor (busca com :filter="false") entram sem recarregar a página
            let signature = this.optionSignature();
            const observer = new MutationObserver(() => {
                const next = this.optionSignature();

                if (next !== signature) {
                    signature = next;
                    this.revision++;
                    this.rememberLabels();
                }
            });
            observer.observe(this.$root, { childList: true, subtree: true });
        },

        optionSignature() {
            return [...this.$root.querySelectorAll('[data-combobox-option]')].map((option) => option.dataset.value + ':' + option.dataset.label).join('|');
        },

        normalize(text) {
            return (text ?? '').toString().normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase().trim();
        },

        allOptions() {
            this.revision;

            return [...this.$root.querySelectorAll('[data-combobox-option]')];
        },

        rememberLabels() {
            const labels = { ...this.labels };

            this.allOptions().forEach((option) => {
                labels[option.dataset.value] = {
                    text: option.dataset.label,
                    html: option.querySelector('[data-combobox-label]')?.innerHTML ?? option.dataset.label,
                };
            });

            this.labels = labels;
        },

        isVisible(option) {
            const query = this.normalize(this.query);

            return ! filter || query === '' || option.hasAttribute('data-combobox-unfiltered') || this.normalize(option.dataset.search).includes(query);
        },

        options() {
            return this.allOptions().filter((option) => ! option.disabled && this.isVisible(option));
        },

        hasResults() {
            return this.options().length > 0;
        },

        selectedValues() {
            if (multiple) {
                return this.value ?? [];
            }

            return this.value === null || this.value === undefined || this.value === '' ? [] : [String(this.value)];
        },

        isSelected(optionValue) {
            return this.selectedValues().includes(String(optionValue));
        },

        get selectedLabel() {
            return this.selectedValues().map((item) => this.labels[item]?.text ?? item).join(', ');
        },

        get selectedHtml() {
            const values = this.selectedValues();

            if (values.length === 1 || ! multiple) {
                return values[0] !== undefined ? (this.labels[values[0]]?.html ?? '') : '';
            }

            return '';
        },

        toggle() {
            this.open ? this.close() : this.show();
        },

        show() {
            this.open = true;

            if (filter) {
                this.query = '';
            }

            this.activeValue = this.selectedValues()[0] ?? (this.options()[0]?.dataset.value ?? null);
            this.$nextTick(() => (this.$refs.search ?? this.$root.querySelector('[data-combobox-search]'))?.focus());
        },

        close(restoreFocus = false) {
            this.open = false;

            if (restoreFocus) {
                this.$refs.trigger.focus();
            }
        },

        choose(option) {
            const optionValue = option.dataset.value;

            if (multiple) {
                this.value = this.isSelected(optionValue)
                    ? this.value.filter((item) => item !== optionValue)
                    : [...this.value, optionValue];
            } else {
                this.value = optionValue;
            }

            if (this.$refs.raw) {
                this.$refs.raw.value = multiple ? this.value.join(',') : optionValue;
                this.$refs.raw.dispatchEvent(new Event('change', { bubbles: true }));
            }

            if (! multiple) {
                this.close(true);
            }

            if (clearQuery && this.query !== '') {
                this.query = '';
                this.$nextTick(() => this.$root.querySelector('[data-combobox-search]')?.dispatchEvent(new Event('input', { bubbles: true })));
            }
        },

        unselect(optionValue) {
            this.value = multiple ? this.value.filter((item) => item !== String(optionValue)) : null;
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
    Alpine.data('uiAutocomplete', ({ minChars = 1, filter = true } = {}) => ({
        open: false,
        query: '',
        activeIndex: 0,
        revision: 0,

        init() {
            this.query = this.$refs.input.value;
            this.$watch('query', () => (this.activeIndex = 0));

            // Sugestões que chegam do servidor (:filter="false") aparecem sem esperar outra tecla
            let signature = this.itemSignature();
            new MutationObserver(() => {
                const next = this.itemSignature();

                if (next !== signature) {
                    signature = next;
                    this.revision++;
                }
            }).observe(this.$root, { childList: true, subtree: true });
        },

        itemSignature() {
            return [...this.$root.querySelectorAll('[data-autocomplete-item]')].map((item) => item.dataset.value).join('|');
        },

        normalize(text) {
            return (text ?? '').toString().normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase();
        },

        matches(item) {
            const query = this.normalize(this.query.trim());

            return ! filter || item.hasAttribute('data-autocomplete-always') || query === '' || this.normalize(item.dataset.search).includes(query);
        },

        items() {
            this.revision;

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
     * Composer de IA: textarea que cresce até 8 linhas, Enter envia, Shift+Enter quebra a linha,
     * Esc para a resposta. value é x-modelable. Com submit/stop chama o método do Livewire;
     * sem eles, dispara ai-prompt-submit e ai-prompt-stop.
     */
    Alpine.data('uiPrompt', ({ max = null, submit = null, stop = null, busy = false, busyProperty = null } = {}) => ({
        value: '',
        busyState: busy,

        isBusy() {
            return busyProperty && this.$wire ? Boolean(this.$wire[busyProperty]) : this.busyState;
        },

        init() {
            this.$nextTick(() => this.grow());
            this.$watch('value', () => this.$nextTick(() => this.grow()));
        },

        grow() {
            const area = this.$refs.input;
            const lineHeight = parseFloat(getComputedStyle(area).lineHeight) || 23;
            area.style.height = 'auto';
            area.style.height = `${Math.min(area.scrollHeight, lineHeight * 8 + 2)}px`;
            area.style.overflowY = area.scrollHeight > lineHeight * 8 + 2 ? 'auto' : 'hidden';
        },

        length() {
            return (this.value ?? '').length;
        },

        overLimit() {
            return max !== null && this.length() > max;
        },

        canSend() {
            return (this.value ?? '').trim() !== '' && !this.overLimit() && !this.isBusy();
        },

        counter() {
            const format = (number) => new Intl.NumberFormat('pt-BR').format(number);

            return max === null ? '' : `${format(this.length())} / ${format(max)}`;
        },

        keydown(event) {
            if (event.key === 'Enter' && !event.shiftKey && !event.isComposing) {
                event.preventDefault();
                this.send();
            }

            if (event.key === 'Escape' && this.isBusy()) {
                event.preventDefault();
                this.halt();
            }
        },

        send() {
            if (this.isBusy()) {
                this.halt();

                return;
            }

            if (!this.canSend()) {
                return;
            }

            const text = this.value;
            this.$dispatch('ai-prompt-submit', { text });

            if (submit && this.$wire) {
                this.$wire.call(submit, text);
            }
        },

        halt() {
            this.$dispatch('ai-prompt-stop');

            if (stop && this.$wire) {
                this.$wire.call(stop);
            }
        },

        insert(text) {
            const area = this.$refs.input;
            const start = area.selectionStart ?? this.value.length;
            this.value = this.value.slice(0, start) + text + this.value.slice(area.selectionEnd ?? start);
            this.$nextTick(() => {
                area.focus();
                area.setSelectionRange(start + text.length, start + text.length);
            });
        },
    }));

    /**
     * Tabela: seleção em massa (selected é x-modelable, então wire:model recebe as chaves marcadas)
     * e linhas expansíveis.
     */
    Alpine.data('uiTable', () => ({
        selected: [],
        expanded: {},

        keys() {
            return [...this.$root.querySelectorAll('[data-table-select]')].map((input) => input.value);
        },

        allState() {
            const keys = this.keys();
            const count = keys.filter((key) => this.selected.includes(key)).length;

            if (count === 0) {
                return 'false';
            }

            return count === keys.length ? 'true' : 'mixed';
        },

        toggleAll() {
            const keys = this.keys();
            this.selected = this.allState() === 'true' ? this.selected.filter((key) => !keys.includes(key)) : [...new Set([...this.selected, ...keys])];
        },

        clearSelection() {
            this.selected = [];
        },

        isSelected(key) {
            return this.selected.includes(String(key));
        },

        toggleRow(key) {
            this.expanded = { ...this.expanded, [key]: !this.expanded[key] };
        },
    }));

    /**
     * Editor de texto rico sem dependências: contenteditable + execCommand, barra de formatação
     * com estado (aria-pressed) e menu de comandos ao digitar "/" numa linha vazia.
     * value (x-modelable) é o HTML; sanitize no servidor antes de exibir para outras pessoas.
     */
    Alpine.data('uiEditor', ({ value = '', mentionSearch = null } = {}) => ({
        mentionOpen: false,
        mentionQuery: '',
        mentionIndex: 0,
        mentionTop: 0,
        mentionLeft: 0,
        mentionNode: null,
        mentionOffset: 0,

        value,
        active: {},
        block: 'p',
        slashOpen: false,
        slashQuery: '',
        slashIndex: 0,
        slashTop: 0,
        slashLeft: 0,

        init() {
            this.$refs.content.innerHTML = this.value ?? '';
            document.execCommand('defaultParagraphSeparator', false, 'p');
            this.$watch('value', (next) => {
                if (next !== this.$refs.content.innerHTML && document.activeElement !== this.$refs.content) {
                    this.$refs.content.innerHTML = next ?? '';
                }
            });
            this.onSelection = () => this.refreshState();
            document.addEventListener('selectionchange', this.onSelection);
        },

        destroy() {
            document.removeEventListener('selectionchange', this.onSelection);
        },

        refreshState() {
            if (!this.$refs.content.contains(window.getSelection()?.anchorNode ?? null)) {
                return;
            }

            this.active = Object.fromEntries(['bold', 'italic', 'strikeThrough', 'insertUnorderedList', 'insertOrderedList'].map((command) => [command, document.queryCommandState(command)]));
            const format = String(document.queryCommandValue('formatBlock') || 'p').toLowerCase().replace(/[<>]/g, '');
            this.block = format === 'div' ? 'p' : format;
            this.active.code = Boolean(this.closest('code'));
            this.active.link = Boolean(this.closest('a'));
        },

        closest(tag) {
            let node = window.getSelection()?.anchorNode ?? null;

            while (node && node !== this.$refs.content) {
                if (node.nodeName?.toLowerCase() === tag) {
                    return node;
                }

                node = node.parentNode;
            }

            return null;
        },

        sync() {
            const html = this.$refs.content.innerHTML;
            this.value = html === '<p><br></p>' || html === '<br>' ? '' : html;
        },

        run(command, argument = null) {
            this.$refs.content.focus();
            document.execCommand(command, false, argument);
            this.sync();
            this.refreshState();
        },

        setBlock(tag) {
            this.run('formatBlock', `<${tag}>`);
        },

        toggleCode() {
            const code = this.closest('code');

            if (code) {
                code.replaceWith(...code.childNodes);
                this.sync();

                return;
            }

            const text = window.getSelection()?.toString() || 'código';
            this.run('insertHTML', `<code>${text.replace(/[&<>]/g, (character) => `&#${character.charCodeAt(0)};`)}</code>&nbsp;`);
        },

        link() {
            if (this.closest('a')) {
                this.run('unlink');

                return;
            }

            const url = window.prompt('Endereço do link', 'https://');

            if (url && url !== 'https://') {
                this.run('createLink', url);
            }
        },

        slashItems() {
            const query = this.slashQuery.toLowerCase();

            return [...this.$root.querySelectorAll('[data-slash-item]')].filter((item) => item.dataset.label.toLowerCase().includes(query));
        },

        mentionItems() {
            const query = (this.mentionQuery ?? '').normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();

            return [...this.$root.querySelectorAll('[data-mention-item]')].filter((item) => item.dataset.search.includes(query));
        },

        openMention() {
            const selection = window.getSelection();

            if (! selection?.rangeCount) {
                return;
            }

            const range = selection.getRangeAt(0);
            const caret = range.getBoundingClientRect();
            const box = this.$refs.content.getBoundingClientRect();

            this.mentionNode = selection.anchorNode;
            this.mentionOffset = selection.anchorOffset;
            this.mentionTop = (caret?.bottom || box.top) - box.top + 6;
            this.mentionLeft = Math.max(0, (caret?.left || box.left) - box.left - 6);
            this.mentionQuery = '';
            this.mentionIndex = 0;
            this.mentionOpen = true;
            this.searchMentions();
        },

        trackMention() {
            const selection = window.getSelection();

            if (! selection || selection.anchorNode !== this.mentionNode || selection.anchorOffset < this.mentionOffset) {
                this.mentionOpen = false;

                return;
            }

            const query = (this.mentionNode.textContent ?? '').slice(this.mentionOffset, selection.anchorOffset);

            if (query.length > 30 || /\s{2,}|\n/.test(query)) {
                this.mentionOpen = false;

                return;
            }

            this.mentionQuery = query;
            this.mentionIndex = 0;
            this.searchMentions();
        },

        searchMentions() {
            if (mentionSearch && this.$wire) {
                this.$wire.set(mentionSearch, this.mentionQuery);
            }
        },

        chooseMention(item) {
            const selection = window.getSelection();
            const node = this.mentionNode;

            if (! node || ! selection) {
                return;
            }

            const end = selection.anchorNode === node ? selection.anchorOffset : this.mentionOffset + this.mentionQuery.length;
            const range = document.createRange();
            range.setStart(node, Math.max(0, this.mentionOffset - 1));
            range.setEnd(node, Math.min(end, node.textContent.length));
            range.deleteContents();

            const mention = document.createElement('span');
            mention.dataset.mention = item.dataset.value;
            mention.contentEditable = 'false';
            mention.textContent = `@${item.dataset.label}`;

            const space = document.createTextNode('\u00a0');
            range.insertNode(space);
            range.insertNode(mention);

            const caret = document.createRange();
            caret.setStartAfter(space);
            caret.collapse(true);
            selection.removeAllRanges();
            selection.addRange(caret);

            this.mentionOpen = false;
            this.sync();
            this.$dispatch('editor-mention', { value: item.dataset.value, label: item.dataset.label });
        },

        runCommand(name) {
            const line = this.closestBlock();

            if (line) {
                line.textContent = '';
            }

            this.slashOpen = false;
            this.sync();
            this.$dispatch('editor-command', { name });
        },

        keydown(event) {
            if (this.mentionOpen) {
                const items = this.mentionItems();

                if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
                    event.preventDefault();
                    this.mentionIndex = (this.mentionIndex + (event.key === 'ArrowDown' ? 1 : -1) + items.length) % (items.length || 1);

                    return;
                }

                if ((event.key === 'Enter' || event.key === 'Tab') && items.length) {
                    event.preventDefault();
                    this.chooseMention(items[this.mentionIndex] ?? items[0]);

                    return;
                }

                if (event.key === 'Escape') {
                    event.preventDefault();
                    event.stopPropagation();
                    this.mentionOpen = false;

                    return;
                }
            }

            if (event.key === '@' && ! this.mentionOpen && this.$root.querySelector('[data-mention-item], [role=listbox][aria-label=Pessoas]')) {
                this.$nextTick(() => this.openMention());
            }

            if (this.slashOpen) {
                const items = this.slashItems();

                if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
                    event.preventDefault();
                    this.slashIndex = (this.slashIndex + (event.key === 'ArrowDown' ? 1 : -1) + items.length) % (items.length || 1);

                    return;
                }

                if (event.key === 'Enter') {
                    event.preventDefault();
                    items[this.slashIndex]?.click();

                    return;
                }

                if (event.key === 'Escape') {
                    event.preventDefault();
                    event.stopPropagation();
                    this.slashOpen = false;

                    return;
                }
            }

            if (event.key === '/' && !this.slashOpen) {
                const line = this.closestBlock();

                if (!line || line.textContent.trim() === '') {
                    this.$nextTick(() => this.openSlash());
                }
            }
        },

        input() {
            this.sync();

            if (this.mentionOpen) {
                this.trackMention();
            }

            if (this.slashOpen) {
                const text = this.closestBlock()?.textContent ?? '';

                if (!text.startsWith('/')) {
                    this.slashOpen = false;

                    return;
                }

                this.slashQuery = text.slice(1);
                this.slashIndex = 0;
            }
        },

        closestBlock() {
            let node = window.getSelection()?.anchorNode ?? null;

            while (node && node.parentNode !== this.$refs.content) {
                node = node.parentNode;
            }

            return node && node !== this.$refs.content ? node : null;
        },

        openSlash() {
            const range = window.getSelection()?.getRangeAt(0);
            const caret = range?.getBoundingClientRect();
            const box = this.$refs.content.getBoundingClientRect();
            this.slashTop = (caret?.bottom ?? box.top) - box.top + 6;
            this.slashLeft = Math.max(0, (caret?.left ?? box.left) - box.left - 6);
            this.slashQuery = '';
            this.slashIndex = 0;
            this.slashOpen = true;
        },

        chooseSlash(command, argument = null) {
            const line = this.closestBlock();

            if (line) {
                line.textContent = '';
                const range = document.createRange();
                range.setStart(line, 0);
                range.collapse(true);
                window.getSelection().removeAllRanges();
                window.getSelection().addRange(range);
            }

            this.slashOpen = false;

            if (command === 'formatBlock') {
                this.setBlock(argument);
            } else if (command === 'code') {
                this.toggleCode();
            } else {
                this.run(command, argument);
            }
        },

        paste(event) {
            event.preventDefault();
            document.execCommand('insertText', false, event.clipboardData.getData('text/plain'));
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
    /**
     * Intervalo de datas: dois meses, atalhos, horário opcional e Aplicar/Cancelar.
     * value é x-modelable: { start, end } (mais start_time e end_time com horário).
     */
    /**
     * Painéis redimensionáveis: tamanho do primeiro painel em %, com arrastar, teclado e duplo clique.
     */
    /**
     * Lista ordenável: arrastar pela alça, teclado (Espaço pega, setas movem, Espaço solta, Esc cancela)
     * e botões. Move os elementos no DOM e avisa a nova ordem (evento reorder e, com sort, o Livewire).
     */
    /**
     * Árvore de arquivos: pastas abertas por padrão, filtro por texto e por tipo, setas navegam
     * (← fecha ou sobe para a pasta, → abre). value é x-modelable com o caminho escolhido.
     */
    /**
     * Diff com comentários: + abre o comentário novo na linha; conversas existentes abrem e fecham;
     * Resolver recolhe. Comentar dispara diff-comment e, com comment, chama o Livewire com (linha, texto).
     */
    Alpine.data('uiDiff', ({ comment = null } = {}) => ({
        draftLine: null,
        threads: {},

        registerThread(line, resolved) {
            this.threads = { ...this.threads, [line]: ! resolved };
        },

        hasThread(line) {
            return line in this.threads;
        },

        isThreadOpen(line) {
            return this.threads[line] === true;
        },

        toggleDraft(line) {
            if (this.hasThread(line)) {
                this.threads = { ...this.threads, [line]: ! this.threads[line] };

                return;
            }

            this.draftLine = this.draftLine === line ? null : line;
            this.$nextTick(() => this.$root.querySelector(`#diff-comment-${line}-new`)?.focus());
        },

        send(line, body) {
            const text = (body ?? '').trim();

            if (text === '') {
                return;
            }

            this.$dispatch('diff-comment', { line, body: text });

            if (comment && this.$wire) {
                this.$wire.call(comment, line, text);
            }

            this.draftLine = null;
        },

        resolveThread(line) {
            this.threads = { ...this.threads, [line]: false };
            this.$dispatch('thread-resolved', { line });
        },
    }));

    Alpine.data('uiFileTree', ({ value = null } = {}) => ({
        value,
        query: '',
        filter: 'all',
        closed: {},
        focusPath: null,

        nodes() {
            return [...this.$root.querySelectorAll('[data-tree-node]')];
        },

        isOpen(path) {
            return ! this.closed[path];
        },

        fileMatches(node) {
            const query = this.query.trim().toLowerCase();
            const byText = query === '' || node.dataset.path.toLowerCase().includes(query);
            const byFilter = this.filter === 'all'
                || (this.filter === 'findings' && Number(node.dataset.findings) > 0)
                || (this.filter === 'new' && node.dataset.status === 'A');

            return byText && byFilter;
        },

        matches(node) {
            if (! node.hasAttribute('data-directory')) {
                return this.fileMatches(node);
            }

            const prefix = `${node.dataset.path}/`;

            return this.nodes().some((other) => ! other.hasAttribute('data-directory') && other.dataset.path.startsWith(prefix) && this.fileMatches(other));
        },

        isVisible(node) {
            const parents = node.dataset.parents ? node.dataset.parents.split('|') : [];
            const filtering = this.query.trim() !== '' || this.filter !== 'all';

            return this.matches(node) && (filtering || parents.every((parent) => this.isOpen(parent)));
        },

        hasMatches() {
            return this.nodes().some((node) => ! node.hasAttribute('data-directory') && this.fileMatches(node));
        },

        visibleNodes() {
            return this.nodes().filter((node) => this.isVisible(node));
        },

        isFocusTarget(node) {
            const target = this.focusPath ?? this.value ?? this.visibleNodes()[0]?.dataset.path;

            return node.dataset.path === target;
        },

        activate(node) {
            this.focusPath = node.dataset.path;

            if (node.hasAttribute('data-directory')) {
                this.closed = { ...this.closed, [node.dataset.path]: this.isOpen(node.dataset.path) };

                return;
            }

            this.value = node.dataset.path;
            this.$dispatch('select-file', { path: node.dataset.path });
        },

        onKeydown(event) {
            const visible = this.visibleNodes();
            const current = event.target.closest('[data-tree-node]');
            const index = visible.indexOf(current);
            let target = null;

            if (event.key === 'ArrowDown') {
                target = visible[Math.min(index + 1, visible.length - 1)];
            } else if (event.key === 'ArrowUp') {
                target = visible[Math.max(index - 1, 0)];
            } else if (event.key === 'Home') {
                target = visible[0];
            } else if (event.key === 'End') {
                target = visible[visible.length - 1];
            } else if (event.key === 'ArrowRight' && current?.hasAttribute('data-directory')) {
                if (! this.isOpen(current.dataset.path)) {
                    this.closed = { ...this.closed, [current.dataset.path]: false };
                } else {
                    target = visible[index + 1];
                }
            } else if (event.key === 'ArrowLeft' && current) {
                if (current.hasAttribute('data-directory') && this.isOpen(current.dataset.path)) {
                    this.closed = { ...this.closed, [current.dataset.path]: true };
                } else {
                    const parent = current.dataset.parents.split('|').filter(Boolean).pop();
                    target = parent ? this.nodes().find((node) => node.dataset.path === parent) : null;
                }
            } else {
                return;
            }

            event.preventDefault();

            if (target) {
                this.focusPath = target.dataset.path;
                target.focus();
            }
        },
    }));

    Alpine.data('uiSortableList', ({ sort = null } = {}) => ({
        grabbed: null,
        origin: null,
        message: '',
        revision: 0,

        items() {
            this.revision;

            return [...this.$refs.list.querySelectorAll(':scope > [data-sortable-item]')];
        },

        isFirst(item) {
            return this.items()[0] === item;
        },

        isLast(item) {
            const items = this.items();

            return items[items.length - 1] === item;
        },

        place(item, index) {
            const others = this.items().filter((other) => other !== item);
            const target = others[index];

            target ? this.$refs.list.insertBefore(item, target) : this.$refs.list.appendChild(item);
            this.revision++;
        },

        moveBy(item, step) {
            const index = this.items().indexOf(item);
            const next = Math.max(0, Math.min(this.items().length - 1, index + step));

            if (next === index) {
                return;
            }

            this.place(item, next);

            if (this.grabbed === null) {
                this.commit(item);
            } else {
                this.announce(item);
            }

            this.$nextTick(() => item.querySelector('[data-sortable-handle]')?.focus());
        },

        announce(item) {
            const items = this.items();
            this.message = `${item.dataset.label} movido para a posição ${items.indexOf(item) + 1} de ${items.length}.`;
        },

        commit(item) {
            const items = this.items();
            const position = items.indexOf(item);
            const order = items.map((element) => element.dataset.key);

            this.announce(item);
            this.$dispatch('reorder', { key: item.dataset.key, position, order });

            if (sort && this.$wire) {
                this.$wire.call(sort, item.dataset.key, position);
            }
        },

        onKeydown(event, item) {
            if (event.key === ' ' || event.key === 'Enter') {
                event.preventDefault();

                if (this.grabbed === item.dataset.key) {
                    this.drop(item);
                } else {
                    this.grabbed = item.dataset.key;
                    this.origin = this.items().indexOf(item);
                    this.message = `${item.dataset.label} pego. Use as setas e aperte Espaço para soltar.`;
                }
            } else if (this.grabbed === item.dataset.key && (event.key === 'ArrowUp' || event.key === 'ArrowDown')) {
                event.preventDefault();
                this.moveBy(item, event.key === 'ArrowUp' ? -1 : 1);
            } else if (this.grabbed === item.dataset.key && event.key === 'Escape') {
                event.preventDefault();
                event.stopPropagation();
                this.place(item, this.origin);
                this.grabbed = null;
                this.message = `${item.dataset.label} voltou para a posição ${this.origin + 1}.`;
                this.$nextTick(() => item.querySelector('[data-sortable-handle]')?.focus());
            }
        },

        drop(item, silent = false) {
            if (this.grabbed !== item.dataset.key) {
                return;
            }

            this.grabbed = null;

            if (this.items().indexOf(item) !== this.origin) {
                this.commit(item);
            } else if (! silent) {
                this.message = `${item.dataset.label} ficou na posição ${this.origin + 1}.`;
            }
        },

        startDrag(event, item) {
            if (event.button !== 0) {
                return;
            }

            event.preventDefault();
            this.grabbed = item.dataset.key;
            this.origin = this.items().indexOf(item);

            const move = (moveEvent) => {
                const others = this.items().filter((other) => other !== item);
                const index = others.findIndex((other) => {
                    const box = other.getBoundingClientRect();

                    return moveEvent.clientY < box.top + box.height / 2;
                });
                const target = index === -1 ? others.length : index;

                if (this.items().indexOf(item) !== target) {
                    this.place(item, target);
                }
            };
            const stop = () => {
                window.removeEventListener('pointermove', move);
                window.removeEventListener('pointerup', stop);
                window.removeEventListener('pointercancel', stop);
                this.drop(item, true);
            };

            // Na janela: mover o item no DOM faria a alça perder a captura do ponteiro
            window.addEventListener('pointermove', move);
            window.addEventListener('pointerup', stop);
            window.addEventListener('pointercancel', stop);
        },
    }));

    Alpine.data('uiResizable', ({ size = 50, min = 20, max = 80, vertical = false, persist = null } = {}) => ({
        size,
        initial: size,
        min,
        max,
        vertical,

        init() {
            try {
                const saved = persist ? Number(localStorage.getItem(`ui-resizable:${persist}`)) : 0;

                if (saved) {
                    this.size = this.clamp(saved);
                }
            } catch (error) {}

            this.$watch('size', (value) => {
                try {
                    if (persist) {
                        localStorage.setItem(`ui-resizable:${persist}`, String(Math.round(value)));
                    }
                } catch (error) {}
            });
        },

        clamp(value) {
            return Math.min(max, Math.max(min, value));
        },

        startDrag(event) {
            event.preventDefault();
            const handle = event.currentTarget;
            const box = this.$root.getBoundingClientRect();

            handle.setPointerCapture?.(event.pointerId);

            const move = (moveEvent) => {
                const ratio = vertical
                    ? (moveEvent.clientY - box.top) / box.height
                    : (moveEvent.clientX - box.left) / box.width;
                this.size = this.clamp(ratio * 100);
            };
            const stop = () => {
                handle.removeEventListener('pointermove', move);
                handle.removeEventListener('pointerup', stop);
                handle.removeEventListener('pointercancel', stop);
                this.$dispatch('resized', { size: Math.round(this.size) });
            };

            handle.addEventListener('pointermove', move);
            handle.addEventListener('pointerup', stop);
            handle.addEventListener('pointercancel', stop);
        },

        onKeydown(event) {
            const amount = event.shiftKey ? 10 : 5;
            const keys = vertical ? { back: 'ArrowUp', forward: 'ArrowDown' } : { back: 'ArrowLeft', forward: 'ArrowRight' };

            if (event.key === keys.back) {
                this.size = this.clamp(this.size - amount);
            } else if (event.key === keys.forward) {
                this.size = this.clamp(this.size + amount);
            } else if (event.key === 'Home') {
                this.size = min;
            } else if (event.key === 'End') {
                this.size = max;
            } else if (event.key === 'Enter') {
                this.reset();
            } else {
                return;
            }

            event.preventDefault();
        },

        reset() {
            this.size = this.initial;
        },
    }));

    Alpine.data('uiDateRange', ({ value = null, min = null, max = null, locale = 'pt-BR', today = null, time = false, step = 15, presets = [], inline = false } = {}) => {
        const pad = (number) => String(number).padStart(2, '0');
        const toIso = (date) => `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`;
        const fromIso = (iso) => {
            const [year, month, day] = String(iso).split('-').map(Number);

            return new Date(year, month - 1, day);
        };
        const toMinutes = (clock) => {
            const [hours, minutes] = String(clock ?? '00:00').split(':').map(Number);

            return hours * 60 + minutes;
        };
        const empty = () => ({ start: null, end: null, ...(time ? { start_time: '09:00', end_time: '10:00' } : {}) });

        return {
            open: inline,
            value: { ...empty(), ...(value ?? {}) },
            draft: null,
            today: today ?? toIso(new Date()),
            month: null,
            hovered: null,
            activePreset: null,
            timeFocus: 'start',

            init() {
                this.draft = { ...this.value };
                this.month = (this.value.start ?? this.today).slice(0, 7);
                this.$watch('value', (next) => {
                    if (! this.open || inline) {
                        this.draft = { ...empty(), ...(next ?? {}) };
                    }
                });
            },

            monthAt(offset) {
                const date = fromIso(`${this.month}-01`);
                date.setMonth(date.getMonth() + offset);

                return toIso(date).slice(0, 7);
            },

            monthLabel(offset) {
                return new Intl.DateTimeFormat(locale, { month: 'long', year: 'numeric' }).format(fromIso(`${this.monthAt(offset)}-01`));
            },

            weekdays() {
                const sunday = new Date(2026, 9, 4);

                return [...Array(7)].map((_, index) => {
                    const date = new Date(sunday);
                    date.setDate(sunday.getDate() + index);

                    return new Intl.DateTimeFormat(locale, { weekday: 'narrow' }).format(date);
                });
            },

            days(offset) {
                const month = this.monthAt(offset);
                const first = fromIso(`${month}-01`);
                const total = new Date(first.getFullYear(), first.getMonth() + 1, 0).getDate();
                const blanks = [...Array(first.getDay())].map((_, index) => ({ key: `${month}-blank-${index}`, iso: null, column: index }));

                return blanks.concat([...Array(total)].map((_, index) => {
                    const iso = `${month}-${pad(index + 1)}`;

                    return { key: iso, iso, day: index + 1, column: (first.getDay() + index) % 7 };
                }));
            },

            shift(step) {
                this.month = this.monthAt(step);
            },

            isDisabled(iso) {
                return (min !== null && iso < min) || (max !== null && iso > max);
            },

            isEdge(iso) {
                return iso !== null && (iso === this.draft.start || iso === this.draft.end);
            },

            rangeEnd() {
                return this.draft.end ?? (this.draft.start && this.hovered && this.hovered > this.draft.start ? this.hovered : null);
            },

            inBand(iso) {
                const end = this.rangeEnd();

                return iso !== null && this.draft.start !== null && end !== null && iso >= this.draft.start && iso <= end && this.draft.start !== end;
            },

            bandShape(cell) {
                if (! this.inBand(cell.iso)) {
                    return '';
                }

                const end = this.rangeEnd();
                const startsRow = cell.iso === this.draft.start || cell.column === 0;
                const endsRow = cell.iso === end || cell.column === 6;

                return ['bg-wrap', startsRow ? 'rounded-l-[10px]' : '', endsRow ? 'rounded-r-[10px]' : ''].join(' ');
            },

            pick(iso) {
                if (iso === null || this.isDisabled(iso)) {
                    return;
                }

                this.activePreset = 'custom';

                if (! this.draft.start || this.draft.end) {
                    this.draft = { ...this.draft, start: iso, end: null };
                } else if (iso < this.draft.start) {
                    this.draft = { ...this.draft, start: iso, end: this.draft.start };
                } else {
                    this.draft = { ...this.draft, end: iso };
                }

                if (inline && this.draft.end) {
                    this.apply();
                }
            },

            presetList() {
                const date = fromIso(this.today);
                const firstOfMonth = toIso(new Date(date.getFullYear(), date.getMonth(), 1));
                const lastMonthStart = toIso(new Date(date.getFullYear(), date.getMonth() - 1, 1));
                const lastMonthEnd = toIso(new Date(date.getFullYear(), date.getMonth(), 0));
                const quarterStart = toIso(new Date(date.getFullYear(), Math.floor(date.getMonth() / 3) * 3, 1));
                const weekAgo = new Date(date);
                weekAgo.setDate(date.getDate() - 6);

                return [
                    { key: 'today', label: 'Hoje', start: this.today, end: this.today },
                    { key: '7d', label: 'Últimos 7 dias', start: toIso(weekAgo), end: this.today },
                    { key: 'month', label: 'Este mês', start: firstOfMonth, end: this.today },
                    { key: 'last-month', label: 'Mês passado', start: lastMonthStart, end: lastMonthEnd },
                    { key: 'quarter', label: 'Este trimestre', start: quarterStart, end: this.today },
                    ...presets.map((preset, index) => ({ key: `custom-${index}`, ...preset })),
                ];
            },

            choosePreset(preset) {
                this.activePreset = preset.key;
                this.draft = { ...this.draft, start: preset.start, end: preset.end };
                this.month = preset.start.slice(0, 7);

                if (inline) {
                    this.apply();
                }
            },

            slots() {
                const list = [];

                for (let minutes = 0; minutes < 24 * 60; minutes += step) {
                    list.push(`${pad(Math.floor(minutes / 60))}:${pad(minutes % 60)}`);
                }

                return list;
            },

            setTime(clock) {
                const key = this.timeFocus === 'start' ? 'start_time' : 'end_time';
                this.draft = { ...this.draft, [key]: clock };

                if (key === 'start_time' && toMinutes(this.draft.end_time) <= toMinutes(clock)) {
                    this.draft.end_time = this.slots()[Math.min(this.slots().indexOf(clock) + 4, this.slots().length - 1)];
                }

                if (inline) {
                    this.apply();
                }
            },

            slotHint(clock) {
                const minutes = toMinutes(clock) - toMinutes(this.draft.start_time);

                return this.timeFocus === 'end' && minutes > 0 ? this.formatDuration(minutes) : '';
            },

            formatDuration(minutes) {
                return `${Math.floor(minutes / 60) ? Math.floor(minutes / 60) + ' h ' : ''}${minutes % 60 ? (minutes % 60) + ' min' : ''}`.trim() || '0 min';
            },

            duration() {
                return this.formatDuration(Math.max(0, toMinutes(this.draft.end_time) - toMinutes(this.draft.start_time)));
            },

            dayCount() {
                if (! this.draft.start || ! this.draft.end) {
                    return 0;
                }

                return Math.round((fromIso(this.draft.end) - fromIso(this.draft.start)) / 86400000) + 1;
            },

            short(iso) {
                return iso ? new Intl.DateTimeFormat(locale, { day: 'numeric', month: 'short' }).format(fromIso(iso)).replace('.', '') : '…';
            },

            label() {
                if (! this.value.start) {
                    return '';
                }

                const year = (this.value.end ?? this.value.start).slice(0, 4);
                const times = time && this.value.start_time ? ` · ${this.value.start_time}–${this.value.end_time}` : '';

                return `${this.short(this.value.start)} – ${this.short(this.value.end)} ${year}${times}`;
            },

            dayLabel(iso) {
                return new Intl.DateTimeFormat(locale, { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }).format(fromIso(iso));
            },

            toggle() {
                this.open ? this.cancel() : this.show();
            },

            show() {
                this.draft = { ...this.value };
                this.month = (this.value.start ?? this.today).slice(0, 7);
                this.open = true;
                this.$nextTick(() => this.$root.querySelector('[data-time-selected]')?.scrollIntoView({ block: 'center' }));
            },

            cancel() {
                this.draft = { ...this.value };

                if (! inline) {
                    this.open = false;
                }
            },

            apply() {
                if (! this.draft.start || ! this.draft.end) {
                    return;
                }

                this.value = { ...this.draft };
                this.$root.querySelectorAll('[data-range-raw]').forEach((input) => {
                    input.value = this.value[input.dataset.rangeRaw] ?? '';
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                });

                if (! inline) {
                    this.open = false;
                    this.$refs.trigger?.focus();
                }
            },
        };
    });

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
     * Código de verificação: uma casa por caractere, colar distribui, Backspace volta.
     * value é x-modelable; com submit chama o método do Livewire ao completar.
     */
    Alpine.data('uiOtp', ({ length = 6, numeric = true, value = '', submit = null, resend = null, cooldown = 60 } = {}) => ({
        value: (value ?? '').toString().slice(0, length),
        wait: resend ? cooldown : 0,
        timer: null,

        init() {
            if (resend) {
                this.tick();
            }
        },

        get digits() {
            return (this.value ?? '').toString().split('');
        },

        inputs() {
            return [...this.$root.querySelectorAll('input')];
        },

        clean(text) {
            const pattern = numeric ? /\D/g : /[^0-9a-z]/gi;

            return (text ?? '').toString().replace(pattern, '').toUpperCase();
        },

        filled() {
            return this.digits.length;
        },

        remaining() {
            return length - this.digits.length;
        },

        focusAt(index) {
            this.inputs()[Math.max(0, Math.min(length - 1, index))]?.focus();
        },

        write(index, characters) {
            const digits = this.digits.slice(0, index);
            const next = (digits.join('') + characters + this.digits.slice(index + characters.length).join('')).slice(0, length);

            this.value = next;
            this.$nextTick(() => {
                this.inputs().forEach((input, position) => (input.value = this.digits[position] ?? ''));
                this.focusAt(Math.min(index + characters.length, length - 1));
                this.complete();
            });
        },

        typed(event, index) {
            const characters = this.clean(event.target.value);

            if (characters === '') {
                event.target.value = this.digits[index] ?? '';

                return;
            }

            this.write(index, characters.slice(-1 * Math.min(characters.length, length - index)));
        },

        paste(event) {
            const characters = this.clean(event.clipboardData.getData('text')).slice(0, length);

            if (characters !== '') {
                this.write(0, characters);
            }
        },

        back(event, index) {
            event.preventDefault();

            if (this.digits[index] !== undefined) {
                this.value = this.digits.filter((_, position) => position !== index).join('');
                this.$nextTick(() => this.focusAt(index));
            } else {
                this.value = this.digits.slice(0, Math.max(0, index - 1)).join('');
                this.$nextTick(() => this.focusAt(index - 1));
            }
        },

        complete() {
            if (this.digits.length !== length) {
                return;
            }

            this.$dispatch('otp-complete', { value: this.value });

            if (submit && this.$wire) {
                this.$wire.call(submit, this.value);
            }
        },

        tick() {
            clearInterval(this.timer);
            this.timer = setInterval(() => {
                this.wait = Math.max(0, this.wait - 1);

                if (this.wait === 0) {
                    clearInterval(this.timer);
                }
            }, 1000);
        },

        clock() {
            return `${Math.floor(this.wait / 60)}:${String(this.wait % 60).padStart(2, '0')}`;
        },

        sendAgain() {
            if (this.wait > 0) {
                return;
            }

            this.$dispatch('otp-resend');

            if (resend && this.$wire) {
                this.$wire.call(resend);
            }

            this.wait = cooldown;
            this.tick();
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
    /**
     * Hover card: abre depois do delay no mouse ou na hora no foco; dá tempo de levar o mouse até o cartão.
     */
    Alpine.data('uiHoverCard', ({ delay = 400 } = {}) => ({
        open: false,
        timer: null,

        init() {
            const trigger = this.$el.querySelector('a, button, [tabindex]');

            if (trigger) {
                trigger.setAttribute('aria-describedby', [trigger.getAttribute('aria-describedby'), this.$id('ui-hover-card')].filter(Boolean).join(' '));
            }
        },

        schedule() {
            clearTimeout(this.timer);
            this.timer = setTimeout(() => (this.open = true), delay);
        },

        show() {
            clearTimeout(this.timer);
            this.open = true;
        },

        leave() {
            clearTimeout(this.timer);
            this.timer = setTimeout(() => (this.open = false), 150);
        },

        hide() {
            clearTimeout(this.timer);
            this.open = false;
        },
    }));

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
