<?php
// app/views/schedule/my.php
require 'app/views/templates/header.php';
require 'app/views/templates/spinner.php';
?>

<style>
:root {
  --primary: #3b82f6;
  --primary-hover: #2563eb;
  --gray-50: #f9fafb;
  --gray-100: #f3f4f6;
  --gray-200: #e5e7eb;
  --gray-300: #d1d5db;
  --gray-500: #6b7280;
  --gray-700: #374151;
  --gray-900: #111827;
  --shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
  --radius: 0.5rem;
  --spacing-xs: 0.25rem;
  --spacing-sm: 0.5rem;
  --spacing-md: 0.75rem;
}

.page {
  background: var(--gray-50);
  min-height: 100vh;
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
  padding: var(--spacing-sm);
}

.header {
  padding: var(--spacing-sm) 0;
  margin-bottom: var(--spacing-md);
  border-bottom: 1px solid var(--gray-200);
}

.title {
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--gray-900);
  margin-bottom: var(--spacing-xs);
}

.subtitle {
  color: var(--gray-500);
  font-size: 0.75rem;
  line-height: 1.2;
}

.controls {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-sm);
}

.toggle {
  display: flex;
  border: 1px solid var(--gray-300);
  border-radius: var(--radius);
  overflow: hidden;
  width: 100%;
}

.toggle button {
  flex: 1;
  background: #fff;
  border: 0;
  padding: var(--spacing-sm) var(--spacing-md);
  font-weight: 600;
  color: var(--gray-700);
  text-align: center;
  transition: background 0.2s ease;
  cursor: pointer;
  font-size: 0.85rem;
}

.toggle button.active {
  background: var(--primary);
  color: #fff;
}

.toggle button:hover:not(.active) {
  background: var(--gray-100);
}

.nav-buttons {
  display: flex;
  justify-content: space-between;
  width: 100%;
  margin-top: var(--spacing-sm);
}

.btn {
  border: 1px solid var(--gray-300);
  background: #fff;
  padding: var(--spacing-sm) var(--spacing-md);
  border-radius: var(--radius);
  font-weight: 600;
  color: var(--gray-700);
  font-size: 0.85rem;
  width: 2.5rem;
  text-align: center;
  transition: all 0.2s ease;
}

.btn:hover {
  background: var(--gray-100);
  transform: translateY(-1px);
}

.summary {
  font-weight: 600;
  color: var(--gray-900);
  font-size: 0.8rem;
  margin-top: var(--spacing-sm);
  text-align: right;
}

.grid {
  background: #fff;
  border: 1px solid var(--gray-200);
  box-shadow: var(--shadow);
  border-radius: var(--radius);
  overflow-x: auto;
  margin-bottom: var(--spacing-md);
}

.head {
  display: grid;
  grid-template-columns: 140px repeat(7, 1fr);
  background: var(--gray-100);
  border-bottom: 1px solid var(--gray-200);
}

.head div {
  padding: var(--spacing-sm);
  font-weight: 600;
  color: var(--gray-700);
  text-align: center;
  font-size: 0.75rem;
  white-space: nowrap;
}

.head div:first-child {
  text-align: left;
}

.row {
  display: grid;
  grid-template-columns: 140px repeat(7, 1fr);
  border-bottom: 1px solid var(--gray-200);
  min-height: 80px;
}

.emp {
  background: var(--gray-50);
  border-right: 1px solid var(--gray-200);
  padding: var(--spacing-sm);
  display: flex;
  flex-direction: column;
  gap: var(--spacing-xs);
}

