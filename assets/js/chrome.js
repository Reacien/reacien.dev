(() => {
    const root = document.documentElement;

    const THEME_KEY = 'rc-theme';
    const prefersDark =
        window.matchMedia &&
        window.matchMedia('(prefers-color-scheme: dark)').matches;

     try {
        const savedTheme = localStorage.getItem(THEME_KEY);
        if (savedTheme === 'light' || savedTheme === 'dark') {
            root.dataset.theme = savedTheme;
        } else if (prefersDark) {
            root.dataset.theme = 'dark';
        } else {
            root.dataset.theme = 'light';
        }
    } catch {
        root.dataset.theme = prefersDark ? 'dark' : 'light';
    }

    const updateThemeIcon = () => {
        const current = root.dataset.theme || 'light';
        document.querySelectorAll('[data-theme-icon]').forEach(el => {
            el.textContent = current === 'dark' ? '☾' : '☀';
        });
    };

    updateThemeIcon();

    window.rcToggleTheme = () => {
        const current = root.dataset.theme === 'dark' ? 'dark' : 'light';
        const next = current === 'dark' ? 'light' : 'dark';
        root.dataset.theme = next;
        try {
            localStorage.setItem(THEME_KEY, next);
        } catch {}
        updateThemeIcon();
    };

    const ua = navigator.userAgent || '';
    const plat = navigator.platform || '';
    const isMac = /Mac/.test(plat) || /Mac OS X/.test(ua);

    const isCoarse = window.matchMedia && window.matchMedia('(pointer: coarse)').matches;
    const hasTouch = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
    const isTouch = isCoarse && hasTouch;

    root.dataset.os = isMac ? 'mac' : 'other';
    root.dataset.input = isTouch ? 'touch' : 'keyboard';

    document.querySelectorAll('.cmdk-mod').forEach(el => {
        el.textContent = isMac ? '⌘' : 'ctrl';
    });

    const dataEl = document.getElementById('cmdk-data');
    let cmdkItems = [];
    if (dataEl) {
        try {
            cmdkItems = JSON.parse(dataEl.textContent || '[]');
        } catch {}
    }

    const inline = document.querySelector('[data-cmdk-inline]');
    if (inline && root.dataset.input === 'keyboard' && cmdkItems.length) {
        const container = inline.closest('.cmdk-inline');
        let panel = container.querySelector('.cmdk-inline-panel');
        if (!panel) {
            panel = document.createElement('div');
            panel.className = 'cmdk-inline-panel';
            panel.hidden = true;
            panel.innerHTML = '<div class="cmdk-inline-list"></div>';
            container.appendChild(panel);
        }
        const listEl = panel.querySelector('.cmdk-inline-list');

        let closeTimer = null;

        const openInlinePanel = () => {
            clearTimeout(closeTimer);
            panel.hidden = false;
            requestAnimationFrame(() => {
                panel.classList.remove('is-closing');
                panel.classList.add('is-open');
            });
        };

        const closeInlinePanel = () => {
            clearTimeout(closeTimer);
            panel.classList.remove('is-open');
            panel.classList.add('is-closing');
            closeTimer = setTimeout(() => {
                panel.hidden = true;
                panel.classList.remove('is-closing');
            }, 180);
        };

        let filtered = [];
        let active = 0;

        const renderInline = () => {
            if (!inline.value.trim() || filtered.length === 0) {
                closeInlinePanel();
                return;
            }

            const html = filtered
                .map(
                    (it, idx) => `
                <div class="cmdk-inline-item${idx === active ? ' active' : ''}" data-idx="${idx}">
                  <span class="k">${escapeHtml(it.k)}</span>
                  <span class="label">${escapeHtml(it.label)}</span>
                </div>
              `,
                )
                .join('');

            listEl.innerHTML = html;
            openInlinePanel();
        };

        const filterInline = q => {
            const ql = q.trim().toLowerCase();
            if (!ql) {
                filtered = [];
                renderInline();
                return;
            }
            filtered = cmdkItems.filter(i =>
                (i.label + ' ' + (i.k || '') + ' ' + (i.hint || ''))
                    .toLowerCase()
                    .includes(ql),
            );
            active = 0;
            renderInline();
        };

        const runInline = it => {
            if (!it) return;
            if (it.js) {
                try {
                    new Function(it.js)();
                } catch (e) {
                    console.warn(e);
                }
            }
            if (it.href) {
                if (it.external) window.open(it.href, '_blank', 'noopener');
                else window.location.href = it.href;
            }
            inline.value = '';
            closeInlinePanel();
        };

        inline.addEventListener('input', e => filterInline(e.target.value));

        inline.addEventListener('keydown', e => {
            if (!filtered.length) return;
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                active = Math.min(filtered.length - 1, active + 1);
                renderInline();
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                active = Math.max(0, active - 1);
                renderInline();
            } else if (e.key === 'Enter') {
                e.preventDefault();
                runInline(filtered[active]);
            } else if (e.key === 'Escape') {
                closeInlinePanel();
                inline.blur();
            }
        });

        listEl.addEventListener('mousedown', e => {
            const row = e.target.closest('.cmdk-inline-item');
            if (!row) return;
            const idx = +row.dataset.idx;
            runInline(filtered[idx]);
        });

        document.addEventListener('click', e => {
            if (!container.contains(e.target)) {
                closeInlinePanel();
            }
        });

        function escapeHtml(s) {
            return String(s).replace(/[&<>"']/g, c => {
                return {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;',
                }[c];
            });
        }
    }

    if (isTouch) {
        document.querySelectorAll('.cmdk-hint-touch-hide').forEach(el => {
            el.hidden = true;
        });
    }

    const ACCENT_KEY = 'rc-accent';
    const VALID_ACCENTS = ['green','red','blue','magenta','mono'];
    try {
        const saved = localStorage.getItem(ACCENT_KEY);
        if (saved && VALID_ACCENTS.includes(saved)) root.dataset.accent = saved;
    } catch {}

    window.rcSetAccent = (a) => {
        if (!VALID_ACCENTS.includes(a)) return;
        root.dataset.accent = a;
        try { localStorage.setItem(ACCENT_KEY, a); } catch {}
    };

    window.rcCycleAccent = () => {
        const cur = root.dataset.accent || 'green';
        const next = VALID_ACCENTS[(VALID_ACCENTS.indexOf(cur) +1) % VALID_ACCENTS.length];
        window.rcSetAccent(next);
    };

    const openCmdk = () => window.dispatchEvent(new CustomEvent('rc:cmdk:open'));
    document.querySelectorAll('[data-cmdk-trigger]').forEach(btn => {
        btn.addEventListener('click', openCmdk);
    });

    window.addEventListener('keydown', (e) => {
        if (root.dataset.input === 'touch') return;

        const target = e.target;
        const tag = target.tagName;
        if (tag === 'INPUT' || tag === 'TEXTAREA' || target.isContentEditable) {
            return;
        }

        const key = e.key.toLowerCase();

        if ((e.metaKey || e.ctrlKey) && e.shiftKey && key === 'k') {
            e.preventDefault();
            openCmdk();
            return;
        }

        if (!e.metaKey && !e.ctrlKey && e.shiftKey && key === 't') {
            e.preventDefault();
            if (window.rcToggleTheme) window.rcToggleTheme();
        }
    });

    document.querySelectorAll('[data-theme-trigger]').forEach(btn => {
        btn.addEventListener('click', () => {
            if (window.rcToggleTheme) window.rcToggleTheme();
        });
    });
})();