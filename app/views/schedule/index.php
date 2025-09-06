<?php
// app/views/schedule/index.php
require 'app/views/templates/header.php';
require 'app/views/templates/spinner.php';
?>
<style>
:root {
  --primary: #3b82f6;
  --primary-hover: #2563eb;
  --danger: #ef4444;
  --danger-hover: #dc2626;
  --success: #10b981;
  --warning: #f59e0b;
  --gray-50: #f9fafb;
  --gray-100: #f3f4f6;
  --gray-200: #e5e7eb;
  --gray-300: #d1d5db;
  --gray-400: #9ca3af;
  --gray-500: #6b7280;
  --gray-700: #374151;
  --gray-900: #111827;
  --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
  --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
  --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  --radius: 0.375rem;
  --radius-lg: 0.5rem;
  --transition: all 0.2s ease;
}

.schedule-container { background: var(--gray-50); min-height: 100vh; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
.page-header { margin-bottom: 2rem; }
.page-title { font-size: 1.875rem; font-weight: 700; color: var(--gray-900); margin-bottom: 0.5rem; }
.page-subtitle { color: var(--gray-500); font-size: 1.125rem; }

.btn { display: inline-flex; align-items: center; justify-content: center; border-radius: var(--radius); padding: 0.625rem 1.25rem; font-weight: 500; font-size: 0.875rem; border: 1px solid transparent; cursor: pointer; transition: var(--transition); gap: 0.5rem; }
.btn:hover { transform: translateY(-1px); }
.btn-sm { padding: 0.5rem 0.875rem; font-size: 0.8125rem; }
.btn-primary { background: var(--primary); color: white; box-shadow: var(--shadow-sm); }
.btn-primary:hover { background: var(--primary-hover); box-shadow: var(--shadow); }
.btn-outline { background: transparent; border: 1px solid var(--gray-300); color: var(--gray-700); }
.btn-outline:hover { background: var(--gray-50); }
.btn-danger { background: var(--danger); color: white; }
.btn-danger:hover { background: var(--danger-hover); }
.btn-success { background: var(--success); color: white; }
.btn-success:hover { background: #0da271; }
.btn-icon { padding: 0.5rem; border-radius: var(--radius); }

.badge { display: inline-block; padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 600; line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: 9999px; }
.badge-success { background: #e8f8ee; border: 1px solid #bfead3; color: #0f5132; }
.badge-warning { background: #fef3c7; border: 1px solid #fde68a; color: #92400e; }

.week-controls { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; }
.week-navigation { display: flex; align-items: center; gap: 0.5rem; }
.week-nav-btn { background: var(--gray-100); border: 1px solid var(--gray-300); border-radius: var(--radius); padding: 0.5rem; color: var(--gray-700); cursor: pointer; transition: var(--transition); display: flex; align-items: center; justify-content: center; }
.week-nav-btn:hover { background: var(--gray-200); }
.week-display { font-weight: 500; color: var(--gray-900); min-width: 260px; text-align: center; font-size: 0.875rem; }
.publish-section { display: flex; align-items: center; gap: 0.75rem; }

/* tools dropdown */
.tools-wrap { position: relative; }
.tools-menu { display: none; position: absolute; right: 0; top: 110%; background:#fff; border:1px solid var(--gray-200); border-radius: .5rem; box-shadow: var(--shadow); min-width: 240px; z-index: 10; }
.tools-menu.show { display: block; }
.tools-item { width:100%; text-align:left; background:#fff; border:0; padding:.6rem .9rem; font-size:.875rem; color:var(--gray-700); }
.tools-item:hover { background: var(--gray-50); }

.schedule-grid { background: #fff; border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow); margin-bottom: 2rem; }
.grid-header { display: grid; grid-template-columns: 240px repeat(7, 1fr); background: var(--gray-50); border-bottom: 1px solid var(--gray-200); }
.grid-header-cell { padding: 1rem 0.75rem; font-weight: 600; color: var(--gray-700); font-size: 0.875rem; text-align: center; border-right: 1px solid var(--gray-200); }
.grid-header-cell:first-child { text-align: left; background: var(--gray-100); }
.grid-body { max-height: 70vh; overflow-y: auto; }
.grid-row { display: grid; grid-template-columns: 240px repeat(7, 1fr); border-bottom: 1px solid var(--gray-200); min-height: 120px; }
.employee-cell { background: var(--gray-50); padding: 1rem 0.75rem; border-right: 1px solid var(--gray-200); display: flex; flex-direction: column; gap: 0.25rem; }
.employee-name { font-weight: 600; color: var(--gray-900); font-size: 0.9rem; }
.employee-role { color: var(--gray-500); font-size: 0.8rem; }
.employee-hours { color: var(--gray-500); font-size: 0.75rem; margin-top: auto; }

.day-cell { padding: 0.5rem; border-right: 1px solid var(--gray-200); position: relative; background: #fff; min-height: 120px; }
.shift-block { background: var(--primary); color: #fff; border-radius: var(--radius); padding: 0.5rem; margin-bottom: 0.25rem; font-size: 0.75rem; position: relative; cursor: pointer; transition: var(--transition); box-shadow: var(--shadow-sm); }
.shift-block:hover { background: var(--primary-hover); transform: translateY(-1px); box-shadow: var(--shadow); }
.shift-time { font-weight: 700; margin-bottom: 0.25rem; }
.shift-role { opacity: 0.95; font-size: 0.72rem; }
.shift-actions { position:absolute; top:.25rem; right:.25rem; display:flex; gap:.25rem; opacity:0; transition:opacity .2s; }
.shift-block:hover .shift-actions { opacity:1; }
.shift-mini { border:0; background:rgba(255,255,255,.25); color:#fff; width:22px; height:22px; border-radius:999px; font-size:.75rem; display:flex; align-items:center; justify-content:center; }

.add-shift-area { position: absolute; bottom: 0.5rem; left: 0.5rem; right: 0.5rem; }
.add-shift-btn { width: 100%; background: transparent; border: 2px dashed var(--gray-300); color: var(--gray-500); border-radius: var(--radius); padding: 0.5rem; font-size: 0.75rem; cursor: pointer; transition: var(--transition); display: flex; align-items: center; justify-content: center; gap: 0.25rem; }
.add-shift-btn:hover { border-color: var(--primary); color: var(--primary); background: #eff6ff; }

.modal-content { border: none; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); }
.modal-header { border-bottom: 1px solid var(--gray-200); padding: 1.25rem 1.5rem; }
.modal-title { margin-bottom: 0; line-height: 1.5; font-weight: 600; color: var(--gray-900); display: flex; align-items: center; gap: 0.5rem; }
.modal-body { padding: 1.5rem; }
.modal-footer { border-top: 1px solid var(--gray-200); padding: 1rem 1.5rem; gap: 0.75rem; }

.form-group { margin-bottom: 1rem; }
.form-label { display: block; font-size: 0.875rem; font-weight: 600; color: var(--gray-700); margin-bottom: 0.5rem; }
.form-control { width: 100%; border: 1px solid var(--gray-300); border-radius: var(--radius); padding: 0.625rem 0.875rem; font-size: 0.875rem; transition: var(--transition); }
.form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15); }
.role-select { appearance: none; background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e"); background-position: right 0.5rem center; background-repeat: no-repeat; background-size: 1.2em 1.2em; padding-right: 2.5rem; }
.time-input-group { display: flex; gap: 0.5rem; align-items: center; }
.time-separator { color: var(--gray-500); font-weight: 600; }
.day-selector { padding: 0.375rem 0.75rem; border-radius: var(--radius); font-size: 0.75rem; font-weight: 500; cursor: pointer; transition: var(--transition); }

.empty-state { padding: 3rem 1rem; text-align: center; color: var(--gray-500); }
.empty-state-icon { font-size: 2.5rem; margin-bottom: 1rem; opacity: 0.5; }
.empty-state-text { margin-bottom: 1.5rem; }
.loading-shimmer { background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%); background-size: 200% 100%; animation: loading 1.5s infinite; border-radius: var(--radius); height: 1rem; margin-bottom: 0.5rem; }
@keyframes loading { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

.fade-in { animation: fadeIn 0.3s ease-in; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

/* Responsive */
@media (max-width: 1200px) { .grid-header, .grid-row { grid-template-columns: 200px repeat(7, 1fr); } }
@media (max-width: 1024px) { .schedule-grid { overflow-x: auto; } .grid-header, .grid-row { min-width: 1000px; } }
@media (max-width: 768px) {
  .week-controls { flex-direction: column; align-items: flex-start; gap: 1rem; }
  .publish-section { width: 100%; justify-content: space-between; }
  .modal-dialog { margin: 1rem; }
}
@media (max-width: 640px) {
  .container-fluid { padding-left: 1rem; padding-right: 1rem; }
  .time-input-group { flex-direction: column; align-items: stretch; }
  .time-separator { text-align: center; }
}
</style>

<div class="schedule-container">
  <div class="container-fluid px-3 px-md-4 py-4">
    <div class="d-flex align-items-center justify-content-between page-header">
      <div>
        <h1 class="page-title">Schedule</h1>
        <p class="page-subtitle">Manage your team's work schedule</p>
      </div>

      <div class="week-controls">
        <div class="week-navigation" role="group" aria-label="Week navigation">
          <button class="week-nav-btn" id="prevWeekBtn" title="Previous week" aria-label="Previous week">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/></svg>
          </button>
          <div class="week-display" id="weekDisplay" aria-live="polite"></div>
          <button class="week-nav-btn" id="nextWeekBtn" title="Next week" aria-label="Next week">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/></svg>
          </button>
        </div>

        <div class="publish-section">
          <div class="tools-wrap" id="toolsWrap" style="display:none;">
            <button class="btn btn-outline btn-sm" id="toolsBtn">
              <i class="bi bi-tools"></i> Tools
            </button>
            <div class="tools-menu" id="toolsMenu">
              <button class="tools-item" id="copyPrevToThisBtn">Copy from previous week → this</button>
              <button class="tools-item" id="copyThisToNextBtn">Copy this week → next</button>
              <button class="tools-item" id="openCopyWeekModalBtn">Copy week… (pick weeks)</button>
              <hr class="m-0">
              <button class="tools-item" id="openCopyUserModalBtn">Copy shifts: User → User</button>
            </div>
          </div>
          <span class="badge badge-warning" id="statusIndicator">Draft</span>
          <button class="btn btn-primary" id="publishBtn">Publish</button>
        </div>
      </div>
    </div>

    <div class="schedule-grid">
      <div class="grid-header">
        <div class="grid-header-cell">Team members</div>
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

    <div id="emptyState" class="empty-state" style="display: none;">
      <div class="empty-state-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" viewBox="0 0 16 16"><path d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v8A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-8A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1h-3zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5zm1.886 6.914L15 7.151V12.5a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5V7.15l6.614 1.764a1.5 1.5 0 0 0 .772 0zM1.5 4h13a.5.5 0 0 1 .5.5v1.616L8.129 7.948a.5.5 0 0 1-.258 0L1 6.116V4.5a.5.5 0 0 1 .5-.5z"/></svg>
      </div>
      <div class="empty-state-text">No team members available for scheduling</div>
    </div>
  </div>
</div>

<!-- Add Shift Modal -->
<div class="modal fade" id="shiftModal" tabindex="-1" aria-labelledby="shiftModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="shiftModalLabel" class="modal-title">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
          Add Shift
        </h5>
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
            <button type="button" class="btn btn-outline btn-sm day-selector" data-day="1">Mon</button>
            <button type="button" class="btn btn-outline btn-sm day-selector" data-day="2">Tue</button>
            <button type="button" class="btn btn-outline btn-sm day-selector" data-day="3">Wed</button>
            <button type="button" class="btn btn-outline btn-sm day-selector" data-day="4">Thu</button>
            <button type="button" class="btn btn-outline btn-sm day-selector" data-day="5">Fri</button>
            <button type="button" class="btn btn-outline btn-sm day-selector" data-day="6">Sat</button>
            <button type="button" class="btn btn-outline btn-sm day-selector" data-day="0">Sun</button>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label" for="shiftNotes">Shift Notes:</label>
          <textarea id="shiftNotes" class="form-control" rows="3" placeholder="Notes employees will see."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="saveShiftBtn">Add Shift</button>
      </div>
    </div>
  </div>
</div>

<!-- Copy Week Modal -->
<div class="modal fade" id="copyWeekModal" tabindex="-1" aria-labelledby="copyWeekModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="copyWeekModalLabel" class="modal-title">Copy Week</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Source week (Monday)</label>
          <input type="date" id="cwSource" class="form-control" disabled>
        </div>
        <div class="form-group">
          <label class="form-label">Target week (Monday)</label>
          <input type="date" id="cwTarget" class="form-control">
        </div>
        <div class="form-check mt-2">
          <input class="form-check-input" type="checkbox" id="cwOverwrite">
          <label class="form-check-label" for="cwOverwrite">Overwrite target week (delete existing shifts)</label>
        </div>
        <small class="text-muted d-block mt-2">Tip: target must be a Monday—use the date picker.</small>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="cwDoCopyBtn">Copy</button>
      </div>
    </div>
  </div>
</div>

<!-- Copy User→User Modal -->
<div class="modal fade" id="copyUserModal" tabindex="-1" aria-labelledby="copyUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="copyUserModalLabel" class="modal-title">Copy Shifts: User → User (this week)</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">From user</label>
          <select id="cuFrom" class="form-control"></select>
        </div>
        <div class="form-group">
          <label class="form-label">To user</label>
          <select id="cuTo" class="form-control"></select>
        </div>
        <div class="form-group">
          <label class="form-label">Days</label>
          <div class="d-flex gap-2 flex-wrap mt-1" id="cuDays">
            <button type="button" class="btn btn-outline btn-sm cu-day" data-day="1">Mon</button>
            <button type="button" class="btn btn-outline btn-sm cu-day" data-day="2">Tue</button>
            <button type="button" class="btn btn-outline btn-sm cu-day" data-day="3">Wed</button>
            <button type="button" class="btn btn-outline btn-sm cu-day" data-day="4">Thu</button>
            <button type="button" class="btn btn-outline btn-sm cu-day" data-day="5">Fri</button>
            <button type="button" class="btn btn-outline btn-sm cu-day" data-day="6">Sat</button>
            <button type="button" class="btn btn-outline btn-sm cu-day" data-day="0">Sun</button>
          </div>
        </div>
        <div class="form-check mt-1">
          <input class="form-check-input" type="checkbox" id="cuOverwrite">
          <label class="form-check-label" for="cuOverwrite">Overwrite target user’s shifts for chosen days</label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="cuDoCopyBtn">Copy shifts</button>
      </div>
    </div>
  </div>
</div>

<!-- Copy Single Shift Modal -->
<div class="modal fade" id="copyOneModal" tabindex="-1" aria-labelledby="copyOneModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="copyOneModalLabel" class="modal-title">Copy This Shift</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="coShiftId">
        <div class="form-group">
          <label class="form-label">To user</label>
          <select id="coTo" class="form-control"></select>
        </div>
        <div class="form-group">
          <label class="form-label">Target date</label>
          <input type="date" id="coDate" class="form-control">
        </div>
        <small class="text-muted d-block mt-2">Time and notes will be copied; you can move it to any date.</small>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="coDoCopyBtn">Copy</button>
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

    let data;
    try { data = JSON.parse(text); } catch (e) { console.error('[fetchJSON] Invalid JSON:', text.slice(0, 400)); throw e; }
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
let shiftModal, copyWeekModal, copyUserModal, copyOneModal;
let currentEmployee = null;
let selectedDays = new Set();
let cuSelectedDays = new Set();

// ===== Dates =====
function mondayOf(dateStr) {
  const d = new Date(dateStr + 'T12:00:00');
  const dow = d.getDay(); // 0..6 (Sun..Sat)
  const offset = (dow === 0) ? 6 : (dow - 1);
  d.setDate(d.getDate() - offset);
  return d.toISOString().slice(0,10);
}
function nextMonday(ymd) {
  const d = new Date(ymd + 'T12:00:00');
  const dow = d.getDay();            // 0=Sun..6=Sat
  const add = (8 - (dow || 7));      // days to next Mon
  d.setDate(d.getDate() + add);
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
  shiftModal    = new bootstrap.Modal(document.getElementById('shiftModal'));
  copyWeekModal = new bootstrap.Modal(document.getElementById('copyWeekModal'));
  copyUserModal = new bootstrap.Modal(document.getElementById('copyUserModal'));
  copyOneModal  = new bootstrap.Modal(document.getElementById('copyOneModal'));

  const today = new Date().toISOString().slice(0,10);
  currentWeekStart = mondayOf(today);

  document.getElementById('prevWeekBtn').addEventListener('click', () => changeWeek(-7));
  document.getElementById('nextWeekBtn').addEventListener('click', () => changeWeek(7));
  document.getElementById('publishBtn').addEventListener('click', togglePublish);
  document.getElementById('saveShiftBtn').addEventListener('click', saveShift);

  // day selectors (Add Shift modal)
  document.querySelectorAll('.day-selector').forEach((btn) => {
    btn.addEventListener('click', (e) => {
      const b = e.currentTarget;
      const day = b.dataset.day;
      if (selectedDays.has(day)) {
        selectedDays.delete(day); b.classList.remove('btn-primary'); b.classList.add('btn-outline');
      } else {
        selectedDays.add(day); b.classList.remove('btn-outline'); b.classList.add('btn-primary');
      }
    });
  });

  // Tools dropdown (admin only)
  const toolsBtn  = document.getElementById('toolsBtn');
  const toolsMenu = document.getElementById('toolsMenu');
  if (toolsBtn) {
    toolsBtn.addEventListener('click', () => toolsMenu.classList.toggle('show'));
    document.addEventListener('click', (e) => { if (!document.getElementById('toolsWrap').contains(e.target)) toolsMenu.classList.remove('show'); });
  }

  // Quick tools actions
  document.getElementById('copyPrevToThisBtn').addEventListener('click', () => copyPrevToThis());
  document.getElementById('copyThisToNextBtn').addEventListener('click', () => copyThisToNext());
  document.getElementById('openCopyWeekModalBtn').addEventListener('click', () => openCopyWeekModal());
  document.getElementById('openCopyUserModalBtn').addEventListener('click', () => openCopyUserModal());
  document.getElementById('cwDoCopyBtn').addEventListener('click', () => doCopyWeek());
  document.getElementById('cuDoCopyBtn').addEventListener('click', () => doCopyUser());
  document.getElementById('coDoCopyBtn').addEventListener('click', () => doCopyOne());

  // days pickers inside Copy User modal
  document.querySelectorAll('.cu-day').forEach((b) => {
    b.addEventListener('click', () => { 
      const d = b.dataset.day;
      if (cuSelectedDays.has(d)) { cuSelectedDays.delete(d); b.classList.remove('btn-primary'); b.classList.add('btn-outline'); }
      else { cuSelectedDays.add(d); b.classList.remove('btn-outline'); b.classList.add('btn-primary'); }
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
  try { employees = await fetchJSON('/schedule/api?a=employees.list'); }
  catch (e) { console.error('Error loading employees:', e); employees = []; }
}

async function loadWeek() {
  try {
    const data = await fetchJSON(`/schedule/api?a=shifts.week&week=${currentWeekStart}`);
    shifts  = data.shifts || [];
    isAdmin = !!data.is_admin;

    updateWeekHeader();
    renderGrid();
    await loadPublishStatus();

    // show/hide tools for admin
    const toolsWrap = document.getElementById('toolsWrap');
    if (toolsWrap) toolsWrap.style.display = isAdmin ? 'block' : 'none';
  } catch (e) {
    console.error('Error loading week:', e);
    showError('Failed to load schedule data');
  }
}

async function loadPublishStatus() {
  try {
    const status = await fetchJSON(`/schedule/api?a=publish.status&week=${currentWeekStart}`);
    const ind = document.getElementById('statusIndicator');
    const btn = document.getElementById('publishBtn');
    if (status.published) { ind.textContent = 'Published'; ind.className = 'badge badge-success'; btn.textContent = 'Unpublish'; }
    else { ind.textContent = 'Draft'; ind.className = 'badge badge-warning'; btn.textContent = 'Publish'; }
    btn.style.display = isAdmin ? 'block' : 'none';
  } catch (e) { console.error('Error loading publish status:', e); }
}

// ===== UI =====
function updateWeekHeader() {
  document.getElementById('weekDisplay').textContent = formatWeekDisplay(currentWeekStart);
  const days = weekDays(currentWeekStart);
  document.querySelectorAll('.grid-header-cell[data-day]').forEach((cell, idx) => { if (days[idx]) cell.textContent = days[idx].display; });

  const active = employees.filter(emp => emp.is_active !== 0 && emp.is_active !== false);
  const teamHeader = document.querySelector('.grid-header-cell:first-child');
  if (teamHeader) teamHeader.textContent = `Team members (${active.length})`;
}

function renderGrid() {
  const body = document.getElementById('scheduleGridBody');
  const emptyState = document.getElementById('emptyState');
  body.innerHTML = '';

  const activeEmployees = employees.filter(emp => emp.is_active !== 0 && emp.is_active !== false);
  if (activeEmployees.length === 0) { emptyState.style.display = 'block'; return; }
  emptyState.style.display = 'none';

  const days = weekDays(currentWeekStart);

  activeEmployees.forEach(emp => {
    const row = document.createElement('div');
    row.className = 'grid-row fade-in';

    const empCell = document.createElement('div');
    empCell.className = 'employee-cell';

    const empShifts = shifts.filter(s => s.employee_id === emp.id);
    const hours = totalHours(empShifts);

    empCell.innerHTML = `
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <div class="employee-name">${escapeHtml(emp.name)}</div>
          <div class="employee-role">${escapeHtml(emp.role_title || '')}</div>
        </div>
        ${isAdmin ? `<button class="btn btn-outline btn-sm" title="Copy this user’s shifts to another user" onclick="openCopyUserModal(${emp.id})">Copy →</button>` : ''}
      </div>
      <div class="employee-hours">${hours.toFixed(2)} hrs</div>
    `;
    row.appendChild(empCell);

    days.forEach(day => {
      const cell = document.createElement('div');
      cell.className = 'day-cell';

      const todays = empShifts.filter(s => (s.start_dt || '').slice(0,10) === day.date);
      todays.forEach(shift => cell.appendChild(shiftBlock(shift)));

      if (isAdmin) {
        const add = document.createElement('div'); 
        add.className = 'add-shift-area';
        const btn = document.createElement('button');
        btn.className = 'add-shift-btn';
        btn.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16"><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
          Add shift
        `;
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
    ${isAdmin ? `
      <div class="shift-actions">
        <button class="shift-mini" title="Copy" onclick="openCopyOne(${shift.id}, '${(shift.start_dt||'').slice(0,10)}')">⎘</button>
        <button class="shift-mini" title="Delete" onclick="deleteShift(${shift.id})">×</button>
      </div>` : ''}
  `;
  return div;
}

async function loadRolesIntoModal() {
  try {
    const roles = await fetchJSON('/schedule/api?a=roles.list');
    const sel = document.getElementById('shiftRole');
    sel.innerHTML = '';
    roles.forEach(r => { const o = document.createElement('option'); o.value = r.name; o.textContent = r.name; sel.appendChild(o); });
  } catch (e) { console.error('Could not load roles:', e); }
}

// ===== Modal/CRUD =====
async function openShiftModal(emp, ymd) {
  if (!isAdmin) return;
  currentEmployee = emp;
  selectedDays.clear();
  document.querySelectorAll('.day-selector').forEach((b) => { b.classList.remove('btn-primary'); b.classList.add('btn-outline'); });

  const dow = new Date(ymd + 'T12:00:00').getDay();
  const pre = document.querySelector(`.day-selector[data-day="${dow}"]`);
  if (pre) { selectedDays.add(String(dow)); pre.classList.add('btn-primary'); pre.classList.remove('btn-outline'); }

  await loadRolesIntoModal();

  document.getElementById('startTime').value = '09:00';
  document.getElementById('endTime').value   = '17:00';
  document.getElementById('shiftRole').value = emp.role_title || '';
  document.getElementById('shiftNotes').value = '';

  shiftModal.show();
}

async function saveShift() {
  if (!currentEmployee || selectedDays.size === 0) { showError('Please select at least one day'); return; }
  const startTime = document.getElementById('startTime').value;
  const endTime   = document.getElementById('endTime').value;
  const role      = document.getElementById('shiftRole').value;
  const notes     = document.getElementById('shiftNotes').value;
  if (!startTime || !endTime) { showError('Please select start and end times'); return; }

  try {
    const base = new Date(currentWeekStart + 'T12:00:00');
    for (const dayStr of selectedDays) {
      const dow = parseInt(dayStr, 10);
      let offset; if (dow === 0) offset = 6; else offset = dow - 1;
      const d = new Date(base); d.setDate(base.getDate() + offset);
      const ymd = d.toISOString().slice(0,10);
      const start_dt = `${ymd} ${startTime}:00`;
      const end_dt   = `${ymd} ${endTime}:00`;
      await fetchJSON('/schedule/api?a=shifts.create', { method: 'POST', body: JSON.stringify({ employee_id: currentEmployee.id, start_dt, end_dt, notes: notes || role }) });
    }
    shiftModal.hide();
    await loadWeek();
    showSuccess('Shift(s) added successfully');
  } catch (e) { console.error('Error saving shift:', e); showError('Error saving shift: ' + e.message); }
}

async function deleteShift(id) {
  if (!isAdmin || !confirm('Are you sure you want to delete this shift?')) return;
  try { await fetchJSON(`/schedule/api?a=shifts.delete&id=${id}`); await loadWeek(); showSuccess('Shift deleted successfully'); }
  catch (e) { console.error('Error deleting shift:', e); showError('Error deleting shift: ' + e.message); }
}

async function togglePublish() {
  if (!isAdmin) return;
  try {
    const status = await fetchJSON(`/schedule/api?a=publish.status&week=${currentWeekStart}`);
    const newStatus = !status.published;
    await fetchJSON('/schedule/api?a=publish.set', { method: 'POST', body: JSON.stringify({ week: currentWeekStart, published: newStatus ? 1 : 0 }) });
    await loadPublishStatus();
    showSuccess(`Schedule ${newStatus ? 'published' : 'unpublished'} successfully`);
  } catch (e) { console.error('Error toggling publish status:', e); showError('Error: ' + e.message); }
}

// ===== Tools: Copy week / user / one =====
function openCopyWeekModal() {
  if (!isAdmin) return;
  document.getElementById('cwSource').value = currentWeekStart;
  document.getElementById('cwTarget').value = nextMonday(currentWeekStart);
  document.getElementById('cwOverwrite').checked = false;
  copyWeekModal.show();
}
async function doCopyWeek() {
  const source_week = document.getElementById('cwSource').value;
  const target_week = document.getElementById('cwTarget').value;
  const overwrite   = document.getElementById('cwOverwrite').checked ? 1 : 0;
  if (!target_week) { showError('Pick a target Monday'); return; }
  try {
    await fetchJSON('/schedule/api?a=shifts.copyWeek', { method:'POST', body: JSON.stringify({ source_week, target_week, overwrite }) });
    copyWeekModal.hide();
    await loadWeek(); // in case you copied to current week
    showSuccess('Week copied');
  } catch (e) { showError('Copy failed: ' + e.message); }
}
async function copyPrevToThis() {
  const prevMon = (()=>{ const d=new Date(currentWeekStart+'T12:00:00'); d.setDate(d.getDate()-7); return d.toISOString().slice(0,10); })();
  try {
    await fetchJSON('/schedule/api?a=shifts.copyWeek', { method:'POST', body: JSON.stringify({ source_week: prevMon, target_week: currentWeekStart, overwrite: 0 }) });
    await loadWeek();
    showSuccess('Copied previous week to this week');
  } catch (e) { showError('Copy failed: ' + e.message); }
}
async function copyThisToNext() {
  const nextMon = nextMonday(currentWeekStart);
  try {
    await fetchJSON('/schedule/api?a=shifts.copyWeek', { method:'POST', body: JSON.stringify({ source_week: currentWeekStart, target_week: nextMon, overwrite: 0 }) });
    showSuccess('Copied this week to next week');
  } catch (e) { showError('Copy failed: ' + e.message); }
}

function openCopyUserModal(prefillFromId = null) {
  if (!isAdmin) return;
  const fromSel = document.getElementById('cuFrom');
  const toSel   = document.getElementById('cuTo');
  fromSel.innerHTML = ''; toSel.innerHTML = '';
  employees.forEach(e => {
    const o1 = document.createElement('option'); o1.value = e.id; o1.textContent = e.name; fromSel.appendChild(o1);
    const o2 = document.createElement('option'); o2.value = e.id; o2.textContent = e.name; toSel.appendChild(o2);
  });
  if (prefillFromId) fromSel.value = String(prefillFromId);
  cuSelectedDays = new Set(['1','2','3','4','5','6','0']); // all days default
  document.querySelectorAll('.cu-day').forEach(b => { b.classList.remove('btn-outline'); b.classList.add('btn-primary'); });
  document.getElementById('cuOverwrite').checked = false;
  copyUserModal.show();
}
async function doCopyUser() {
  const from_employee_id = parseInt(document.getElementById('cuFrom').value,10);
  const to_employee_id   = parseInt(document.getElementById('cuTo').value,10);
  const overwrite        = document.getElementById('cuOverwrite').checked ? 1 : 0;
  if (!from_employee_id || !to_employee_id || from_employee_id === to_employee_id) { showError('Choose two different users'); return; }
  const days = Array.from(cuSelectedDays).map(x=>parseInt(x,10));
  try {
    await fetchJSON('/schedule/api?a=shifts.copyUserToUser', { method:'POST', body: JSON.stringify({ week: currentWeekStart, from_employee_id, to_employee_id, days, overwrite }) });
    copyUserModal.hide();
    await loadWeek();
    showSuccess('Shifts copied between users');
  } catch (e) { showError('Copy failed: ' + e.message); }
}

// Single-shift copy
function openCopyOne(shiftId, dateYmd) {
  if (!isAdmin) return;
  document.getElementById('coShiftId').value = String(shiftId);
  document.getElementById('coDate').value = dateYmd;
  const toSel = document.getElementById('coTo'); toSel.innerHTML = '';
  employees.forEach(e => { const o=document.createElement('option'); o.value=e.id; o.textContent=e.name; toSel.appendChild(o); });
  copyOneModal.show();
}
async function doCopyOne() {
  const shift_id = parseInt(document.getElementById('coShiftId').value,10);
  const to_employee_id = parseInt(document.getElementById('coTo').value,10);
  const target_date = document.getElementById('coDate').value;
  if (!shift_id || !to_employee_id || !target_date) { showError('Missing info'); return; }
  try {
    await fetchJSON('/schedule/api?a=shifts.copyShift', { method:'POST', body: JSON.stringify({ shift_id, to_employee_id, target_date }) });
    copyOneModal.hide();
    await loadWeek();
    showSuccess('Shift copied');
  } catch (e) { showError('Copy failed: ' + e.message); }
}

// ===== Utils =====
function totalHours(list) {
  return list.reduce((acc, s) => { const a=new Date(s.start_dt), b=new Date(s.end_dt); const h=(b-a)/36e5; return acc + (isFinite(h) ? h : 0); }, 0);
}
function escapeHtml(t=''){ const d = document.createElement('div'); d.textContent = t; return d.innerHTML; }
function showError(message) { alert('Error: ' + message); }
function showSuccess(message) { alert('Success: ' + message); }

window.deleteShift = deleteShift;
window.openCopyUserModal = openCopyUserModal;
window.openCopyOne = openCopyOne;
</script>