.emp .name {
  font-weight: 600;
  font-size: 0.85rem;
  color: var(--gray-900);
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.cell {
  position: relative;
  padding: var(--spacing-sm);
  border-right: 1px solid var(--gray-200);
  background: #fff;
  min-height: 80px;
  display: flex;
  flex-direction: column;
  gap: var(--spacing-xs);
}

.shift {
  background: var(--primary);
  color: #fff;
  border-radius: 0.375rem;
  padding: var(--spacing-xs) var(--spacing-sm);
  margin-bottom: var(--spacing-xs);
  box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
  font-size: 0.7rem;
  line-height: 1.2;
  display: flex;
  flex-direction: column;
}

.shift.me {
  outline: 2px solid #1d4ed8;
}

.small {
  font-size: 0.65rem;
  color: var(--gray-500);
}

.info {
  margin-top: auto;
  font-size: 0.6rem;
  color: var(--gray-600);
  line-height: 1.1;
}

/* Mobile-first adjustments */
@media (min-width: 576px) {
  .page {
    padding: var(--spacing-md);
  }

  .header {
    padding: var(--spacing-md) 0;
  }

  .title {
    font-size: 1.5rem;
  }

  .subtitle {
    font-size: 0.85rem;
  }

  .toggle button {
    font-size: 0.9rem;
    padding: var(--spacing-sm) var(--spacing-lg);
  }

  .btn {
    font-size: 0.9rem;
    width: 3rem;
  }

  .summary {
    font-size: 0.9rem;
  }

  .head div {
    font-size: 0.8rem;
    padding: var(--spacing-sm) var(--spacing-md);
  }

  .emp .name {
    font-size: 0.9rem;
  }

  .cell,
  .row {
    min-height: 90px;
  }

  .shift {
    font-size: 0.75rem;
    padding: var(--spacing-sm);
  }

  .small {
    font-size: 0.7rem;
  }

  .info {
    font-size: 0.65rem;
  }
}

@media (min-width: 768px) {
  .controls {
    flex-direction: row;
    align-items: center;
  }

  .nav-buttons {
    margin-top: 0;
    width: auto;
  }

  .head,
  .row {
    grid-template-columns: 180px repeat(7, 1fr);
  }

  .head div {
    font-size: 0.85rem;
  }

  .cell,
  .row {
    min-height: 100px;
  }
}

@media (min-width: 1024px) {
  .head,
  .row {
    grid-template-columns: 220px repeat(7, 1fr);
  }

  .head div {
    font-size: 0.9rem;
  }

  .cell,
  .row {
    min-height: 110px;
  }
}
</style>

<div class="page">
  <header class="header">
    <div class="container-fluid">
      <div class="d-flex flex-column align-items-start justify-content-between">
        <div>
          <div class="title">My Shifts</div>
          <div class="subtitle">This week first. Browse weeks, compare with your team, and see weekly hours.</div>
        </div>
        <div class="controls w-100">
          <div class="toggle" role="tablist" aria-label="Scope">
            <button id="scopeMine" class="active" aria-selected="true">My shifts</button>
            <button id="scopeAll" aria-selected="false">All team</button>
          </div>
          <div class="nav-buttons">
            <button class="btn" id="prevBtn" aria-label="Previous week">◀</button>
            <div class="mx-2 fw-bold" id="weekText"></div>
            <button class="btn" id="nextBtn" aria-label="Next week">▶</button>
          </div>
        </div>
      </div>
    </div>
  </header>

  <div class="container-fluid">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center mb-2">
      <div class="small" id="whoLine"></div>
      <div class="summary"><span id="hoursLabel">My hours</span>: <span id="hoursVal">0.00</span></div>
    </div>

    <div class="grid">
      <div class="head">
        <div>Team member</div>
        <div id="h0">Mon</div>
        <div id="h1">Tue</div>
        <div id="h2">Wed</div>
        <div id="h3">Thu</div>
        <div id="h4">Fri</div>
        <div id="h5">Sat</div>
        <div id="h6">Sun</div>
      </div>
      <div id="gridBody"></div>
    </div>
  </div>
</div>

<?php require 'app/views/templates/footer.php'; ?>

<script>
async function fetchJSON(url, options = {}) {
  Spinner?.show();
  try {
    const r = await fetch(url, { headers: { 'Content-Type': 'application/json' }, ...options });
    const t = await r.text();
    if (!r.ok) { try { throw new Error(JSON.parse(t).error) } catch { throw new Error(t) } }
    return JSON.parse(t);
  } finally { Spinner?.hide(); }
}

let me = { employee_id: null, name: '' };
let employees = [];
let weekStart; // YYYY-MM-DD (Monday)
let scope = 'mine'; // 'mine' | 'all'
let allWeekShifts = [];

const mondayOf = (ymd) => {
  const d = new Date(ymd + 'T12:00:00');
  const dow = d.getDay();
  d.setDate(d.getDate() - (dow === 0 ? 6 : dow - 1));
  return d.toISOString().slice(0, 10);
};

const weekDays = (monday) => {
  const labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
  const mon = new Date(monday + 'T12:00:00');
  const a = [];
  for (let i = 0; i < 7; i++) {
    const d = new Date(mon);
    d.setDate(d.getDate() + i);
    a.push({ ymd: d.toISOString().slice(0, 10), label: labels[i] + ', ' + d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) });
  }
  return a;
};

const esc = (t = '') => {
  const d = document.createElement('div');
  d.textContent = t;
  return d.innerHTML;
};

const sumHours = (list) => list.reduce((acc, s) => acc + ((new Date(s.end_dt) - new Date(s.start_dt)) / 36e5 || 0), 0);
const uniq = (arr) => [...new Set(arr)];

function setWeekHeader() {
  const days = weekDays(weekStart);
  days.forEach((d, i) => (document.getElementById('h' + i).textContent = d.label));
  const mon = new Date(weekStart + 'T12:00:00');
  const sun = new Date(mon);
  sun.setDate(sun.getDate() + 6);
  const fmt = (x) => x.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
  document.getElementById('weekText').textContent = `${fmt(mon)} – ${fmt(sun)}`;
}

function renderShift(s, isMe) {
  const div = document.createElement('div');
  div.className = 'shift' + (isMe ? ' me' : '');
  const t1 = s.start_dt.slice(11, 16), t2 = s.end_dt.slice(11, 16);
  const role = (s.notes || s.employee_role || '').trim();
  div.innerHTML = `<div><strong>${t1}–${t2}</strong></div>${role ? `<div>${esc(role)}</div>` : ''}`;
  return div;
}

