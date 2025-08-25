<?php require 'app/views/templates/header.php';
require 'app/views/templates/spinner.php';
?>

<style>
.page-wrap { background:#f8fafc; min-height:100vh; }
.h1 { font-size:1.5rem; font-weight:700; color:#111827; }
.sub { color:#6b7280; }
.card { background:#fff; border-radius:.75rem; box-shadow:0 1px 3px rgba(0,0,0,.08); }
.badge { font-size:.75rem; }
.badge-green { background:#e8f8ee; border:1px solid #bfead3; color:#0f5132; }
.badge-red { background:#fde2e1; border:1px solid #f5b5b4; color:#842029; }
.table thead th { color:#374151; font-weight:700; }
</style>

<div class="page-wrap">
  <div class="container-fluid px-4 py-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <div>
        <div class="h1">Team roster</div>
        <div class="sub">Add (hire) or terminate team members. Hiring creates rows in <b>users</b> and <b>employees</b>.</div>
      </div>
      <button class="btn btn-primary" id="btnAdd"><i class="fas fa-user-plus me-1"></i>Add team member</button>
    </div>

    <div class="card p-3">
      <div class="d-flex align-items-center mb-3">
        <div class="input-group" style="max-width:420px">
          <span class="input-group-text"><i class="fas fa-search"></i></span>
          <input id="q" class="form-control" placeholder="Search team members…">
        </div>
        <div class="form-check form-switch ms-3">
          <input class="form-check-input" type="checkbox" id="showTerminated">
          <label class="form-check-label" for="showTerminated">Show terminated</label>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table align-middle" id="tbl">
          <thead>
            <tr>
              <th>Team Member</th>
              <th>Contact</th>
              <th>Access</th>
              <th>Role</th>
              <th>Wage</th>
              <th>Status</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody id="rows"></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Hire modal -->
<div class="modal fade" id="hireModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Add team member</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Username *</label>
            <input class="form-control" id="h_username" placeholder="e.g. jsmith">
          </div>
          <div class="col-md-6">
            <label class="form-label">Full name</label>
            <input class="form-control" id="h_fullname" placeholder="John Smith">
          </div>
          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input class="form-control" id="h_email" type="email">
          </div>
          <div class="col-md-6">
            <label class="form-label">Mobile phone</label>
            <input class="form-control" id="h_phone" placeholder="(555) 555-5555">
          </div>


          <div class="col-md-5">
            <label class="form-label">Role</label>
            <select class="form-select" id="h_role"></select>
            <div class="form-text">Manage options in <b>roles</b> table.</div>
          </div>


          
          <div class="col-md-3">
            <label class="form-label">Wage</label>
            <input class="form-control" id="h_wage" type="number" step="0.01" value="0.00">
          </div>
          <div class="col-md-4">
            <label class="form-label d-block">Rate</label>
            <select class="form-select" id="h_rate">
              <option value="hourly">Hourly</option>
              <option value="salary">Salary</option>
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label">Start date</label>
            <input class="form-control" id="h_start" type="date" value="<?= date('Y-m-d') ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label d-block">Access level</label>
            <select class="form-select" id="h_access">
              <option value="0">Employee</option>
              <option value="1">Manager (Admin)</option>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Password (optional)</label>
            <input class="form-control" id="h_password" type="text" placeholder="Auto if blank">
          </div>
        </div>
        <div class="small text-muted mt-2">Hiring creates a <b>users</b> row (login) and an <b>employees</b> row (HR profile).</div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" id="hireSave">Add team member</button>
      </div>
    </div>
  </div>
</div>

<!-- Terminate modal -->
<div class="modal fade" id="termModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-user-slash me-2"></i>Terminate</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="t_user_id">
        <div class="mb-3">
          <label class="form-label">Reason</label>
          <select class="form-select" id="t_reason">
            <option value="">Select…</option>
            <option>Resignation</option><option>Dismissal</option>
            <option>Seasonal layoff</option><option>End of contract</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Termination date</label>
          <input class="form-control" id="t_date" type="date" value="<?= date('Y-m-d') ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Internal note (optional)</label>
          <textarea class="form-control" id="t_note" rows="3"></textarea>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="t_rehire" checked>
          <label class="form-check-label" for="t_rehire">Eligible for rehire</label>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-danger" id="termSave">Terminate</button>
      </div>
    </div>
  </div>
</div>

<?php require 'app/views/templates/footer.php'; ?>

<script>
let ROSTER = [];
let IS_ADMIN = false;

const M_hire  = new bootstrap.Modal(document.getElementById('hireModal'));
const M_term  = new bootstrap.Modal(document.getElementById('termModal'));

document.addEventListener('DOMContentLoaded', async () => {
  await bootstrapTeam();
  document.getElementById('q').addEventListener('input', render);
  document.getElementById('showTerminated').addEventListener('change', render);

  document.getElementById('btnAdd').addEventListener('click', async () => {
    if (!IS_ADMIN) return alert('Admin only');
    clearHireForm();
    await loadRolesForHire();
    M_hire.show();
  });

  document.getElementById('hireSave').addEventListener('click', onHireSave);
  document.getElementById('termSave').addEventListener('click', onTermSave);
});

/* ---------- Bootstrap roster ---------- */
async function bootstrapTeam() {
  const data = await get('/team/api?a=bootstrap');
  ROSTER = data.roster || [];
  IS_ADMIN = !!data.is_admin;
  render();
}

/* ---------- Spinner-aware fetch helpers ---------- */
async function fetchJSON(url, options = {}) {
  Spinner.show();
  try {
    const r = await fetch(url, { headers: { 'Content-Type': 'application/json' }, ...options });
    const t = await r.text();
    if (!r.ok) throw new Error(t || `HTTP ${r.status}`);
    return JSON.parse(t);
  } finally {
    Spinner.hide();
  }
}

async function get(url) {
  Spinner.show();
  try {
    const r = await fetch(url);
    const t = await r.text();
    if (!r.ok) throw new Error(t);
    return JSON.parse(t);
  } finally {
    Spinner.hide();
  }
}

async function post(url, data) {
  Spinner.show();
  try {
    const r = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });
    const t = await r.text();
    if (!r.ok) throw new Error(t);
    return JSON.parse(t);
  } finally {
    Spinner.hide();
  }
}

/* ---------- Roles ---------- */
async function loadRolesForHire() {
  const sel = document.getElementById('h_role');
  sel.innerHTML = '<option>Loading…</option>';

  async function tryFetch(url) {
    try {
      const r = await fetch(url, { headers: { 'Content-Type': 'application/json' }});
      const txt = await r.text();
      if (!r.ok) throw new Error(txt || `HTTP ${r.status}`);
      const json = JSON.parse(txt);
      return Array.isArray(json) ? json : (Array.isArray(json.roles) ? json.roles : []);
    } catch (e) {
      console.error('[roles.list] error from', url, e);
      return null;
    }
  }

  let roles = await tryFetch('/schedule/api?a=roles.list');
  if (!roles) roles = await tryFetch('/team/api?a=roles.list');

  sel.innerHTML = '';
  if (Array.isArray(roles) && roles.length) {
    for (const r of roles) {
      const name = (r && (r.name ?? r.title ?? r.role ?? '')).toString();
      if (!name) continue;
      const opt = document.createElement('option');
      opt.value = name;
      opt.textContent = name;
      sel.appendChild(opt);
    }
  }
  if (!sel.options.length) {
    sel.innerHTML = '<option value="">— No roles found (check API / DB) —</option>';
  }
}

/* ---------- Render roster ---------- */
function render() {
  const q = (document.getElementById('q').value || '').toLowerCase();
  const showTerm = document.getElementById('showTerminated').checked;
  const tbody = document.getElementById('rows');
  tbody.innerHTML = '';

  ROSTER.filter(r => {
    const matchQ = !q || (r.name||'').toLowerCase().includes(q) || (r.username||'').toLowerCase().includes(q) || (r.email||'').toLowerCase().includes(q);
    const matchTerm = showTerm ? true : (parseInt(r.is_active) === 1);
    return matchQ && matchTerm;
  }).forEach(r => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td><strong>${escapeHtml(r.name || r.username)}</strong><div class="small text-muted">${escapeHtml(r.username||'')}</div></td>
      <td>${escapeHtml(r.email||'')}<div class="small text-muted">${escapeHtml(r.phone||'')}</div></td>
      <td>${r.is_admin==1?'<span class="badge bg-dark">Manager</span>':'<span class="badge bg-secondary">Employee</span>'}</td>
      <td>${escapeHtml(r.role_title||'')}</td>
      <td>${Number(r.wage||0).toFixed(2)} <span class="text-muted">/ ${r.rate||'hourly'}</span></td>
      <td>
        ${r.is_active==1
          ? '<span class="badge badge-green">Active</span>'
          : `<span class="badge badge-red">Terminated</span>
             <div class="small text-muted">${escapeHtml(r.termination_reason||'')}</div>`}
      </td>
      <td class="text-end">
        ${r.is_active==1
            ? `<button class="btn btn-sm btn-outline-danger" ${IS_ADMIN?'':'disabled'} onclick="openTerminate(${r.user_id})"><i class="fas fa-user-slash"></i></button>`
            : `<button class="btn btn-sm btn-outline-success" ${IS_ADMIN?'':'disabled'} onclick="rehire(${r.user_id})"><i class="fas fa-undo"></i></button>`}
      </td>
    `;
    tbody.appendChild(tr);
  });
}

/* ---------- Hire ---------- */
function clearHireForm() {
  ['h_username','h_fullname','h_email','h_phone','h_password'].forEach(id => document.getElementById(id).value='');
  document.getElementById('h_wage').value = '0.00';
  document.getElementById('h_rate').value = 'hourly';
  document.getElementById('h_access').value = '0';
  document.getElementById('h_start').value = (new Date()).toISOString().slice(0,10);
}

async function onHireSave(){
  const payload = {
    username:  v('h_username'),
    full_name: v('h_fullname'),
    email:     v('h_email'),
    phone:     v('h_phone'),
    role_title: v('h_role'),
    wage:      parseFloat(v('h_wage')||0),
    rate:      v('h_rate'),
    is_admin:  parseInt(v('h_access'),10),
    start_date:v('h_start'),
    password:  v('h_password')
  };
  await post('/team/api?a=hire', payload);
  M_hire.hide();
  await refreshRoster();
}

function v(id){ return document.getElementById(id).value.trim(); }

/* ---------- Terminate / Rehire ---------- */
function openTerminate(user_id){
  document.getElementById('t_user_id').value = user_id;
  document.getElementById('t_reason').value = '';
  document.getElementById('t_note').value = '';
  document.getElementById('t_rehire').checked = true;
  document.getElementById('t_date').value = (new Date()).toISOString().slice(0,10);
  M_term.show();
}

async function onTermSave(){
  const payload = {
    user_id: parseInt(document.getElementById('t_user_id').value,10),
    reason:  document.getElementById('t_reason').value.trim(),
    note:    document.getElementById('t_note').value.trim(),
    termination_date: document.getElementById('t_date').value,
    eligible_for_rehire: document.getElementById('t_rehire').checked ? 1 : 0
  };
  await post('/team/api?a=terminate', payload);
  M_term.hide();
  await refreshRoster();
}

async function rehire(user_id){
  await post('/team/api?a=rehire', { user_id, start_date:(new Date()).toISOString().slice(0,10) });
  await refreshRoster();
}

/* ---------- Utils ---------- */
async function refreshRoster(){ const d = await get('/team/api?a=list'); ROSTER = d.roster||[]; render(); }
function escapeHtml(t=''){ const d=document.createElement('div'); d.textContent=t; return d.innerHTML; }
</script>

