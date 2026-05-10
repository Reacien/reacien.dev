(() => {
  const KEY = 'rc-boot-seen';
  const overlay = document.getElementById('boot-overlay');
  if (!overlay) return;

  let seen = false;
  try { seen = sessionStorage.getItem(KEY) === '1'; } catch {}
  if (seen) {
    overlay.remove();
    return;
  }

  const lines = document.getElementById('boot-lines');
  overlay.hidden = false;

  const SCRIPT = [
    { d: 60,  t: '> booting reacien.dev …' },
    { d: 30,  t: '\n> loading paper texture …' },
    { d: 30,  t: '\n> ' },
    { d: 0,   t: 'welcome, stranger. make yourself at home.', accent: true },
  ];

  let i = 0, ch = 0, finished = false;
  const tick = () => {
    if (finished) return;
    const step = SCRIPT[i];
    if (!step) { finishSoon(); return; }
    if (ch < step.t.length) {
      const next = step.t[ch++];
      const span = step.accent
        ? lines.querySelector('.accent') || (() => {
            const s = document.createElement('span');
            s.className = 'accent ok';
            lines.appendChild(s);
            return s;
          })()
        : null;
      if (span) span.textContent += next;
      else lines.appendChild(document.createTextNode(next));
      setTimeout(tick, step.d);
    } else {
      i++; ch = 0;
      setTimeout(tick, 200);
    }
  };

  const finishSoon = () => {
    finished = true;
    setTimeout(() => {
      overlay.classList.add('is-fading');
      setTimeout(() => {
        overlay.remove();
        try { sessionStorage.setItem(KEY, '1'); } catch {}
      }, 550);
    }, 700);
  };

  const skip = () => {
    finished = true;
    overlay.remove();
    try { sessionStorage.setItem(KEY, '1'); } catch {}
  };
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') skip();
  }, { once: true });

  setTimeout(tick, 200);
})();