async function loadWeek() {
  setWeekHeader();
  const data = await fetchJSON(`/schedule/api?a=shifts.week&week=${weekStart}`);
  allWeekShifts = data.shifts || [];

  const body = document.getElementById('gridBody');
  body.innerHTML = '';

  if (scope === 'all') {
    document.getElementById('whoLine').textContent = 'Viewing: entire team';
    document.getElementById('hoursLabel').textContent = 'Team hours';

    const byEmp = {};
    allWeekShifts.forEach(s => (byEmp[s.employee_id] ||= []).push(s));

    const ids = Object.keys(byEmp)
      .map(Number)
      .sort((a, b) => {
        const an = (employees.find(e => e.id === a)?.name || '').toLowerCase();
        const bn = (employees.find(e => e.id === b)?.name || '').toLowerCase();
        return an < bn ? -1 : an > bn ? 1 : 0;
      });

    let total = 0;
    const days = weekDays(weekStart);

    ids.forEach(empId => {
      const row = document.createElement('div');
      row.className = 'row';
      const meta = employees.find(e => e.id === empId) || { name: '(Unknown)' };
      const list = byEmp[empId];
      const hrs = sumHours(list);
      total += hrs;

      const empCell = document.createElement('div');
      empCell.className = 'emp';
      empCell.innerHTML = `<div class="name">${esc(meta.name)}</div><div class="small">${hrs.toFixed(2)} hrs</div>${
        empId === me.employee_id ? '<div class="small">This is you</div>' : ''
      }`;
      row.appendChild(empCell);

      days.forEach(d => {
        const cell = document.createElement('div');
        cell.className = 'cell';
        list.filter(s => s.start_dt.slice(0, 10) === d.ymd).forEach(s => cell.appendChild(renderShift(s, empId === me.employee_id)));
        row.appendChild(cell);
      });

      body.appendChild(row);
    });

    document.getElementById('hoursVal').textContent = total.toFixed(2);
  } else {
    document.getElementById('whoLine').textContent = `Viewing: ${me.name}`;
    document.getElementById('hoursLabel').textContent = 'My hours';

    const myList = allWeekShifts.filter(s => s.employee_id === me.employee_id);

    const row = document.createElement('div');
    row.className = 'row';
    const meCell = document.createElement('div');
    meCell.className = 'emp';
    meCell.innerHTML = `<div class="name">${esc(me.name)}</div><div class="small">${sumHours(myList).toFixed(2)} hrs</div>`;
    row.appendChild(meCell);

    const days = weekDays(weekStart);
    days.forEach(d => {
      const cell = document.createElement('div');
      cell.className = 'cell';
      const todaysMine = myList.filter(s => s.start_dt.slice(0, 10) === d.ymd);
      todaysMine.forEach(s => {
        const el = renderShift(s, true);
        const coworkers = allWeekShifts.filter(x => x.employee_id !== me.employee_id && x.start_dt.slice(0, 10) === d.ymd);
        if (coworkers.length) {
          const names = uniq(coworkers.map(x => (employees.find(e => e.id === x.employee_id)?.name) || 'Unknown'));
          const info = document.createElement('div');
          info.className = 'info';
          info.textContent = 'With: ' + names.join(', ');
          el.appendChild(info);
        }
        cell.appendChild(el);
      });
      row.appendChild(cell);
    });

    body.appendChild(row);
    document.getElementById('hoursVal').textContent = sumHours(myList).toFixed(2);
  }
}

function bindUI() {
  document.getElementById('prevBtn').onclick = async () => {
    const d = new Date(weekStart + 'T12:00:00');
    d.setDate(d.getDate() - 7);
    weekStart = mondayOf(d.toISOString().slice(0, 10));
    await loadWeek();
  };
  document.getElementById('nextBtn').onclick = async () => {
    const d = new Date(weekStart + 'T12:00:00');
    d.setDate(d.getDate() + 7);
    weekStart = mondayOf(d.toISOString().slice(0, 10));
    await loadWeek();
  };
  const mine = document.getElementById('scopeMine'),
    all = document.getElementById('scopeAll');
  mine.onclick = async () => {
    scope = 'mine';
    mine.classList.add('active');
    all.classList.remove('active');
    await loadWeek();
  };
  all.onclick = async () => {
    scope = 'all';
    all.classList.add('active');
    mine.classList.remove('active');
    await loadWeek();
  };
}

document.addEventListener('DOMContentLoaded', async () => {
  try {
    const who = await fetchJSON('/schedule/api?a=me.employee');
    me.employee_id = who.employee_id;
    me.name = who.employee_name || 'Me';

    employees = await fetchJSON('/schedule/api?a=employees.list');

    // Set to current date (2025-09-06 05:02 EDT)
    const today = new Date('2025-09-06T05:02:00-04:00');
    weekStart = mondayOf(today.toISOString().slice(0, 10)); // This week first
    bindUI();
    await loadWeek();
  } catch (e) {
    console.error(e);
    alert('Could not load your shifts: ' + e.message);
  }
});
</script>