<?php /* app/views/templates/spinner.php */ ?>
<style>
  /* ===== Global overlay spinner ===== */
  .tw-spinner {
    position: fixed; inset: 0; z-index: 1060;            /* above modals backdrop */
    display: none; align-items: center; justify-content: center;
    background: rgba(17,24,39,.35); backdrop-filter: blur(1px);
  }
  .tw-spinner.show { display: flex; }

  .tw-spinner-box {
    background:#fff; border-radius:.75rem; padding:1rem 1.25rem;
    box-shadow:0 10px 24px rgba(0,0,0,.15); display:flex; gap:.75rem; align-items:center;
    min-width: 200px;
  }
  .tw-spinner-ring {
    width: 26px; height: 26px; border-radius: 9999px;
    border: 3px solid #e5e7eb; border-top-color:#3b82f6; animation: tw-spin 0.9s linear infinite;
  }
  .tw-spinner-text { font-weight:600; color:#111827; font-size:.95rem; }
  @keyframes tw-spin { to { transform: rotate(360deg) } }
</style>

<div id="twSpinner" class="tw-spinner" role="status" aria-live="polite" aria-busy="false">
  <div class="tw-spinner-box">
    <div class="tw-spinner-ring" aria-hidden="true"></div>
    <div id="twSpinnerText" class="tw-spinner-text">Loading…</div>
  </div>
</div>

<script>
  // Small API, safe for concurrent calls via ref counting
  (function () {
    let count = 0;
    const el = document.getElementById('twSpinner');
    const txt = document.getElementById('twSpinnerText');

    function show(message) {
      count++;
      if (message) txt.textContent = message;
      el.classList.add('show');
      el.setAttribute('aria-busy', 'true');
    }

    function hide() {
      count = Math.max(0, count - 1);
      if (count === 0) {
        el.classList.remove('show');
        el.setAttribute('aria-busy', 'false');
        txt.textContent = 'Loading…';
      }
    }

    async function wrap(promiseOrFn, message) {
      show(message);
      try {
        if (typeof promiseOrFn === 'function') {
          return await promiseOrFn();
        } else {
          return await promiseOrFn;
        }
      } finally {
        hide();
      }
    }

    window.Spinner = { show, hide, wrap };
  })();
</script>
