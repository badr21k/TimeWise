// Time Clock scripts (extracted and enhanced)
(function(){
  function $(id){ return document.getElementById(id); }
  function has(id){ return !!$(id); }

  // Live clock display
  function updateLiveClock(){
    const now = new Date();
    const timeEl = $('tcTime');
    const dateEl = $('tcDate');
    if (timeEl) timeEl.textContent = now.toLocaleTimeString([], {hour:'2-digit', minute:'2-digit', second:'2-digit'});
    if (dateEl) dateEl.textContent = now.toLocaleDateString(undefined, {weekday:'short', year:'numeric', month:'short', day:'numeric'});
  }

  function pad2(n){ return String(n).padStart(2,'0'); }
  function fmtDuration(ms){
    if (ms < 0) ms = 0;
    const h = Math.floor(ms/3600000);
    const m = Math.floor((ms%3600000)/60000);
    const s = Math.floor((ms%60000)/1000);
    return `${pad2(h)}:${pad2(m)}:${pad2(s)}`;
  }

  document.addEventListener('DOMContentLoaded', function(){
    // Start live clock interval if present
    updateLiveClock();
    setInterval(updateLiveClock, 1000);

    // Bootstrap helpers
    const toast   = $('toaster') ? new bootstrap.Toast($('toaster')) : null;
    const toastMsgEl = $('toastMsg');
    const satisfactionModal = $('satisfactionModal') ? new bootstrap.Modal($('satisfactionModal')) : null;

    let lastStatus = null;
    let durationTimer = null;

    // Robust date parsing (copied)
    function parseDateSmart(input) {
      if (input == null) return null;
      if (typeof input === 'number') return new Date(input);
      if (typeof input !== 'string') return new Date(input);
      const s = input.trim();
      if (/Z$|[+\-]\d{2}:\d{2}$/.test(s)) return new Date(s);
      if (/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}$/.test(s)) return new Date(s + 'Z');
      if (/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/.test(s)) return new Date(s.replace(' ', 'T') + 'Z');
      return new Date(s);
    }
    const fmtDT = (v) => { const d = parseDateSmart(v); return d ? d.toLocaleString() : '—'; };
    const fmtHM = (v) => { const d = parseDateSmart(v); return d ? d.toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'}) : '—'; };
    const fmtDate = (v) => { const d = parseDateSmart(v); return d ? d.toLocaleDateString(undefined, {weekday:'short', year:'numeric', month:'short', day:'numeric'}) : '—'; };

    function tzPayload(){
      return {
        client_time_epoch_ms: Date.now(),
        client_time_iso: new Date().toISOString(),
        tz_offset_min: -new Date().getTimezoneOffset(),
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

    async function api(action, body=null){
      const url = `/timeclock/api?a=${encodeURIComponent(action)}`;
      const opts = { method: body ? 'POST' : 'GET' };
      if (body) {
        const form = new FormData();
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
          todayEl.innerHTML = `${title}${fmtDate(s.today.start || new Date())} · ${start} – ${end}`;
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

    function coerceEntriesToday(s) { return s.entries_today || s.today_entries || s.todayShifts || s.shifts_today || []; }
    function msBetween(a,b){ const da=parseDateSmart(a), db=parseDateSmart(b); if(!da) return 0; if(!db) return Date.now()-da.getTime(); return Math.max(0, db.getTime()-da.getTime()); }
    function hoursDisplay(ms, d=2){ return (ms/3600000).toFixed(d); }

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
            <td>${scheduled ? '<span class="badge bg-info-subtle text-info-emphasis">Scheduled</span>' : '<span class="badge bg-danger-subtle text-danger-emphasis">Unscheduled</span>'}</td>
            <td><strong>${hoursDisplay(Math.max(0, workMs))}</strong></td>
          </tr>
        `);
      }
      tbody.innerHTML = rows.join('');
      if (totalEl) totalEl.textContent = hoursDisplay(totalMs);
    }

    function updateButtons(s){
      const clockedIn = !!s.clocked_in;
      const onBreak   = !!s.on_break;
      if (has('btnClockIn'))   $('btnClockIn').disabled   = clockedIn;
      if (has('btnBreakStart'))$('btnBreakStart').disabled= !clockedIn || onBreak;
      if (has('btnBreakEnd'))  $('btnBreakEnd').disabled  = !clockedIn || !onBreak;
      if (has('btnClockOut'))  $('btnClockOut').disabled  = !clockedIn || onBreak;
    }

    function refreshDurationTicker(){
      const durEl = $('tcDuration');
      const breakBadge = $('tcBreakBadge');
      if (!durEl) return;
      if (!lastStatus || !lastStatus.entry || !lastStatus.entry.clock_in){
        durEl.style.display = 'none';
        if (breakBadge) breakBadge.style.display = 'none';
        if (durationTimer) { clearInterval(durationTimer); durationTimer = null; }
        return;
      }
      const cin = lastStatus.entry.clock_in;
      const onBreak = !!lastStatus.on_break;
      durEl.style.display = '';
      if (breakBadge) breakBadge.style.display = onBreak ? '' : 'none';

      function tick(){
        const now = Date.now();
        const start = (new Date(cin)).getTime();
        const ms = now - start - ((lastStatus.entry.total_break_minutes || 0) * 60000);
        durEl.textContent = `⏱ ${fmtDuration(ms)}`;
      }
      tick();
      if (durationTimer) clearInterval(durationTimer);
      durationTimer = setInterval(tick, 1000);
    }

    async function refreshStatus(){
      try{
        const s = await api('status');
        lastStatus = s;
        if (!s.clocked_in){
          setPill('Clocked Out', 'info');
          let msg = '<small>You are currently clocked out.</small>';
          if (s.entry && s.entry.satisfaction) { msg += ` <small>&middot; Last satisfaction: ${s.entry.satisfaction}/5</small>`; }
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
        refreshDurationTicker();
      } catch(e){
        setPill('Status Error', 'danger');
        setDetail(`<small class="text-danger">${e.message || 'Failed to load status'}</small>`);
      }
    }

    async function actionWithSpinner(btnId, labelHtml, doWork, busyText){
      const btn = $(btnId);
      const original = btn ? btn.innerHTML : '';
      if (btn) { btn.disabled = true; btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>${labelHtml}`; }
      busy(true, busyText);
      try{
        await doWork();
        await refreshStatus();
        showToast('Done.');
      } catch(e){
        setPill('Action Failed', 'danger');
        setDetail(`<small class="text-danger">${e.message}</small>`);
      } finally {
        if (btn) { btn.disabled = false; btn.innerHTML = original; }
        busy(false);
      }
    }

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
        setDetail(`<small class=\"text-danger\">${e.message}</small>`);
      } finally {
        busy(false);
        isProcessing = false;
      }
    }

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

    // Init
    setPill('Loading…', 'info');
    setDetail('<small>Fetching your current shift state…</small>');
    refreshStatus();
  });
})();
