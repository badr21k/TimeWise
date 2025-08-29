<?php require 'app/views/templates/header.php';
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

.page-wrap { 
  background: var(--gray-50); 
  min-height: 100vh; 
  padding: 1rem 0;
}

.page-header {
  margin-bottom: 2rem;
}

.page-title {
  font-size: 1.875rem;
  font-weight: 700;
  color: var(--gray-900);
  margin-bottom: 0.5rem;
}

.page-subtitle {
  color: var(--gray-500);
  font-size: 1.125rem;
}

.card { 
  background: #fff; 
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow);
  overflow: hidden;
}

.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: var(--radius);
  padding: 0.625rem 1.25rem;
  font-weight: 500;
  font-size: 0.875rem;
  border: 1px solid transparent;
  cursor: pointer;
  transition: var(--transition);
  gap: 0.5rem;
}

.btn:hover {
  transform: translateY(-1px);
}

.btn-sm {
  padding: 0.5rem 0.875rem;
  font-size: 0.8125rem;
}

.btn-primary {
  background: var(--primary);
  color: white;
  box-shadow: var(--shadow-sm);
}

.btn-primary:hover {
  background: var(--primary-hover);
  box-shadow: var(--shadow);
}

.btn-outline {
  background: transparent;
  border: 1px solid var(--gray-300);
  color: var(--gray-700);
}

.btn-outline:hover {
  background: var(--gray-50);
}

.btn-danger {
  background: var(--danger);
  color: white;
}

.btn-danger:hover {
  background: var(--danger-hover);
}

.btn-success {
  background: var(--success);
  color: white;
}

.btn-success:hover {
  background: #0da271;
}

.btn-icon {
  padding: 0.5rem;
  border-radius: var(--radius);
}

.badge {
  display: inline-block;
  padding: 0.35rem 0.65rem;
  font-size: 0.75rem;
  font-weight: 600;
  line-height: 1;
  text-align: center;
  white-space: nowrap;
  vertical-align: baseline;
  border-radius: 9999px;
}

.badge-success {
  background: #e8f8ee;
  border: 1px solid #bfead3;
  color: #0f5132;
}

.badge-danger {
  background: #fde2e1;
  border: 1px solid #f5b5b4;
  color: #842029;
}

.badge-dark {
  background: #e5e7eb;
  border: 1px solid #d1d5db;
  color: #374151;
}

.badge-secondary {
  background: #f3f4f6;
  border: 1px solid #e5e7eb;
  color: #6b7280;
}

.table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
}

.table th {
  font-weight: 600;
  color: var(--gray-700);
  background: var(--gray-50);
  padding: 0.875rem 1rem;
  border-bottom: 1px solid var(--gray-200);
  text-align: left;
  font-size: 0.875rem;
  text-transform: uppercase;
  letter-spacing: 0.025em;
}

.table td {
  padding: 1rem;
  border-bottom: 1px solid var(--gray-200);
  vertical-align: middle;
}

.table tbody tr {
  transition: var(--transition);
}

.table tbody tr:hover {
  background: var(--gray-50);
}

.input-group {
  position: relative;
  display: flex;
  flex-wrap: wrap;
  align-items: stretch;
  width: 100%;
}

.input-group-text {
  display: flex;
  align-items: center;
  padding: 0.625rem 0.875rem;
  font-size: 0.875rem;
  font-weight: 400;
  line-height: 1.5;
  color: var(--gray-500);
  text-align: center;
  white-space: nowrap;
  background-color: var(--gray-100);
  border: 1px solid var(--gray-300);
  border-radius: var(--radius);
  border-right: 0;
  border-top-right-radius: 0;
  border-bottom-right-radius: 0;
}

.form-control {
  display: block;
  width: 100%;
  padding: 0.625rem 0.875rem;
  font-size: 0.875rem;
  font-weight: 400;
  line-height: 1.5;
  color: var(--gray-700);
  background-color: #fff;
  background-clip: padding-box;
  border: 1px solid var(--gray-300);
  border-radius: var(--radius);
  transition: var(--transition);
}

.form-control:focus {
  outline: 0;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
}

.form-select {
  display: block;
  width: 100%;
  padding: 0.625rem 2.25rem 0.625rem 0.875rem;
  font-size: 0.875rem;
  font-weight: 400;
  line-height: 1.5;
  color: var(--gray-700);
  background-color: #fff;
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 0.875rem center;
  background-size: 16px 12px;
  border: 1px solid var(--gray-300);
  border-radius: var(--radius);
  transition: var(--transition);
}

.form-select:focus {
  border-color: var(--primary);
  outline: 0;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
}

.form-check {
  display: block;
  min-height: 1.5rem;
  padding-left: 1.5em;
  margin-bottom: 0.125rem;
}

.form-check-input {
  width: 1em;
  height: 1em;
  margin-top: 0.25em;
  vertical-align: top;
  background-color: #fff;
  background-repeat: no-repeat;
  background-position: center;
  background-size: contain;
  border: 1px solid var(--gray-300);
  appearance: none;
  print-color-adjust: exact;
  transition: var(--transition);
  position: relative;
  margin-left: -1.5em;
}

