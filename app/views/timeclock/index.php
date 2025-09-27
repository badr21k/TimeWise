<?php require 'app/views/templates/header.php'; ?>

<style>
  .tw-card { border:0; border-radius:16px; box-shadow:0 10px 30px rgba(16,24,40,.08); overflow:hidden; }
  .tw-card__header {
    background: radial-gradient(1200px 400px at 10% -10%, #e0f2fe 0%, transparent 40%),
                linear-gradient(180deg, #ffffff, #fafafa);
    padding: 22px 24px; border-bottom:1px solid #eef2f7;
  }
  .tw-card__title { display:flex; align-items:center; gap:.6rem; margin:0; font-weight:700; }
  .tw-dot { width:10px;height:10px;border-radius:999px;background:#22c55e; box-shadow:0 0 0 4px rgba(34,197,94,.15); }
  .tw-card__body { padding: 20px 24px 24px; }

  .tw-status { display:inline-flex; align-items:center; gap:.5rem; padding:.5rem .75rem; border-radius:999px; font-weight:600; font-size:.9rem; }
  .tw-status--ok{ background:#ecfdf5;color:#047857;border:1px solid #a7f3d0; }
  .tw-status--info{ background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe; }
  .tw-status--bad{ background:#fef2f2;color:#b91c1c;border:1px solid #fecaca; }

  .tw-mini { border:1px solid #eef2f7; border-radius:14px; padding:14px; }
  .tw-badge { font-size:.75rem; font-weight:700; padding:.25rem .5rem; border-radius:999px; }
  .tw-badge--scheduled { background:#e0f2fe; color:#075985; }
  .tw-badge--unscheduled { background:#fee2e2; color:#991b1b; }
  .tw-badge--upcoming { background:#f1f5f9; color:#334155; }

  .tw-actions { gap:.5rem; }
  .tw-actions .btn { border-radius:12px; padding:.6rem .85rem; font-weight:600; }
  .btn .spinner-border { width:1rem; height:1rem; }

  #busyOverlay { position:fixed; inset:0; background:rgba(255,255,255,.65); backdrop-filter:blur(2px);
    display:none; align-items:center; justify-content:center; z-index:1055; }
  #busyOverlay.active { display:flex; }
  .tw-spinner-wrap { display:flex; flex-direction:column; align-items:center; gap:.75rem; }
  .tw-spinner-wrap small { color:#475569; }

  @keyframes fadeUp { from{opacity:0; transform:translateY(8px)} to{opacity:1; transform:translateY(0)} }
  .animate-in { animation:fadeUp .28s ease-out both; }
</style>

<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card tw-card animate-in">
        <div class="tw-card__header">
          <h1 class="tw-card__title h4 mb-0">
            <span class="tw-dot" aria-hidden="true"></span>
            Time Clock
          </h1>
          <div class="mt-2">
            <span id="statusPill" class="tw-status tw-status--info">
              <i class="fas fa-rotate fa-spin me-1"></i> Loading status…
            </span>
          </div>
        </div>

        <div class="tw-card__body">
          <div id="statusDetail" class="text-secondary mb-3" style="min-height:1.25rem;">
            <small>Fetching your current shift state…</small>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <div class="tw-mini h-100">
                <div class="d-flex align-items-center justify-content-between mb-1">
                  <strong>Today’s Shift</strong>
                  <span id="todayBadge" class="tw-badge tw-badge--unscheduled" style="display:none;">Unscheduled</span>
                </div>
                <div id="todayShift" class="small text-secondary">Loading…</div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="tw-mini h-100">
                <div class="d-flex align-items-center justify-content-between mb-1">
                  <strong>Next Scheduled</strong>
                  <span class="tw-badge tw-badge--upcoming">Upcoming</span>
                </div>
                <div id="nextShift" class="small text-secondary">—</div>
              </div>
            </div>
          </div>

          <div class="alert alert-light border mb-3" id="clockHint" style="display:none;">
            <i class="fas fa-info-circle me-1"></i>
            No scheduled shift today. You can still <strong>Clock In</strong> — it will be saved as an <strong>Unscheduled</strong> shift.
          </div>

          <div class="d-flex flex-wrap tw-actions">
            <button class="btn btn-success" id="btnClockIn">
              <i class="fas fa-play me-1"></i> Clock In
            </button>
            <button class="btn btn-outline-secondary" id="btnBreakStart">
              <i class="fas fa-coffee me-1"></i> Start Break
            </button>
            <button class="btn btn-secondary" id="btnBreakEnd">
              <i class="fas fa-mug-hot me-1"></i> End Break
            </button>

            <div class="ms-auto"></div>

            <button class="btn btn-danger" id="btnClockOut">
              <i class="fas fa-stop me-1"></i> Clock Out
            </button>
          </div>
        </div>
      </div>

      <!-- Today's Shifts List -->
      <div class="card tw-card mt-3">
        <div class="tw-card__header">
          <h2 class="h6 mb-0">Today’s Shifts</h2>
        </div>
        <div class="tw-card__body">
          <div class="table-responsive">
            <table class="table align-middle mb-2">
              <thead>
                <tr>
                  <th>Clock In</th>
                  <th>Clock Out</th>
                  <th>Break</th>
                  <th>Type</th>
                  <th>Hours</th>
                </tr>
              </thead>
              <tbody id="todayList">
                <tr><td colspan="5" class="text-secondary">No entries.</td></tr>
              </tbody>
            </table>
          </div>
          <div class="d-flex justify-content-end">
            <strong>Total Today:&nbsp;<span id="todayTotalHours">0.00</span>&nbsp;hrs</strong>
          </div>
        </div>
      </div>

      <!-- Toast -->
      <div class="position-fixed bottom-0 end-0 p-3" style="z-index:1080">
        <div id="toaster" class="toast align-items-center text-bg-dark border-0" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="d-flex">
            <div class="toast-body" id="toastMsg">Action completed.</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Busy Overlay -->
<div id="busyOverlay" aria-hidden="true">
  <div class="tw-spinner-wrap">
    <div class="spinner-border" role="status" aria-label="Working…"></div>
    <small id="busyText">Working…</small>
  </div>
</div>

<!-- Satisfaction Modal -->
<div class="modal fade" id="satisfactionModal" tabindex="-1" aria-labelledby="satisfactionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="satisfactionModalLabel">Satisfaction Survey</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="mb-2">How satisfied are you with your day?</p>
        <select id="satisfactionSelect" class="form-select">
          <option value="" selected>— Select —</option>
          <option value="1">1 — Very Dissatisfied</option>
          <option value="2">2 — Dissatisfied</option>
          <option value="3">3 — Neutral</option>
          <option value="4">4 — Satisfied</option>
          <option value="5">5 — Very Satisfied</option>
        </select>
        <small class="text-secondary d-block mt-2">You can skip this if you prefer.</small>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link text-secondary" id="btnSkip">Skip</button>
        <button type="button" class="btn btn-primary" id="btnSubmit">
          <span class="me-1"><i class="fas fa-paper-plane"></i></span> Submit
        </button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  /* ---------- Safe DOM ---------- */
  const $ = (id) => document.getElementById(id);
  const has = (id) => !!$(id);

  /* ---------- Bootstrap ---------- */
  const toast   = $('toaster') ? new bootstrap.Toast($('toaster')) : null;
  const toastMsgEl = $('toastMsg');
  const satisfactionModal = $('satisfactionModal') ? new bootstrap.Modal($('satisfactionModal')) : null;

  let lastStatus = null;

  /* ---------- Robust date parsing ---------- */
  function parseDateSmart(input) {
    if (input == null) return null;
    if (typeof input === 'number') return new Date(input); // epoch ms
    if (typeof input !== 'string') return new Date(input);

    const s = input.trim();

    // Has explicit zone or Z
    if (/Z$|[+\-]\d{2}:\d{2}$/.test(s)) return new Date(s);

    // ISO without zone
    if (/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}$/.test(s)) return new Date(s + 'Z');

    // "YYYY-MM-DD HH:mm:ss"
    if (/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/.test(s)) return new Date(s.replace(' ', 'T') + 'Z');

    return new Date(s);
  }

  const fmtDT = (v) => { const d = parseDateSmart(v); return d ? d.toLocaleString() : '—'; };
  const fmtHM = (v) => { const d = parseDateSmart(v); return d ? d.toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'}) : '—'; };
  const fmtDate = (v) => { const d = parseDateSmart(v); return d ? d.toLocaleDateString(undefined, {weekday:'short', year:'numeric', month:'short', day:'numeric'}) : '—'; };

  /* ---------- Client TZ payload ---------- */
  function tzPayload() {
    return {
      client_time_epoch_ms: Date.now(),
      client_time_iso: new Date().toISOString(),             // UTC instant
      tz_offset_min: -new Date().getTimezoneOffset(),        // minutes east of UTC
      tz: Intl.DateTimeFormat().resolvedOptions().timeZone || 'UTC'
    };
  }

  function showToast(msg){ if (toast && toastMsgEl){ toastMsgEl.textContent = msg; toast.show(); } }

  function setPill(text, type='info') {
    const pill = $('statusPill'); if (!pill) return;
    pill.textContent = '';
    pill.className = 'tw-status ' + (type==='success'?'tw-status--ok':type==='danger'?'tw-status--bad':'tw-status--info');
    const icon = document.createElement('i');
    icon.className = type==='success' ? 'fas fa-check-circle me-1'
               : type==='danger'     ? 'fas fa-triangle-exclamation me-1'
                                     : 'fas fa-circle-info me-1';
    pill.appendChild(icon);
    pill.appendChild(document.createTextNode(text));
  }

  function setDetail(html){ const el = $('statusDetail'); if (el) el.innerHTML = html; }

  function lockControls(locked=true){
    ['btnClockIn','btnBreakStart','btnBreakEnd','btnClockOut','btnSubmit','btnSkip'].forEach(id=>{
      const el = $(id); if (el) el.disabled = locked;
    });
  }

  function busy(on=true, text='Working…'){
    const ov = $('busyOverlay');
    const txt = $('busyText');
    if (txt) txt.textContent = text;
    if (ov) {
      ov.classList.toggle('active', on);
      ov.setAttribute('aria-hidden', on ? 'false' : 'true');
    }
    lockControls(on);
  }

  /* ---------- API (auto-attaches local time info) ---------- */
  async function api(action, body=null){
    const url = `/timeclock/api?a=${encodeURIComponent(action)}`;
    const opts = { method: body ? 'POST' : 'GET' };
    if (body) {
      const form = new FormData();
      // Merge tz payload first so body keys can override if needed
      const tzp = tzPayload();
      for (const k in tzp) form.append(k, tzp[k]);
      for (const k in body) form.append(k, body[k]);
      opts.body = form;
    }
    const res = await fetch(url, opts);
    if (!res.ok) throw new Error(`Request failed (${res.status})`);
    const data = await res.json();
    if (data && data.error) throw new Error(data.error);
    return data;
  }

  /* ---------- Schedule panels ---------- */
  function fillSchedulePanels(s){
    const scheduledToday = (s.scheduled_today === true) || (!!s.today && s.today.scheduled === true);

    const todayBadge = $('todayBadge');
    if (todayBadge) {
      const showBadge = scheduledToday || !!(s.entry && s.entry.clock_in);
      todayBadge.style.display = showBadge ? 'inline-block' : 'none';
      if (showBadge) {
        todayBadge.className = 'tw-badge ' + (scheduledToday ? 'tw-badge--scheduled' : 'tw-badge--unscheduled');
        todayBadge.textContent = scheduledToday ? 'Scheduled' : 'Unscheduled';
      }
    }

    const todayEl = $('todayShift');
    if (todayEl) {
      if (scheduledToday && s.today){
        const title = s.today.title ? `<strong>${s.today.title}</strong><br>` : '';
        const start = s.today.start ? fmtHM(s.today.start) : '—';
        const end   = s.today.end   ? fmtHM(s.today.end)   : '—';
        todayEl.innerHTML = `${title}${fmtDate(s.today.start || new Date())} &middot; ${start} – ${end}`;
      } else if (s.entry && s.entry.clock_in) {
        todayEl.innerHTML = `Clocked in at <strong>${fmtDT(s.entry.clock_in)}</strong> (unscheduled)`;
      } else {
        todayEl.innerHTML = `No scheduled shift today.`;
      }
    }

    const ns = s.next_scheduled || s.nextSchedule || null;
    const nextEl = $('nextShift');
    if (nextEl) {
      if (ns && (ns.start || ns.date)){
        const date = ns.start || ns.date;
        const start = ns.start ? fmtHM(ns.start) : (ns.time ? ns.time : '');
        const end   = ns.end ? ` – ${fmtHM(ns.end)}` : '';
        const t = ns.title ? `<strong>${ns.title}</strong><br>` : '';
        nextEl.innerHTML = `${t}${fmtDate(date)} ${start}${end}`;
      } else {
        nextEl.textContent = '—';
      }
    }

    const hint = $('clockHint');
    if (hint) hint.style.display = !scheduledToday ? 'block' : 'none';
  }

  /* ---------- Today list rendering ---------- */
  function coerceEntriesToday(s) {
    // Accept common shapes
    return s.entries_today || s.today_entries || s.todayShifts || s.shifts_today || [];
  }

  function msBetween(a, b){
    const da = parseDateSmart(a), db = parseDateSmart(b);
    if (!da) return 0;
    if (!db) return Date.now() - da.getTime(); // running shift
    return Math.max(0, db.getTime() - da.getTime());
  }

  function hoursDisplay(ms, digits=2){ return (ms / 3600000).toFixed(digits); }

  function renderTodayList(s){
    const tbody = $('todayList'); if (!tbody) return;
    const totalEl = $('todayTotalHours');
    const rows = [];
    let totalMs = 0;

    const arr = coerceEntriesToday(s);
    if (!Array.isArray(arr) || arr.length === 0){
      tbody.innerHTML = `<tr><td colspan="5" class="text-secondary">No entries.</td></tr>`;
      if (totalEl) totalEl.textContent = '0.00';
      return;
    }

    for (const e of arr){
      const cin  = e.clock_in_epoch_ms ?? e.clock_in_ms ?? e.clock_in;
      const cout = e.clock_out_epoch_ms ?? e.clock_out_ms ?? e.clock_out ?? null;
      const brMin = Number(e.total_break_minutes ?? e.break_minutes ?? 0);
      const scheduled = (typeof e.scheduled === 'boolean') ? e.scheduled : (e.type === 'scheduled');

      const workMs = msBetween(cin, cout) - (brMin * 60 * 1000);
      totalMs += Math.max(0, workMs);

      rows.push(`
        <tr>
          <td>${fmtDT(cin)}</td>
          <td>${cout ? fmtDT(cout) : '<span class="text-warning">— in progress —</span>'}</td>
          <td>${brMin} min</td>
          <td>${scheduled ? '<span class="badge bg-info-subtle text-info-emphasis">Scheduled</span>'
                          : '<span class="badge bg-danger-subtle text-danger-emphasis">Unscheduled</span>'}</td>
          <td><strong>${hoursDisplay(Math.max(0, workMs))}</strong></td>
        </tr>
      `);
    }

    tbody.innerHTML = rows.join('');
    if (totalEl) totalEl.textContent = hoursDisplay(totalMs);
  }

  /* ---------- Buttons state ---------- */
  function updateButtons(s){
    const clockedIn = !!s.clocked_in;
    const onBreak   = !!s.on_break;
    if (has('btnClockIn'))   $('btnClockIn').disabled   = clockedIn;
    if (has('btnBreakStart'))$('btnBreakStart').disabled= !clockedIn || onBreak;
    if (has('btnBreakEnd'))  $('btnBreakEnd').disabled  = !clockedIn || !onBreak;
    if (has('btnClockOut'))  $('btnClockOut').disabled  = !clockedIn || onBreak;
  }

  /* ---------- Refresh ---------- */
  async function refreshStatus(){
    try{
      const s = await api('status'); // backend may also consider tz params if desired
      lastStatus = s;

      if (!s.clocked_in){
        setPill('Clocked Out', 'info');
        let msg = '<small>You are currently clocked out.</small>';
        if (s.entry && s.entry.satisfaction) {
          msg += ` <small>&middot; Last satisfaction: ${s.entry.satisfaction}/5</small>`;
        }
        setDetail(msg);
      } else {
        const scheduled = (s.entry && typeof s.entry.scheduled === 'boolean') ? s.entry.scheduled : null;
        setPill('Clocked In', 'success');
        let msg = `<small><strong>In:</strong> ${fmtDT(s.entry.clock_in)} &nbsp;•&nbsp; `;
        msg += s.on_break ? '<span class="text-warning"><i class="fas fa-coffee me-1"></i>On break</span>' : 'Not on break';
        if (s.entry.total_break_minutes) msg += ` &nbsp;•&nbsp; <strong>Break:</strong> ${s.entry.total_break_minutes} min`;
        if (scheduled !== null) msg += ` &nbsp;•&nbsp; <strong>${scheduled ? 'Scheduled' : 'Unscheduled'}</strong>`;
        setDetail(msg + '</small>');
      }

      fillSchedulePanels(s);
      renderTodayList(s);
      updateButtons(s);
    } catch(e){
      setPill('Status Error', 'danger');
      setDetail(`<small class="text-danger">${e.message || 'Failed to load status'}</small>`);
    }
  }

  /* ---------- Action wrapper ---------- */
  async function actionWithSpinner(btnId, labelHtml, doWork, busyText){
    const btn = $(btnId);
    const original = btn ? btn.innerHTML : '';
    if (btn) { btn.disabled = true; btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>${labelHtml}`; }
    busy(true, busyText);
    try{
      await doWork();
      await refreshStatus();   // refresh list + status
      showToast('Done.');
    } catch(e){
      setPill('Action Failed', 'danger');
      setDetail(`<small class="text-danger">${e.message}</small>`);
    } finally {
      if (btn) { btn.disabled = false; btn.innerHTML = original; }
      busy(false);
    }
  }

  /* ---------- Actions ---------- */
  function doClockIn(){
    const unsched = !(lastStatus && (lastStatus.scheduled_today === true || (lastStatus.today && lastStatus.today.scheduled)));
    const payload = { ...(unsched ? { unscheduled: 1 } : {}) };
    return actionWithSpinner('btnClockIn','Clocking In…', () => api('clock.in', payload), 'Clocking in…');
  }
  function doBreakStart(){ return actionWithSpinner('btnBreakStart','Starting…', () => api('break.start', {}), 'Starting break…'); }
  function doBreakEnd(){   return actionWithSpinner('btnBreakEnd','Ending…',   () => api('break.end',   {}), 'Ending break…'); }

  let isProcessing = false;
  async function doClockOutInternal(sat){
    if (isProcessing) return;
    isProcessing = true;
    busy(true, 'Clocking out…');
    try{
      const payload = {};
      if (sat !== null && sat !== '') payload.satisfaction = sat;
      await api('clock.out', payload);
      if (satisfactionModal) satisfactionModal.hide();
      await refreshStatus();
      showToast('Clocked out. Have a good one!');
    } catch(e){
      setPill('Clock Out Failed', 'danger');
      setDetail(`<small class="text-danger">${e.message}</small>`);
    } finally {
      busy(false);
      isProcessing = false;
    }
  }

  /* ---------- Events ---------- */
  if (has('btnClockIn'))   $('btnClockIn').addEventListener('click', doClockIn);
  if (has('btnBreakStart'))$('btnBreakStart').addEventListener('click', doBreakStart);
  if (has('btnBreakEnd'))  $('btnBreakEnd').addEventListener('click', doBreakEnd);
  if (has('btnClockOut'))  $('btnClockOut').addEventListener('click', () => {
    const sel = $('satisfactionSelect'); if (sel) sel.value = '';
    if (satisfactionModal) satisfactionModal.show();
  });
  if (has('btnSkip'))      $('btnSkip').addEventListener('click', () => doClockOutInternal(null));
  if (has('btnSubmit'))    $('btnSubmit').addEventListener('click', () => {
    const sel = $('satisfactionSelect');
    const sat = sel ? sel.value : '';
    if (sat === '') { alert('Please select a satisfaction level or skip.'); return; }
    doClockOutInternal(sat);
  });

  /* ---------- Init ---------- */
  setPill('Loading…', 'info');
  setDetail('<small>Fetching your current shift state…</small>');
  refreshStatus();
});
</script>

<?php require 'app/views/templates/footer.php'; ?>
