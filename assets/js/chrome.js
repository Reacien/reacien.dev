(() => {
    const root = document.documentElement;

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
        if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
            e.preventDefault();
            openCmdk();
        }
    });

    document.querySelectorAll('[data-theme-trigger]').forEach(btn => {
        btn.addEventListener('click', () => {
            const fab = document.getElementById('theme-toggle');
            if (fab) fab.click();
        });
    });
})();