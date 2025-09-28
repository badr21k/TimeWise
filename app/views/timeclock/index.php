<?php require 'app/views/templates/header.php'; ?>

<style>
:root{
  --bg:#f6f8fb;--card:#ffffff;--ink:#1f2a37;--muted:#6b7280;--border:#e5e7eb;--ring:#c7d2fe;
  --accent:#2563eb;--accent-2:#7c3aed;--success:#16a34a;--danger:#dc2626;--warning:#d97706;
  --pill:#eef2ff;--shadow:0 8px 24px rgba(17,24,39,.08)
}
*{box-sizing:border-box}
body{background:var(--bg);color:var(--ink);margin:0}
.container{max-width:960px;padding:16px}

.tw-card{background:var(--card);border:1px solid var(--border);border-radius:16px;box-shadow:var(--shadow);overflow:hidden}
.tw-card__header{padding:16px;text-align:center;background:
  linear-gradient(90deg, rgba(37,99,235,.08), rgba(124,58,237,.06))}
.tw-card__title{margin:0;font-size:1.25rem;letter-spacing:.2px}
.tw-card__body{padding:20px}

.tw-status{display:inline-flex;gap:8px;align-items:center;padding:6px 12px;border-radius:999px;background:#eef2f7;color:#1f2937;font-size:.85rem;border:1px solid var(--border)}
.tw-status--in{background:#ecfdf5;color:#166534;border-color:#bbf7d0}
.tw-status--break{background:#fff7ed;color:#9a3412;border-color:#fed7aa}
.tw-status--out{background:#f3f4f6;color:#374151;border-color:#e5e7eb}

.tc-clock{text-align:center}
.tc-time{font-variant-numeric:tabular-nums;font-size:2.6rem;line-height:1;margin-bottom:6px}
.tc-date{color:var(--muted);font-size:.98rem}
.tc-badges{margin-top:10px;display:flex;gap:8px;justify-content:center;flex-wrap:wrap}
.tc-badge{display:inline-flex;gap:6px;align-items:center;background:#f3f4f6;padding:4px 10px;border-radius:999px;color:#374151;font-size:.8rem;border:1px solid var(--border)}
.tc-badge--break{background:#fff7ed;color:#9a3412;border-color:#fed7aa}
.tc-badge--timer{background:#eef2ff;color:#3730a3;border-color:#c7d2fe}

.tw-mini{background:#fafafa;border:1px solid var(--border);border-radius:12px;padding:14px}
.tw-badge{font-size:.75rem;padding:4px 10px;border-radius:999px;border:1px solid var(--border)}
.tw-badge--unscheduled{background:#eef2ff;color:#1e3a8a;border-color:#c7d2fe}
.tw-badge--upcoming{background:#f5f3ff;color:#5b21b6;border-color:#ddd6fe}
.small{color:var(--muted)}

.alert-note{background:#eff6ff;color:#1e40af;border:1px solid #bfdbfe;border-radius:12px;padding:12px}

.tw-actions{gap:10px}
.btn{border-radius:12px;border:1px solid transparent;min-width:148px;font-weight:600}
.btn-success{background:var(--success);border-color:var(--success);color:#fff}
.btn-danger{background:var(--danger);border-color:var(--danger);color:#fff}
.btn-secondary{background:#e5e7eb;border-color:#e5e7eb;color:#111827}
.btn-outline-secondary{background:#fff;border-color:#d1d5db;color:#374151}
.btn:hover{filter:brightness(1.03);transform:translateY(-1px)}
.btn:focus{outline:2px solid var(--ring);outline-offset:2px}
.btn:disabled{opacity:.6;transform:none}

.table{width:100%;border-collapse:separate;border-spacing:0 10px}
.table thead th{color:#374151;font-weight:700;font-size:.85rem;border-bottom:1px solid var(--border);padding:10px;background:#f9fafb}
.table tbody tr{background:#fff;border:1px solid var(--border)}
.table tbody td{padding:12px 10px}
.table-responsive{border-radius:12px;overflow:hidden;border:1px solid var(--border);background:#fff}

.position-fixed .toast{background:#111827;color:#fff;border:0;border-radius:12px}

#busyOverlay{position:fixed;inset:0;background:rgba(15,23,42,.2);backdrop-filter:saturate(180%) blur(4px);display:none;place-items:center;z-index:2000}
#busyOverlay .spinner-border{width:2.2rem;height:2.2rem}
#busyOverlay small{display:block;color:#111827;margin-top:10px}

@media (max-width:768px){
  .btn{min-width:47%}
  .tw-card__title{font-size:1.1rem}
  .tc-time{font-size:2.3rem}
}
</style>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-8">

      <div class="tw-card">
        <div class="tw-card__header">
          <h1 class="tw-card__title">Time Clock</h1>
          <div class="mt-2">
            <span id="statusPill" class="tw-status"><i class="fas fa-rotate fa-spin"></i> Loading status…</span>
          </div>
        </div>

        <div class="tw-card__body">
          <div class="tc-clock mb-3">
            <div id="tcTime" class="tc-time">--:--:--</div>
            <div id="tcDate" class="tc-date">Loading date…</div>
            <div class="tc-badges">
              <span id="tcDuration" class="tc-badge tc-badge--timer" style="display:none"><i class="fas fa-stopwatch"></i> 00:00:00</span>
              <span id="tcBreakBadge" class="tc-badge tc-badge--break" style="display:none"><i class="fas fa-coffee"></i> On Break</span>
            </div>
          </div>

          <div id="statusDetail" class="small mb-3 text-center">Fetching your current shift state…</div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <div class="tw-mini h-100">
                <div class="d-flex align-items-center justify-content-between mb-1">
                  <strong>Today’s Shift</strong>
                  <span id="todayBadge" class="tw-badge tw-badge--unscheduled" style="display:none">Unscheduled</span>
                </div>
                <div id="todayShift" class="small">Loading…</div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="tw-mini h-100">
                <div class="d-flex align-items-center justify-content-between mb-1">
                  <strong>Next Shift</strong>
                  <span class="tw-badge tw-badge--upcoming">Upcoming</span>
                </div>
                <div id="nextShift" class="small">—</div>
              </div>
            </div>
          </div>

          <div id="clockHint" class="alert-note mb-3" style="display:none">
            <i class="fas fa-info-circle me-1"></i> No scheduled shift today. You can still <strong>Clock In</strong>—it will be saved as <strong>Unscheduled</strong>.
          </div>

          <div class="d-flex flex-wrap tw-actions justify-content-center">
            <button id="btnClockIn" class="btn btn-success"><i class="fas fa-play me-1"></i> Clock In</button>
            <button id="btnBreakStart" class="btn btn-outline-secondary"><i class="fas fa-coffee me-1"></i> Start Break</button>
            <button id="btnBreakEnd" class="btn btn-secondary"><i class="fas fa-mug-hot me-1"></i> End Break</button>
            <button id="btnClockOut" class="btn btn-danger"><i class="fas fa-stop me-1"></i> Clock Out</button>
          </div>
        </div>
      </div>

      <div class="tw-card mt-3">
        <div class="tw-card__header"><h2 class="tw-card__title" style="font-size:1rem">Today’s Shifts</h2></div>
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
                <tr><td colspan="5" class="small">No entries.</td></tr>
              </tbody>
            </table>
          </div>
          <div class="d-flex justify-content-end">
            <strong>Total Today:&nbsp;<span id="todayTotalHours">0.00</span>&nbsp;hrs</strong>
          </div>
        </div>
      </div>

      <div class="position-fixed bottom-0 end-0 p-3" style="z-index:1080">
        <div id="toaster" class="toast align-items-center border-0" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="d-flex">
            <div id="toastMsg" class="toast-body">Action completed.</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<div id="busyOverlay">
  <div class="text-center">
    <div class="spinner-border text-primary" role="status" aria-label="Working…"></div>
    <small id="busyText">Working…</small>
  </div>
</div>

<div class="modal fade" id="satisfactionModal" tabindex="-1" aria-labelledby="satisfactionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background:#ffffff;color:#111827;border:1px solid var(--border)">
      <div class="modal-header" style="border-bottom:1px solid var(--border)">
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
        <small class="text-secondary d-block mt-2">You can skip this.</small>
      </div>
      <div class="modal-footer" style="border-top:1px solid var(--border)">
        <button type="button" class="btn btn-link text-secondary" id="btnSkip" data-bs-dismiss="modal">Skip</button>
        <button type="button" class="btn btn-primary" id="btnSubmit"><i class="fas fa-paper-plane me-1"></i> Submit</button>
      </div>
    </div>
  </div>
</div>

<script>
(function(){
  const $ = (s)=>document.querySelector(s);
  const tf = new Intl.DateTimeFormat(undefined,{hour:'2-digit',minute:'2-digit',second:'2-digit'});
  const df = new Intl.DateTimeFormat(undefined,{weekday:'long',year:'numeric',month:'long',day:'numeric'});
  let state = { status:'loading', clockIn:null, breakSeconds:0, today:[], todaySchedule:null, nextSchedule:null };

  function tick(){
    const now = new Date();
    $('#tcTime').textContent = tf.format(now);
    $('#tcDate').textContent = df.format(now);
    if(state.status==='in' && state.clockIn){
      const base = Math.max(0, ((now - new Date(state.clockIn))/1000) - (state.breakSeconds||0));
      $('#tcDuration').style.display = 'inline-flex';
      $('#tcDuration').innerHTML = `<i class="fas fa-stopwatch"></i> ${secToHms(Math.floor(base))}`;
    } else { $('#tcDuration').style.display = 'none'; }
  }

  function secToHms(s){
    const h=String(Math.floor(s/3600)).padStart(2,'0');
    const m=String(Math.floor((s%3600)/60)).padStart(2,'0');
    const sec=String(Math.floor(s%60)).padStart(2,'0');
    return `${h}:${m}:${sec}`;
  }

  function setBusy(on,msg){ $('#busyOverlay').style.display = on?'grid':'none'; if(msg) $('#busyText').textContent = msg; }
  function toast(msg){ $('#toastMsg').textContent = msg||'Done'; new bootstrap.Toast($('#toaster'),{delay:2200}).show(); }
  function pill(cls,html){ const el=$('#statusPill'); el.className='tw-status '+cls; el.innerHTML=html; }

  function updateUI(){
    if(state.status==='in'){ pill('tw-status--in','<i class="fas fa-check-circle"></i> Clocked In'); $('#statusDetail').textContent='Active shift in progress.'; }
    else if(state.status==='break'){ pill('tw-status--break','<i class="fas fa-mug-hot"></i> On Break'); $('#statusDetail').textContent='Break running.'; }
    else { pill('tw-status--out','<i class="far fa-circle"></i> Clocked Out'); $('#statusDetail').textContent='You are currently clocked out.'; }
    $('#tcBreakBadge').style.display = state.status==='break' ? 'inline-flex' : 'none';

    $('#todayShift').textContent = state.todaySchedule ? `${state.todaySchedule.start} — ${state.todaySchedule.end}` : 'No scheduled shift';
    $('#todayBadge').style.display = state.todaySchedule ? 'none' : 'inline-block';
    $('#clockHint').style.display = state.todaySchedule ? 'none' : 'block';

    $('#nextShift').textContent = state.nextSchedule ? `${state.nextSchedule.date} • ${state.nextSchedule.start} — ${state.nextSchedule.end}` : 'No upcoming shifts';

    const btnIn=$('#btnClockIn'), btnOut=$('#btnClockOut'), bS=$('#btnBreakStart'), bE=$('#btnBreakEnd');
    if(state.status==='out'){ btnIn.disabled=false; btnOut.disabled=true; bS.disabled=true; bE.disabled=true; }
    if(state.status==='in'){ btnIn.disabled=true; btnOut.disabled=false; bS.disabled=false; bE.disabled=true; }
    if(state.status==='break'){ btnIn.disabled=true; btnOut.disabled=false; bS.disabled=true; bE.disabled=false; }

    renderToday();
  }

  function renderToday(){
    const tb=$('#todayList'); tb.innerHTML='';
    if(!state.today.length){ tb.innerHTML='<tr><td colspan="5" class="small">No entries.</td></tr>'; $('#todayTotalHours').textContent='0.00'; return; }
    let totalSec=0;
    state.today.forEach(r=>{
      const tr=document.createElement('tr');
      tr.innerHTML=`<td>${r.in||'—'}</td><td>${r.out||'—'}</td><td>${r.break||'—'}</td><td>${r.type||'Scheduled'}</td><td>${r.hours||'0.00'}</td>`;
      tb.appendChild(tr);
      if(r.seconds) totalSec+=r.seconds;
    });
    $('#todayTotalHours').textContent=(totalSec/3600).toFixed(2);
  }

  async function api(fn, body={}){
    const url='/timeclock/api'+(fn==='state'?`?fn=state&_=${Date.now()}`:'');
    const opt= fn==='state'
      ? {method:'GET',headers:{'Accept':'application/json'}}
      : {method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({fn,...body})};
    const res=await fetch(url,opt); if(!res.ok) throw new Error('Request failed'); return res.json();
  }

  async function loadState(){
    try{
      const r=await api('state');
      state.status=r.status||'out';
      state.clockIn=r.clockInAt||null;
      state.breakSeconds=r.breakSeconds||0;
      state.today=Array.isArray(r.today)?r.today:[];
      state.todaySchedule=r.todaySchedule||null;
      state.nextSchedule=r.nextSchedule||null;
      updateUI();
    }catch{ pill('','<i class="fas fa-triangle-exclamation"></i> Error'); $('#statusDetail').textContent='Could not load state.'; }
  }

  async function doAction(kind,label){
    setBusy(true,label+'…');
    try{
      const r=await api(kind);
      if(r && r.ok!==false){ await loadState(); toast(r.message||'Done'); }
      else{ toast((r&&r.error)||'Action failed'); }
    }catch{ toast('Network error'); }
    finally{ setBusy(false); }
  }

  $('#btnClockIn').addEventListener('click', ()=>doAction('clock_in','Clocking in'));
  $('#btnClockOut').addEventListener('click', async ()=>{ await doAction('clock_out','Clocking out'); try{ new bootstrap.Modal('#satisfactionModal').show(); }catch(_){} });
  $('#btnBreakStart').addEventListener('click', ()=>doAction('break_start','Starting break'));
  $('#btnBreakEnd').addEventListener('click', ()=>doAction('break_end','Ending break'));
  $('#btnSubmit').addEventListener('click', async ()=>{ const v=$('#satisfactionSelect').value; if(v){ setBusy(true,'Submitting…'); try{ await api('satisfaction',{score:v}); toast('Thanks!'); }catch{} setBusy(false); } try{ bootstrap.Modal.getInstance($('#satisfactionModal')).hide(); }catch{} });

  setInterval(tick,1000); tick(); loadState();
})();
</script>

<?php require 'app/views/templates/footer.php'; ?>
