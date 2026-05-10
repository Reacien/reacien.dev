(() => {
    const dialog = document.getElementById('cmdk');
    const dataEl = document.getElementById('cmdk-data');
    if (!dialog || !dataEl) return;

    const items = JSON.parse(dataEl.textContent || '[]');
    const $q = document.getElementById('cmdk-q');
    const $list = document.getElementById('cmdk-list');
    const $empty = document.getElementById('cmdk-empty');
    const $count = document.getElementById('cmdk-count');
    const $total = document.getElementById('cmdk-total');

    let active = 0;
    let filtered = items;

    if (!$q || !$list || !$empty || !$count || !$total) return;

    $total.textContent = items.length + ' commands';
    
    const groupLabels = {
        navigate: 'navigate',
        insights: 'latest insights',
        projects: 'projects',
        actions: 'actions',
        external: 'external',
    };

    const render = () => {
        $count.textContent = filtered.length + ' results';
        if (filtered.length === 0) {
            $list.innerHTML = '';
            $empty.hidden = false;
            return;
        }
        $empty.hidden = true;

        const groups = {};
        filtered.forEach((it, idx) => {
            (groups[it.group] ||= []).push({ ...it, idx });
        });

        const html = Object.entries(groups)
            .map(
                ([g, arr]) => `
                <div class="cmdk-group-label">${groupLabels[g] || g}</div>
                ${arr
                    .map(
                        it => `
                    <div class="cmdk-item${it.idx === active ? ' active' : ''}" data-idx="${it.idx}" role="option">
                        <span class="k">${escapeHtml(it.k)}</span>
                        <span>${escapeHtml(it.label)}</span>
                        <span class="arrow">${escapeHtml(it.hint || '↵')}</span>
                    </div>
                    `,
                )
                .join('')}
            `,
            )
        .join('');
        $list.innerHTML = html;
    };

    const filter = q => {
        const ql = q.trim().toLowerCase();
        filtered = ql
            ? items.filter(i =>
                (i.label + ' ' + (i.k || '') + ' ' + (i.hint || ''))
                    .toLowerCase()
                    .includes(ql),
                )
            : items;
        active = 0;
        render();
    };

    const run = it => {
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
        close();
    };

    const open = () => {
        dialog.hidden = false;
        $q.value = '';
        filter('');
        setTimeout(() => $q.focus(), 30);
    };

    const close = () => {
        dialog.hidden = true;
    };

    $q.addEventListener('input', e => filter(e.target.value));

    $q.addEventListener('keydown', e => {
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            active = Math.min(filtered.length - 1, active + 1);
            render();
            ensureVisible();
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            active = Math.max(0, active - 1);
            render();
            ensureVisible();
        } else if (e.key === 'Enter') {
            e.preventDefault();
            run(filtered[active]);
        } else if (e.key === 'Escape') {
            e.preventDefault();
            close();
        }
    });

    $list.addEventListener('mousemove', e => {
        const row = e.target.closest('.cmdk-item');
        if (!row) return;
        const idx = +row.dataset.idx;
        if (idx !== active) {
            active = idx;
            render();
        }
    });

    $list.addEventListener('click', e => {
        const row = e.target.closest('.cmdk-item');
        if (!row) return;
        run(filtered[+row.dataset.idx]);
    });

    dialog.addEventListener('click', e => {
        if (e.target === dialog) close();
    });

    window.addEventListener('rc:cmdk:open', open);

    function ensureVisible() {
        const el = $list.querySelector('.cmdk-item.active');
        el?.scrollIntoView({ block: 'nearest' });
    }

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
})();