(() => {
  const KEY = "rc-boot-seen";

  function startBootSequence({ ignoreSeen = false } = {}) {
    const overlay = document.getElementById("boot-overlay");
    const lines = document.getElementById("boot-lines");
    const skipBtn = document.getElementById("boot-skip");
    if (!overlay || !lines) return;

    let seen = false;
    try {
      seen = sessionStorage.getItem(KEY) === "1";
    } catch {}

    if (seen && !ignoreSeen) {
      overlay.hidden = true;
      return;
    }

    overlay.hidden = false;
    overlay.classList.remove("is-fading");
    lines.textContent = "";

    const SCRIPT = [
      { d: 28, t: "> booting reacien.dev …" },
      { d: 18, t: "\n> checking local theme cache … ok" },
      { d: 18, t: "\n> restoring accent profile … ok" },
      { d: 18, t: "\n> loading paper texture … ok" },
      { d: 18, t: "\n> mounting chrome … ok" },
      { d: 18, t: "\n> indexing projects … ok" },
      { d: 18, t: "\n> indexing insights … ok" },
      { d: 18, t: "\n> wiring command palette … ok" },
      { d: 18, t: "\n> syncing terminal surface … ok" },
      { d: 18, t: "\n> status: all systems nominal" },
      { d: 18, t: "\n> " },
      { d: 0, t: "welcome, stranger. make yourself at home.", accent: true },
    ];

    let i = 0,
      ch = 0,
      finished = false;

    const prefersReducedMotion =
      window.matchMedia &&
      window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    if (prefersReducedMotion) {
      for (const step of SCRIPT) {
        if (step.accent) {
          const s = document.createElement("span");
          s.className = "accent ok";
          s.textContent = step.t;
          lines.appendChild(s);
        } else {
          lines.appendChild(document.createTextNode(step.t));
        }
      }
      overlay.hidden = true;
      try {
        sessionStorage.setItem(KEY, "1");
      } catch {}
      return;
    }

    const tick = () => {
      if (finished) return;
      const step = SCRIPT[i];
      if (!step) {
        finishSoon();
        return;
      }
      if (ch < step.t.length) {
        const next = step.t[ch++];
        const span = step.accent
          ? lines.querySelector(".accent") ||
            (() => {
              const s = document.createElement("span");
              s.className = "accent ok";
              lines.appendChild(s);
              return s;
            })()
          : null;
        if (span) span.textContent += next;
        else lines.appendChild(document.createTextNode(next));
        setTimeout(tick, step.d);
      } else {
        i++;
        ch = 0;
        setTimeout(tick, 200);
      }
    };

    const finishSoon = () => {
      finished = true;
      setTimeout(() => {
        overlay.classList.add("is-fading");
        setTimeout(() => {
          overlay.hidden = true;
          overlay.classList.remove("is-fading");
          try {
            sessionStorage.setItem(KEY, "1");
          } catch {}
        }, 550);
      }, 700);
    };

    const skip = () => {
      finished = true;
      overlay.hidden = true;
      overlay.classList.remove("is-fading");
      try {
        sessionStorage.setItem(KEY, "1");
      } catch {}
    };

    if (skipBtn) {
      skipBtn.addEventListener("click", skip);
    }

    document.addEventListener("keydown", function escHandler(e) {
      if (e.key === "Escape") {
        skip();
        document.removeEventListener("keydown", escHandler);
      }
    });

    setTimeout(tick, 200);
  }

  startBootSequence();

  window.rcReplayBoot = () => {
    startBootSequence({ ignoreSeen: true });
  };
})();
