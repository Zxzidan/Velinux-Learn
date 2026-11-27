const closeAllDropdowns = (exceptId = null) => {
    document.querySelectorAll('[data-dropdown-panel]').forEach((panel) => {
        if (panel.id !== exceptId) {
            panel.classList.add('hidden');
        }
    });

    document.querySelectorAll('[data-dropdown-toggle]').forEach((trigger) => {
        const targetId = trigger.getAttribute('data-dropdown-toggle');
        if (targetId !== exceptId) {
            trigger.setAttribute('aria-expanded', 'false');
        }
    });
};

const setupDrawers = () => {
    const backdrop = document.getElementById('drawer-backdrop');

    const toggleDrawer = (drawerId, forceOpen = null) => {
        const drawer = document.getElementById(drawerId);
        if (!drawer) {
            return;
        }

        const isHidden = drawer.classList.contains('-translate-x-full');
        const shouldOpen = forceOpen !== null ? forceOpen : isHidden;

        drawer.classList.toggle('-translate-x-full', !shouldOpen);
        drawer.classList.toggle('translate-x-0', shouldOpen);

        if (backdrop) {
            backdrop.classList.toggle('hidden', !shouldOpen);
        }

        document.querySelectorAll(`[data-drawer-toggle="${drawerId}"]`).forEach((btn) => {
            btn.setAttribute('aria-expanded', shouldOpen ? 'true' : 'false');
            const icons = btn.querySelectorAll('svg[data-drawer-icon]');
            if (icons.length === 2) {
                icons[0].classList.toggle('hidden', shouldOpen);
                icons[1].classList.toggle('hidden', !shouldOpen);
            }
        });
    };

    document.querySelectorAll('[data-drawer-toggle]').forEach((button) => {
        button.addEventListener('click', (event) => {
            event.stopPropagation();
            const targetId = button.getAttribute('data-drawer-toggle');
            toggleDrawer(targetId);

            if (button.hasAttribute('data-focus-search')) {
                const searchInput = document.getElementById('sidebar-search');
                if (searchInput) {
                    setTimeout(() => searchInput.focus(), 120);
                }
            }
        });
    });

    if (backdrop) {
        backdrop.addEventListener('click', () => {
            toggleDrawer('drawer-navigation', false);
        });
    }
};

const setupDropdowns = () => {
    document.querySelectorAll('[data-dropdown-toggle]').forEach((button) => {
        const targetId = button.getAttribute('data-dropdown-toggle');
        const panel = document.getElementById(targetId);
        if (!panel) {
            return;
        }

        panel.setAttribute('data-dropdown-panel', 'true');

        button.addEventListener('click', (event) => {
            event.stopPropagation();
            const willOpen = panel.classList.contains('hidden');
            closeAllDropdowns(willOpen ? targetId : null);
            panel.classList.toggle('hidden', !willOpen);
            button.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
        });
    });

    document.addEventListener('click', (event) => {
        const target = event.target;
        if (!(target instanceof Element)) {
            return;
        }

        if (!target.closest('[data-dropdown-panel]') && !target.closest('[data-dropdown-toggle]')) {
            closeAllDropdowns();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeAllDropdowns();
        }
    });
};

const setupCollapses = () => {
    document.querySelectorAll('[data-collapse-toggle]').forEach((button) => {
        const targetId = button.getAttribute('data-collapse-toggle');
        const target = document.getElementById(targetId);
        if (!target) {
            return;
        }

        button.addEventListener('click', () => {
            const isHidden = target.classList.contains('hidden');
            target.classList.toggle('hidden', !isHidden);
            button.setAttribute('aria-expanded', isHidden ? 'true' : 'false');

            const chevron = button.querySelector('[data-collapse-chevron]');
            if (chevron) {
                chevron.classList.toggle('rotate-180', isHidden);
            }
        });
    });
};

const setupTooltips = () => {
    document.querySelectorAll('[data-tooltip-target]').forEach((trigger) => {
        const tooltipId = trigger.getAttribute('data-tooltip-target');
        const tooltip = document.getElementById(tooltipId);
        if (!tooltip) {
            return;
        }

        const show = () => {
            tooltip.classList.remove('invisible', 'opacity-0');
            tooltip.classList.add('visible', 'opacity-100');
        };

        const hide = () => {
            tooltip.classList.add('invisible', 'opacity-0');
            tooltip.classList.remove('visible', 'opacity-100');
        };

        trigger.addEventListener('mouseenter', show);
        trigger.addEventListener('focus', show);
        trigger.addEventListener('mouseleave', hide);
        trigger.addEventListener('blur', hide);
    });
};

const setupThemeToggle = () => {
    const toggleButtons = document.querySelectorAll('[data-theme-toggle]');
    const syncUi = () => {
        const isDark = document.documentElement.classList.contains('dark');
        document.querySelectorAll('[data-theme-icon="dark"]').forEach((el) => el.classList.toggle('hidden', isDark));
        document.querySelectorAll('[data-theme-icon="light"]').forEach((el) => el.classList.toggle('hidden', !isDark));
        document.querySelectorAll('[data-theme-label]').forEach((el) => {
            el.textContent = isDark ? 'Mode Terang' : 'Mode Gelap';
        });
        toggleButtons.forEach((btn) => {
            btn.setAttribute('aria-pressed', isDark ? 'true' : 'false');
        });
    };

    syncUi();

    toggleButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('velinux-theme', isDark ? 'dark' : 'light');
            syncUi();
        });
    });
};

const setupCopyButtons = () => {
    document.querySelectorAll('[data-copy-target]').forEach((button) => {
        button.addEventListener('click', async () => {
            const targetId = button.getAttribute('data-copy-target');
            const target = document.getElementById(targetId);
            if (!target) {
                return;
            }

            try {
                await navigator.clipboard.writeText(target.textContent?.trim() ?? '');
                const label = button.querySelector('[data-copy-label]');
                if (label) {
                    const original = label.textContent;
                    label.textContent = 'Copied!';
                    setTimeout(() => {
                        label.textContent = original;
                    }, 1500);
                }
            } catch {
                // Clipboard unavailable in insecure context
            }
        });
    });
};

document.addEventListener('DOMContentLoaded', () => {
    setupDrawers();
    setupDropdowns();
    setupCollapses();
    setupTooltips();
    setupThemeToggle();
    setupCopyButtons();
});
