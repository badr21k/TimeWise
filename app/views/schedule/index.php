<?php
// app/views/schedule/index.php
require 'app/views/templates/header.php';
require 'app/views/templates/spinner.php';
?>
<style>
/* ==== Homebase-like Schedule (kept & refined) ==== */
.schedule-container { background:#f8fafc; min-height:100vh; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif; }
.schedule-header    { background:#fff; border-bottom:1px solid #e5e7eb; padding:1rem 0; }
.schedule-title     { font-size:1.5rem; font-weight:600; color:#111827; margin:0; }
.schedule-subtitle  { color:#6b7280; font-size:.875rem; margin:0; }

.week-controls { display:flex; align-items:center; gap:1rem; }
.week-navigation { display:flex; align-items:center; gap:.5rem; }
.week-nav-btn { background:#f9fafb; border:1px solid #d1d5db; border-radius:.375rem; padding:.5rem; color:#374151; cursor:pointer; transition:.2s; }
.week-nav-btn:hover { background:#e5e7eb; }
.week-display { font-weight:500; color:#111827; min-width:260px; text-align:center; }

.publish-section { display:flex; align-items:center; gap:.75rem; }
.status-indicator { padding:.375rem .75rem; border-radius:9999px; font-size:.75rem; font-weight:500; }
.status-draft { background:#fef3c7; color:#92400e; }
.status-published { background:#d1fae5; color:#065f46; }
.btn-primary { background:#3b82f6; color:#fff; border:none; border-radius:.375rem; padding:.5rem 1rem; font-weight:500; font-size:.875rem; cursor:pointer; transition:.2s; }
.btn-primary:hover { background:#2563eb; }

.schedule-grid { background:#fff; border-radius:.5rem; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,.1); margin:1rem; }
.grid-header { display:grid; grid-template-columns:240px repeat(7,1fr); background:#f9fafb; border-bottom:1px solid #e5e7eb; }
.grid-header-cell { padding:1rem .75rem; font-weight:600; color:#374151; font-size:.875rem; text-align:center; border-right:1px solid #e5e7eb; }
.grid-header-cell:first-child { text-align:left; background:#f3f4f6; }

.grid-body { max-height:70vh; overflow-y:auto; }
.grid-row { display:grid; grid-template-columns:240px repeat(7,1fr); border-bottom:1px solid #e5e7eb; min-height:120px; }

.employee-cell { background:#f9fafb; padding:1rem .75rem; border-right:1px solid #e5e7eb; display:flex; flex-direction:column; gap:.25rem; }
.employee-name { font-weight:600; color:#111827; font-size:.9rem; }
.employee-role { color:#6b7280; font-size:.8rem; }
.employee-hours { color:#6b7280; font-size:.75rem; margin-top:auto; }

.day-cell { padding:.5rem; border-right:1px solid #e5e7eb; position:relative; background:#fff; }
.shift-block { background:#3b82f6; color:#fff; border-radius:.375rem; padding:.5rem; margin-bottom:.25rem; font-size:.75rem; position:relative; cursor:pointer; transition:.2s; }
.shift-block:hover { background:#2563eb; transform:translateY(-1px); box-shadow:0 2px 4px rgba(0,0,0,.1); }
.shift-time { font-weight:700; margin-bottom:.25rem; }
.shift-role { opacity:.95; font-size:.72rem; }
.shift-delete { position:absolute; top:.25rem; right:.25rem; background:rgba(255,255,255,.25); border:none; color:#fff; border-radius:50%; width:20px; height:20px; font-size:.7rem; display:flex; align-items:center; justify-content:center; opacity:0; transition:opacity .2s; cursor:pointer; }
.shift-block:hover .shift-delete { opacity:1; }

.add-shift-area { position:absolute; bottom:.5rem; left:.5rem; right:.5rem; }
.add-shift-btn { width:100%; background:transparent; border:2px dashed #d1d5db; color:#6b7280; border-radius:.375rem; padding:.5rem; font-size:.75rem; cursor:pointer; transition:.2s; display:flex; align-items:center; justify-content:center; gap:.25rem; }
.add-shift-btn:hover { border-color:#3b82f6; color:#3b82f6; background:#eff6ff; }

/* Modal */
.modal-content { border:none; border-radius:.75rem; box-shadow:0 25px 50px -12px rgba(0,0,0,.25); }
.modal-header { border-bottom:1px solid #e5e7eb; padding:1.25rem 1.5rem; }
.modal-body   { padding:1.5rem; }
.modal-footer { border-top:1px solid #e5e7eb; padding:1rem 1.5rem; gap:.75rem; }
.form-group { margin-bottom:1rem; }
.form-label { display:block; font-size:.875rem; font-weight:600; color:#374151; margin-bottom:.5rem; }
.form-control { width:100%; border:1px solid #d1d5db; border-radius:.375rem; padding:.5rem .75rem; font-size:.875rem; transition:border-color .2s; }
.form-control:focus { outline:none; border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,.1); }
.btn-secondary { background:#f9fafb; color:#374151; border:1px solid #d1d5db; border-radius:.375rem; padding:.5rem 1rem; font-weight:500; font-size:.875rem; cursor:pointer; transition:.2s; }
.btn-secondary:hover { background:#e5e7eb; }
.time-input-group { display:flex; gap:.5rem; align-items:center; }
.time-separator { color:#6b7280; font-weight:600; }
.role-select { appearance:none; background-image:url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e"); background-position:right .5rem center; background-repeat:no-repeat; background-size:1.2em 1.2em; padding-right:2.5rem; }
</style>

<div class="schedule-container">
  <div class="schedule-header">
    <div class="container-fluid px-4">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <h1 class="schedule-title">Schedule</h1>
          <p class="schedule-subtitle">Manage your team's work schedule</p>
        </div>
        <div class="week-controls">
          <div class="week-navigation" role="group" aria-label="Week navigation">
            <button class="week-nav-btn" id="prevWeekBtn" title="Previous week" aria-label="Previous week">
              <i class="fas fa-chevron-left"></i>
            </button>
            <div class="week-display" id="weekDisplay" aria-live="polite"></div>
            <button class="week-nav-btn" id="nextWeekBtn" title="Next week" aria-label="Next week">
              <i class="fas fa-chevron-right"></i>
            </button>
          </div>
          <div class="publish-section">
            <span class="status-indicator status-draft" id="statusIndicator">Draft</span>
            <button class="btn-primary" id="publishBtn">Publish (0)</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="schedule-grid">
    <div class="grid-header">
      <div class="grid-header-cell">Team members (0)</div>
      <div class="grid-header-cell" data-day="0">Mon</div>
      <div class="grid-header-cell" data-day="1">Tue</div>
      <div class="grid-header-cell" data-day="2">Wed</div>
      <div class="grid-header-cell" data-day="3">Thu</div>
      <div class="grid-header-cell" data-day="4">Fri</div>
      <div class="grid-header-cell" data-day="5">Sat</div>
      <div class="grid-header-cell" data-day="6">Sun</div>
    </div>
    <div class="grid-body" id="scheduleGridBody"></div>
  </div>
</div>

<!-- Add Shift Modal -->
<div class="modal fade" id="shiftModal" tabindex="-1" aria-labelledby="shiftModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="shiftModalLabel" class="modal-title">Add Shift</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" title="Close"></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label" for="startTime">Time</label>
          <div class="time-input-group">
            <input type="time" id="startTime" class="form-control" value="09:00">
            <span class="time-separator">–</span>
            <input type="time" id="endTime" class="form-control" value="17:00">
          </div>
        </div>
        <div class="form-group">
          <label class="form-label" for="shiftRole">Role</label>
          <select id="shiftRole" class="form-control role-select"></select>
        </div>
        <div class="form-group">
          <label class="form-label">Apply to:</label>
          <div class="d-flex gap-2 flex-wrap mt-2">
            <button type="button" class="btn btn-secondary btn-sm day-selector" data-day="1">Mon</button>
            <button type="button" class="btn btn-secondary btn-sm day-selector" data-day="2">Tue</button>
            <button type="button" class="btn btn-secondary btn-sm day-selector" data-day="3">Wed</button>
            <button type="button" class="btn btn-secondary btn-sm day-selector" data-day="4">Thu</button>
            <button type="button" class="btn btn-secondary btn-sm day-selector" data-day="5">Fri</button>
            <button type="button" class="btn btn-secondary btn-sm day-selector" data-day="6">Sat</button>
            <button type="button" class="btn btn-secondary btn-sm day-selector" data-day="0">Sun</button>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label" for="shiftNotes">Shift Notes:</label>
          <textarea id="shiftNotes" class="form-control" rows="3" placeholder="Notes employees will see."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="saveShiftBtn">Add</button>
      </div>
    </div>
  </div>
</div>

<?php require_once 'app/views/templates/footer.php'; ?>

<script>
// ===== Spinner-aware strict JSON fetch =====
async function fetchJSON(url, options = {}) {
  Spinner.show();
  try {
    const res = await fetch(url, { headers: { 'Content-Type': 'application/json' }, ...options });
    const text = await res.text();
    if (!res.ok) throw new Error(text || `HTTP ${res.status}`);

    // If server didn't set JSON content-type but returned JSON, still parse it.
    let data;
    try { data = JSON.parse(text); }
    catch (e) {
      console.error('[fetchJSON] Invalid JSON:', text.slice(0, 400));
      throw e;
    }
    return data;
  } finally {
    Spinner.hide();
  }
}

// ===== State =====
let employees = [];
let shifts = [];
let currentWeekStart = null;
let isAdmin = false;
let shiftModal;
let currentEmployee = null;
let selectedDays = new Set();

// ===== Dates =====
function mondayOf(dateStr) {
  const d = new Date(dateStr + 'T12:00:00');
  const dow = d.getDay(); // 0..6 (Sun..Sat)
  const offset = (dow === 0) ? 6 : (dow - 1);
  d.setDate(d.getDate() - offset);
  return d.toISOString().slice(0,10);
}
function formatWeekDisplay(mondayStr) {
  const mon = new Date(mondayStr + 'T12:00:00');
  const sun = new Date(mon); sun.setDate(sun.getDate() + 6);
  const m = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
  const same = mon.getMonth() === sun.getMonth();
  const left  = `${mon.toLocaleDateString('en-US',{weekday:'short'})}, ${m[mon.getMonth()]} ${mon.getDate()}`;
  const right = `${sun.toLocaleDateString('en-US',{weekday:'short'})}, ${same ? '' : (m[sun.getMonth()]+' ')}${sun.getDate()}`;
  return `Week of ${left} - ${right}`;
}
function weekDays(mondayStr) {
  const mon = new Date(mondayStr + 'T12:00:00');
  const m = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
  const arr = [];
  for (let i=0;i<7;i++) {
    const d = new Date(mon); d.setDate(d.getDate()+i);
    arr.push({ date: d.toISOString().slice(0,10), display: `${d.toLocaleDateString('en-US',{weekday:'short'})}, ${m[d.getMonth()]} ${d.getDate()}` });
  }
  return arr;
}

// ===== Init =====
document.addEventListener('DOMContentLoaded', async () => {
  shiftModal = new bootstrap.Modal(document.getElementById('shiftModal'));

  const today = new Date().toISOString().slice(0,10);
  currentWeekStart = mondayOf(today);

  document.getElementById('prevWeekBtn').addEventListener('click', () => changeWeek(-7));
  document.getElementById('nextWeekBtn').addEventListener('click', () => changeWeek(7));
  document.getElementById('publishBtn').addEventListener('click', togglePublish);
  document.getElementById('saveShiftBtn').addEventListener('click', saveShift);

  // Safe day-selector handlers
  document.querySelectorAll('.day-selector').forEach((btn) => {
    btn.addEventListener('click', (e) => {
      const b = e.currentTarget;
      const day = b.dataset.day;
      if (selectedDays.has(day)) {
        selectedDays.delete(day);
        b.classList.remove('btn-primary'); b.classList.add('btn-secondary');
      } else {
        selectedDays.add(day);
        b.classList.remove('btn-secondary'); b.classList.add('btn-primary');
      }
    });
  });

  await loadEmployees();
  await loadWeek();
});

// ===== Week nav =====
function changeWeek(deltaDays) {
  const cur = new Date(currentWeekStart + 'T12:00:00');
  cur.setDate(cur.getDate() + deltaDays);
  currentWeekStart = mondayOf(cur.toISOString().slice(0,10));
  loadWeek();
}

// ===== Loads =====
async function loadEmployees() {
  try {
    employees = await fetchJSON('/schedule/api?a=employees.list');
  } catch (e) {
    console.error('Error loading employees:', e);
    employees = [];
  }
}
async function loadWeek() {
  try {
    const data = await fetchJSON(`/schedule/api?a=shifts.week&week=${currentWeekStart}`);
    shifts  = data.shifts || [];
    isAdmin = !!data.is_admin;

    updateWeekHeader();
    renderGrid();
    await loadPublishStatus();
  } catch (e) {
    console.error('Error loading week:', e);
  }
}
async function loadPublishStatus() {
  try {
    const status = await fetchJSON(`/schedule/api?a=publish.status&week=${currentWeekStart}`);
    const ind = document.getElementById('statusIndicator');
    const btn = document.getElementById('publishBtn');
    if (status.published) {
      ind.textContent = 'Published';
      ind.className   = 'status-indicator status-published';
      btn.textContent = 'Unpublish (0)';
    } else {
      ind.textContent = 'Draft';
      ind.className   = 'status-indicator status-draft';
      btn.textContent = 'Publish (0)';
    }
    btn.style.display = isAdmin ? 'block' : 'none';
  } catch (e) {
    console.error('Error loading publish status:', e);
  }
}

// ===== UI =====
function updateWeekHeader() {
  document.getElementById('weekDisplay').textContent = formatWeekDisplay(currentWeekStart);

  const days = weekDays(currentWeekStart);
  document.querySelectorAll('.grid-header-cell[data-day]').forEach((cell, idx) => {
    if (days[idx]) cell.textContent = days[idx].display;
  });

  const active = employees.filter(emp => emp.is_active !== 0 && emp.is_active !== false);
  const teamHeader = document.querySelector('.grid-header-cell:first-child');
  if (teamHeader) teamHeader.textContent = `Team members (${active.length})`;
}

function renderGrid() {
  const body = document.getElementById('scheduleGridBody');
  body.innerHTML = '';

  const activeEmployees = employees.filter(emp => emp.is_active !== 0 && emp.is_active !== false);
  const days = weekDays(currentWeekStart);

  activeEmployees.forEach(emp => {
    const row = document.createElement('div');
    row.className = 'grid-row';

    const empCell = document.createElement('div');
    empCell.className = 'employee-cell';

    const empShifts = shifts.filter(s => s.employee_id === emp.id);
    const hours = totalHours(empShifts);

    empCell.innerHTML = `
      <div class="employee-name">${escapeHtml(emp.name)}</div>
      <div class="employee-role">${escapeHtml(emp.role_title || '')}</div>
      <div class="employee-hours">${hours.toFixed(2)} hrs</div>
    `;
    row.appendChild(empCell);

    days.forEach(day => {
      const cell = document.createElement('div');
      cell.className = 'day-cell';

      const todays = empShifts.filter(s => (s.start_dt || '').slice(0,10) === day.date);
      todays.forEach(shift => cell.appendChild(shiftBlock(shift)));

      if (isAdmin) {
        const add = document.createElement('div'); add.className = 'add-shift-area';
        const btn = document.createElement('button');
        btn.className = 'add-shift-btn';
        btn.innerHTML = '<i class="fas fa-plus"></i> Add shift';
        btn.addEventListener('click', () => openShiftModal(emp, day.date));
        add.appendChild(btn);
        cell.appendChild(add);
      }

      row.appendChild(cell);
    });

    body.appendChild(row);
  });
}

function shiftBlock(shift) {
  const div = document.createElement('div');
  div.className = 'shift-block';
  const t1 = (shift.start_dt || '').slice(11,16);
  const t2 = (shift.end_dt   || '').slice(11,16);
  div.innerHTML = `
    <div class="shift-time">${t1}-${t2}</div>
    <div class="shift-role">${escapeHtml(shift.notes || shift.employee_role || '')}</div>
    ${isAdmin ? `<button class="shift-delete" onclick="deleteShift(${shift.id})" aria-label="Delete">×</button>` : ''}
  `;
  return div;
}

async function loadRolesIntoModal() {
  try {
    const roles = await fetchJSON('/schedule/api?a=roles.list');
    const sel = document.getElementById('shiftRole');
    sel.innerHTML = ''; // clear existing

    roles.forEach(r => {
      const o = document.createElement('option');
      o.value = r.name;
      o.textContent = r.name;
      sel.appendChild(o);
    });
  } catch (e) {
    console.error('Could not load roles:', e);
  }
}

// ===== Modal/CRUD =====
async function openShiftModal(emp, ymd) {
  if (!isAdmin) return;

  currentEmployee = emp;
  selectedDays.clear();

  document.querySelectorAll('.day-selector').forEach((b) => {
    b.classList.remove('btn-primary'); b.classList.add('btn-secondary');
  });

  const dow = new Date(ymd + 'T12:00:00').getDay();
  const pre = document.querySelector(`.day-selector[data-day="${dow}"]`);
  if (pre) { selectedDays.add(String(dow)); pre.classList.add('btn-primary'); pre.classList.remove('btn-secondary'); }

  await loadRolesIntoModal();   // pull roles with spinner

  document.getElementById('startTime').value = '09:00';
  document.getElementById('endTime').value   = '17:00';
  document.getElementById('shiftRole').value = emp.role_title || '';
  document.getElementById('shiftNotes').value = '';

  shiftModal.show();
}

async function saveShift() {
  if (!currentEmployee || selectedDays.size === 0) return;

  const startTime = document.getElementById('startTime').value;
  const endTime   = document.getElementById('endTime').value;
  const role      = document.getElementById('shiftRole').value;
  const notes     = document.getElementById('shiftNotes').value;

  if (!startTime || !endTime) {
    alert('Please select start and end times');
    return;
  }

  try {
    const base = new Date(currentWeekStart + 'T12:00:00');

    for (const dayStr of selectedDays) {
      const dow = parseInt(dayStr, 10);

      // Convert JS .getDay() 0=Sun to Monday-start logic
      let offset;
      if (dow === 0) offset = 6;
      else offset = dow - 1;

      const d = new Date(base);
      d.setDate(base.getDate() + offset);

      const ymd = d.toISOString().slice(0,10);
      const start_dt = `${ymd} ${startTime}:00`;
      const end_dt   = `${ymd} ${endTime}:00`;

      await fetchJSON('/schedule/api?a=shifts.create', {
        method: 'POST',
        body: JSON.stringify({
          employee_id: currentEmployee.id,
          start_dt,
          end_dt,
          notes: notes || role
        })
      });
    }

    shiftModal.hide();
    await loadWeek();
  } catch (e) {
    console.error('Error saving shift:', e);
    alert('Error saving shift: ' + e.message);
  }
}

async function deleteShift(id) {
  if (!isAdmin || !confirm('Delete this shift?')) return;
  try {
    await fetchJSON(`/schedule/api?a=shifts.delete&id=${id}`);
    await loadWeek();
  } catch (e) {
    console.error('Error deleting shift:', e);
    alert('Error deleting shift: ' + e.message);
  }
}

async function togglePublish() {
  if (!isAdmin) return;
  try {
    await fetchJSON('/schedule/api?a=publish.set', {
      method: 'POST',
      body: JSON.stringify({
        week: currentWeekStart,
        published: 1
      })
    });

    await loadPublishStatus();
    alert('Week has been marked as published');
  } catch (e) {
    console.error('Error publishing:', e);
    alert('Error publishing: ' + e.message);
  }
}

// ===== Utils =====
function totalHours(list) {
  return list.reduce((acc, s) => {
    const a = new Date(s.start_dt), b = new Date(s.end_dt);
    const h = (b - a) / 36e5;
    return acc + (isFinite(h) ? h : 0);
  }, 0);
}
function escapeHtml(t=''){ const d=document.createElement('div'); d.textContent=t; return d.innerHTML; }

// expose delete for inline onclick
window.deleteShift = deleteShift;
</script>