.form-check-input:checked {
  background-color: var(--primary);
  border-color: var(--primary);
}

.form-check-input:focus {
  border-color: var(--primary);
  outline: 0;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
}

.form-check-label {
  cursor: pointer;
  font-size: 0.875rem;
  color: var(--gray-700);
}

.form-label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: var(--gray-700);
  font-size: 0.875rem;
}

.form-text {
  margin-top: 0.25rem;
  font-size: 0.75rem;
  color: var(--gray-500);
}

.text-muted {
  color: var(--gray-500) !important;
}

.small-text {
  font-size: 0.75rem;
  color: var(--gray-500);
  margin-top: 0.25rem;
}

.modal-header {
  padding: 1.25rem 1.5rem;
  border-bottom: 1px solid var(--gray-200);
}

.modal-title {
  margin-bottom: 0;
  line-height: 1.5;
  font-weight: 600;
  color: var(--gray-900);
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.modal-body {
  padding: 1.5rem;
}

.modal-footer {
  padding: 1rem 1.5rem;
  border-top: 1px solid var(--gray-200);
}

.btn-close {
  box-sizing: content-box;
  width: 1em;
  height: 1em;
  padding: 0.25em;
  color: #000;
  background: transparent url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23000'%3e%3cpath d='M.293.293a1 1 0 0 1 1.414 0L8 6.586 14.293.293a1 1 0 1 1 1.414 1.414L9.414 8l6.293 6.293a1 1 0 0 1-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 0 1-1.414-1.414L6.586 8 .293 1.707a1 1 0 0 1 0-1.414z'/%3e%3c/svg%3e") center/1em auto no-repeat;
  border: 0;
  border-radius: var(--radius);
  opacity: 0.5;
  cursor: pointer;
}

.btn-close:hover {
  opacity: 0.75;
}

.empty-state {
  padding: 3rem 1rem;
  text-align: center;
  color: var(--gray-500);
}

.empty-state-icon {
  font-size: 2.5rem;
  margin-bottom: 1rem;
  opacity: 0.5;
}

.empty-state-text {
  margin-bottom: 1.5rem;
}

.loading-shimmer {
  background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
  background-size: 200% 100%;
  animation: loading 1.5s infinite;
  border-radius: var(--radius);
  height: 1rem;
  margin-bottom: 0.5rem;
}

@keyframes loading {
  0% { background-position: 200% 0; }
  100% { background-position: -200% 0; }
}

.fade-in {
  animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Responsive styles */
@media (max-width: 1024px) {
  .table-responsive {
    overflow-x: auto;
  }
  
  .table {
    min-width: 800px;
  }
}

@media (max-width: 768px) {
  .page-header {
    flex-direction: column;
    align-items: flex-start !important;
    gap: 1rem;
  }
  
  .modal-dialog {
    margin: 1rem;
  }
  
  .btn {
    width: 100%;
    justify-content: center;
  }
}

@media (max-width: 640px) {
  .container-fluid {
    padding-left: 1rem;
    padding-right: 1rem;
  }
  
  .card {
    border-radius: 0;
    margin-left: -1rem;
    margin-right: -1rem;
  }
}
</style>

<div class="page-wrap">
  <div class="container-fluid px-3 px-md-4 py-4">
    <div class="d-flex align-items-center justify-content-between page-header">
      <div>
        <h1 class="page-title">Team Roster</h1>
        <p class="page-subtitle">Add (hire) or terminate team members. Hiring creates rows in <b>users</b> and <b>employees</b> tables.</p>
      </div>
      <button class="btn btn-primary" id="btnAdd">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
          <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
        </svg>
        Add Team Member
      </button>
    </div>

    <div class="card">
      <div class="d-flex align-items-center p-3 border-bottom">
        <div class="input-group me-3" style="max-width:420px">
          <span class="input-group-text">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
              <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
            </svg>
          </span>
          <input id="q" class="form-control" placeholder="Search team members…">
        </div>
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="showTerminated">
          <label class="form-check-label" for="showTerminated">Show terminated</label>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table" id="tbl">
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
      
      <div id="emptyState" class="empty-state" style="display: none;">
        <div class="empty-state-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" viewBox="0 0 16 16">
            <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7Zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm-5.784 6A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216ZM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"/>
          </svg>
        </div>
        <div class="empty-state-text">No team members found</div>
        <button class="btn btn-primary" id="btnAddEmpty">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
          </svg>
          Add Team Member
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Hire modal -->
<div class="modal fade" id="hireModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
          </svg>
          Add Team Member
        </h5>
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
        <div class="small-text mt-3">Hiring creates a <b>users</b> row (login) and an <b>employees</b> row (HR profile).</div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" id="hireSave">Add Team Member</button>
      </div>
    </div>
  </div>
</div>

<!-- Terminate modal -->
<div class="modal fade" id="termModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
          </svg>
          Terminate Team Member
        </h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="t_user_id">
        <div class="mb-3">
          <label class="form-label">Reason</label>
          <select class="form-select" id="t_reason">
            <option value="">Select…</option>
            <option>Resignation</option>
            <option>Dismissal</option>
            <option>Seasonal layoff</option>
            <option>End of contract</option>
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
        <button class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
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
  
  document.getElementById('btnAddEmpty').addEventListener('click', async () => {
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
  try {
    const data = await get('/team/api?a=bootstrap');
    ROSTER = data.roster || [];
    IS_ADMIN = !!data.is_admin;
    render();
  } catch (error) {
    console.error('Failed to load team data:', error);
    showError('Failed to load team data. Please refresh the page.');
  }
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
  const emptyState = document.getElementById('emptyState');
  
  const filteredRoster = ROSTER.filter(r => {
    const matchQ = !q || 
      (r.name||'').toLowerCase().includes(q) || 
      (r.username||'').toLowerCase().includes(q) || 
      (r.email||'').toLowerCase().includes(q);
    const matchTerm = showTerm ? true : (parseInt(r.is_active) === 1);
    return matchQ && matchTerm;
  });
  
  if (filteredRoster.length === 0) {
    tbody.innerHTML = '';
    emptyState.style.display = 'block';
    return;
  }
  
  emptyState.style.display = 'none';
  tbody.innerHTML = '';

  filteredRoster.forEach(r => {
    const tr = document.createElement('tr');
    tr.classList.add('fade-in');
    tr.innerHTML = `
      <td>
        <div class="d-flex align-items-center">
          <div class="me-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16" style="color: var(--gray-400)">
              <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
              <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
            </svg>
          </div>
          <div>
            <strong>${escapeHtml(r.name || r.username)}</strong>
            <div class="small-text">${escapeHtml(r.username||'')}</div>
          </div>
        </div>
      </td>
      <td>
        <div>${escapeHtml(r.email||'')}</div>
        <div class="small-text">${escapeHtml(r.phone||'')}</div>
      </td>
      <td>
        ${r.is_admin==1
          ? '<span class="badge badge-dark">Manager</span>'
          : '<span class="badge badge-secondary">Employee</span>'}
      </td>
      <td>${escapeHtml(r.role_title||'')}</td>
      <td>
        <div>$${Number(r.wage||0).toFixed(2)}</div>
        <div class="small-text">${r.rate||'hourly'}</div>
      </td>
      <td>
        ${r.is_active==1
          ? '<span class="badge badge-success">Active</span>'
          : `<span class="badge badge-danger">Terminated</span>
             <div class="small-text">${escapeHtml(r.termination_reason||'')}</div>`}
      </td>
      <td class="text-end">
        ${r.is_active==1
            ? `<button class="btn btn-sm btn-outline-danger" ${IS_ADMIN?'':'disabled'} onclick="openTerminate(${r.user_id})">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                  <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                  <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                </svg>
               </button>`
            : `<button class="btn btn-sm btn-outline-success" ${IS_ADMIN?'':'disabled'} onclick="rehire(${r.user_id})">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                  <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                </svg>
               </button>`}
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
  const username = v('h_username');
  if (!username) {
    showError('Username is required');
    return;
  }
  
  try {
    const payload = {
      username:  username,
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
    showSuccess('Team member added successfully');
  } catch (error) {
    showError('Failed to add team member: ' + error.message);
  }
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
  const reason = document.getElementById('t_reason').value.trim();
  if (!reason) {
    showError('Please select a termination reason');
    return;
  }
  
  try {
    const payload = {
      user_id: parseInt(document.getElementById('t_user_id').value,10),
      reason:  reason,
      note:    document.getElementById('t_note').value.trim(),
      termination_date: document.getElementById('t_date').value,
      eligible_for_rehire: document.getElementById('t_rehire').checked ? 1 : 0
    };
    
    await post('/team/api?a=terminate', payload);
    M_term.hide();
    await refreshRoster();
    showSuccess('Team member terminated successfully');
  } catch (error) {
    showError('Failed to terminate team member: ' + error.message);
  }
}

async function rehire(user_id){
  try {
    await post('/team/api?a=rehire', { user_id, start_date:(new Date()).toISOString().slice(0,10) });
    await refreshRoster();
    showSuccess('Team member rehired successfully');
  } catch (error) {
    showError('Failed to rehire team member: ' + error.message);
  }
}

/* ---------- Utils ---------- */
async function refreshRoster(){ 
  try {
    const d = await get('/team/api?a=list'); 
    ROSTER = d.roster||[]; 
    render();
  } catch (error) {
    showError('Failed to refresh roster: ' + error.message);
  }
}

function escapeHtml(t=''){ 
  const d = document.createElement('div'); 
  d.textContent = t; 
  return d.innerHTML; 
}

function showError(message) {
  // You could replace this with a toast notification system
  alert('Error: ' + message);
}

function showSuccess(message) {
  // You could replace this with a toast notification system
  alert('Success: ' + message);
}
</script>