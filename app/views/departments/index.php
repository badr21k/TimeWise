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

.card { 
  background: #fff; 
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow);
  overflow: hidden;
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

.btn-icon {
  padding: 0.5rem;
  border-radius: var(--radius);
}

.badge {
  display: inline-flex;
  align-items: center;
  gap: 0.375rem;
  background: #eef2ff;
  border: 1px solid #c7d2fe;
  color: #3730a3;
  border-radius: 9999px;
  padding: 0.25rem 0.625rem;
  font-size: 0.75rem;
  margin: 0.25rem 0.25rem 0.25rem 0;
  transition: var(--transition);
}

.badge .remove {
  cursor: pointer;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 16px;
  height: 16px;
  border-radius: 50%;
  background: rgba(0,0,0,0.1);
}

.badge .remove:hover {
  background: rgba(0,0,0,0.2);
}

.dept-row {
  display: grid;
  grid-template-columns: minmax(200px, 1fr) minmax(300px, 2fr) minmax(250px, 1.5fr) auto;
  gap: 1.5rem;
  align-items: start;
  padding: 1.5rem;
  border-bottom: 1px solid var(--gray-200);
  transition: var(--transition);
}

.dept-row:hover {
  background: var(--gray-50);
}

.dept-header {
  display: grid;
  grid-template-columns: minmax(200px, 1fr) minmax(300px, 2fr) minmax(250px, 1.5fr) auto;
  gap: 1.5rem;
  padding: 1rem 1.5rem;
  background: var(--gray-50);
  border-bottom: 1px solid var(--gray-200);
  font-weight: 600;
  color: var(--gray-700);
  font-size: 0.875rem;
  text-transform: uppercase;
  letter-spacing: 0.025em;
}

.dept-name {
  font-weight: 600;
  color: var(--gray-900);
  margin-bottom: 0.5rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.dept-count {
  font-size: 0.875rem;
  color: var(--gray-500);
}

.input, select {
  width: 100%;
  border: 1px solid var(--gray-300);
  border-radius: var(--radius);
  padding: 0.625rem 0.875rem;
  font-size: 0.875rem;
  transition: var(--transition);
}

.input:focus, select:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
}

.input-group {
  display: flex;
  gap: 0.5rem;
  margin-top: 0.75rem;
}

.input-group .input {
  flex: 1;
}

.select-multi {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-bottom: 0.75rem;
}

.small-text {
  font-size: 0.75rem;
  color: var(--gray-500);
  margin-top: 0.25rem;
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

/* Responsive styles */
@media (max-width: 1024px) {
  .dept-row, .dept-header {
    grid-template-columns: 1fr 1fr;
    grid-template-areas: 
      "name actions"
      "roles managers";
    gap: 1rem;
  }
  
  .dept-row > div:nth-child(1) { grid-area: name; }
  .dept-row > div:nth-child(2) { grid-area: roles; }
  .dept-row > div:nth-child(3) { grid-area: managers; }
  .dept-row > div:nth-child(4) { grid-area: actions; }
  
  .dept-header > div:nth-child(1) { grid-area: name; }
  .dept-header > div:nth-child(2) { grid-area: roles; }
  .dept-header > div:nth-child(3) { grid-area: managers; }
  .dept-header > div:nth-child(4) { grid-area: actions; }
}

@media (max-width: 640px) {
  .dept-row, .dept-header {
    grid-template-columns: 1fr;
    grid-template-areas: 
      "name"
      "roles"
      "managers"
      "actions";
  }
  
  .input-group {
    flex-direction: column;
  }
  
  .page-header {
    flex-direction: column;
    align-items: flex-start !important;
    gap: 1rem;
  }
  
  .btn {
    width: 100%;
    justify-content: center;
  }
}

.fade-in {
  animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
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
</style>

<div class="page-wrap">
  <div class="container-fluid px-3 px-md-4 py-4">
    <div class="d-flex align-items-center justify-content-between page-header">
      <div>
        <h1 class="page-title">Departments & Roles</h1>
        <p class="page-subtitle">Create departments, attach roles, and assign managers</p>
      </div>
      <button class="btn btn-primary" id="btnAddDept">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
          <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
        </svg>
        Add Department
      </button>
    </div>

    <div class="card">
      <div class="dept-header">
        <div>Department</div>
        <div>Roles</div>
        <div>Managers</div>
        <div>Actions</div>
      </div>
      <div id="deptList"></div>
      <div id="emptyState" class="empty-state" style="display: none;">
        <div class="empty-state-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" viewBox="0 0 16 16">
            <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
          </svg>
        </div>
        <div class="empty-state-text">No departments yet. Create your first department to get started.</div>
        <button class="btn btn-primary" id="btnAddFirstDept">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
          </svg>
          Add Department
        </button>
      </div>
    </div>
  </div>
</div>

<?php require 'app/views/templates/footer.php'; ?>

<script>
/* ===== Spinner-aware JSON fetch ===== */
async function fetchJSON(url, options = {}) {
  Spinner.show();
  try {
    const res  = await fetch(url, { headers:{'Content-Type':'application/json'}, ...options });
    const text = await res.text();
    if (!res.ok) throw new Error(text || ('HTTP ' + res.status));
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

/* State */
let ALL_ROLES = [];
let ALL_USERS = [];
let DEPTS     = [];

/* Init */
document.addEventListener('DOMContentLoaded', async () => {
  await bootstrapData();
  document.getElementById('btnAddDept').addEventListener('click', onAddDept);
  document.getElementById('btnAddFirstDept').addEventListener('click', onAddDept);
});

async function bootstrapData() {
  try {
    const data = await fetchJSON('/departments/api?a=bootstrap');
    ALL_ROLES = data.roles || [];
    ALL_USERS = data.users || [];
    DEPTS     = data.departments || [];
    renderList();
  } catch (error) {
    console.error('Failed to load data:', error);
    alert('Failed to load data. Please refresh the page.');
  }
}

function renderList() {
  const wrap = document.getElementById('deptList');
  const emptyState = document.getElementById('emptyState');
  
  if (DEPTS.length === 0) {
    wrap.innerHTML = '';
    emptyState.style.display = 'block';
    return;
  }
  
  emptyState.style.display = 'none';
  wrap.innerHTML = '';
  
  DEPTS.forEach(d => {
    const row = deptRow(d);
    row.classList.add('fade-in');
    wrap.appendChild(row);
  });
}

/* Row component */
function deptRow(d) {
  const row = document.createElement('div');
  row.className = 'dept-row';
  row.dataset.id = d.id;

  /* left: name + rename */
  const left = document.createElement('div');
  const name = document.createElement('div');
  name.className = 'dept-name';
  name.innerHTML = `
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
      <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5z"/>
    </svg>
    ${escapeHtml(d.name)}
  `;
  const count = document.createElement('div');
  count.className = 'dept-count';
  count.textContent = `${d.role_count} role${d.role_count !== 1 ? 's' : ''}`;
  const rename = document.createElement('button');
  rename.className = 'btn btn-outline btn-sm mt-2';
  rename.innerHTML = `
    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
      <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
    </svg>
    Rename
  `;
  rename.addEventListener('click', async () => {
    const nn = prompt('New department name', d.name);
    if (!nn || nn === d.name) return;
    try {
      await fetchJSON('/departments/api?a=department.rename', { 
        method:'POST', 
        body: JSON.stringify({ id:d.id, name: nn }) 
      });
      d.name = nn; 
      name.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
          <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5z"/>
        </svg>
        ${escapeHtml(d.name)}
      `;
    } catch (error) {
      alert('Failed to rename department: ' + error.message);
    }
  });
  left.append(name, count, rename);

  /* middle: roles (chips + add input) */
  const mid = document.createElement('div');
  const chips = document.createElement('div'); 
  chips.className='mb-2';
  mid.appendChild(chips);

  const addWrap = document.createElement('div');
  addWrap.className = 'input-group';
  const sel = document.createElement('select');
  sel.className = 'input';
  sel.innerHTML = `<option value="">Select existing role</option>` + 
    ALL_ROLES.map(r => `<option value="${r.id}">${escapeHtml(r.name)}</option>`).join('');
  
  const txt = document.createElement('input'); 
  txt.className='input'; 
  txt.placeholder='Or enter new role name';
  
  const addBtn = document.createElement('button'); 
  addBtn.className='btn btn-primary';
  addBtn.innerHTML = `
    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
      <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
    </svg>
  `;
  
  addBtn.addEventListener('click', async () => {
    if (!sel.value && !txt.value.trim()) {
      alert('Please select an existing role or enter a new role name');
      return;
    }
    
    try {
      if (txt.value.trim()) {
        await fetchJSON('/departments/api?a=role.attach', { 
          method:'POST', 
          body: JSON.stringify({ department_id: d.id, role_name: txt.value.trim() }) 
        });
        txt.value='';
      } else {
        await fetchJSON('/departments/api?a=role.attach', { 
          method:'POST', 
          body: JSON.stringify({ department_id: d.id, role_id: parseInt(sel.value,10) }) 
        });
        sel.value='';
      }
      await refreshRoles(d.id, chips, count, d);
    } catch (error) {
      alert('Failed to add role: ' + error.message);
    }
  });
  
  addWrap.append(sel, txt, addBtn);
  mid.appendChild(addWrap);

  /* right: managers multi-select */
  const right = document.createElement('div');
  const mgrBox = document.createElement('div'); 
  mgrBox.className='select-multi';
  right.appendChild(mgrBox);

  const chooseWrap = document.createElement('div');
  chooseWrap.className = 'input-group';
  const choose = document.createElement('select'); 
  choose.className='input';
  choose.innerHTML = `<option value="">Select user to add as manager</option>` + 
    ALL_USERS.map(u => `<option value="${u.id}">${escapeHtml(u.label)}</option>`).join('');
  
  const addManagerBtn = document.createElement('button');
  addManagerBtn.className = 'btn btn-primary';
  addManagerBtn.innerHTML = `
    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
      <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
    </svg>
  `;
  
  chooseWrap.appendChild(choose);
  chooseWrap.appendChild(addManagerBtn);
  right.appendChild(chooseWrap);

  addManagerBtn.addEventListener('click', async () => {
    const uid = parseInt(choose.value,10);
    if (!uid) {
      alert('Please select a user');
      return;
    }
    
    try {
      await fetchJSON('/departments/api?a=manager.add', { 
        method:'POST', 
        body: JSON.stringify({ department_id: d.id, user_id: uid }) 
      });
      choose.value = '';
      await refreshManagers(d.id, mgrBox);
    } catch (error) {
      alert('Failed to add manager: ' + error.message);
    }
  });

  /* delete */
  const delCol = document.createElement('div');
  delCol.className = 'd-flex justify-content-end';
  
  const del = document.createElement('button');
  del.className = 'btn btn-icon btn-danger';
  del.innerHTML = `
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
      <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
      <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
    </svg>
  `;
  del.title = 'Delete department';
  
  del.addEventListener('click', async () => {
    if (!confirm('Are you sure you want to delete this department? This action cannot be undone.')) return;
    
    try {
      await fetchJSON(`/departments/api?a=department.delete&id=${d.id}`);
      DEPTS = DEPTS.filter(x => x.id !== d.id);
      renderList();
    } catch (error) {
      alert('Failed to delete department: ' + error.message);
    }
  });
  
  delCol.appendChild(del);
  row.append(left, mid, right, delCol);

  // initial role & manager chips with loading state
  showLoadingRoles(chips);
  showLoadingManagers(mgrBox);
  
  // load actual data
  refreshRoles(d.id, chips, count, d);
  refreshManagers(d.id, mgrBox);

  return row;
}

function showLoadingRoles(container) {
  container.innerHTML = `
    <div class="loading-shimmer" style="width: 80%"></div>
    <div class="loading-shimmer" style="width: 60%"></div>
  `;
}

function showLoadingManagers(container) {
  container.innerHTML = `
    <div class="loading-shimmer" style="width: 70%"></div>
  `;
}

async function refreshRoles(deptId, chipsEl, countEl, deptRef) {
  try {
    const r = await fetchJSON(`/departments/api?a=departments.roles.managers&id=${deptId}`);
    chipsEl.innerHTML = '';
    
    if (r.roles && r.roles.length > 0) {
      r.roles.forEach(role => {
        const b = document.createElement('span'); 
        b.className='badge';
        b.innerHTML = `
          ${escapeHtml(role.name)} 
          <span class="remove" title="Remove role">×</span>
        `;
        b.querySelector('.remove').addEventListener('click', async () => {
          if (!confirm(`Remove role "${role.name}" from department?`)) return;
          
          try {
            await fetchJSON('/departments/api?a=role.detach', { 
              method:'POST', 
              body: JSON.stringify({ department_id: deptId, role_id: role.id }) 
            });
            await refreshRoles(deptId, chipsEl, countEl, deptRef);
          } catch (error) {
            alert('Failed to remove role: ' + error.message);
          }
        });
        chipsEl.appendChild(b);
      });
    } else {
      chipsEl.innerHTML = '<span class="small-text">No roles added yet</span>';
    }
    
    deptRef.role_count = (r.roles || []).length;
    countEl.textContent = `${deptRef.role_count} role${deptRef.role_count !== 1 ? 's' : ''}`;
  } catch (error) {
    chipsEl.innerHTML = '<span class="small-text" style="color: var(--danger)">Failed to load roles</span>';
    console.error('Failed to refresh roles:', error);
  }
}

async function refreshManagers(deptId, boxEl) {
  try {
    const r = await fetchJSON(`/departments/api?a=departments.roles.managers&id=${deptId}`);
    boxEl.innerHTML = '';
    
    if (r.managers && r.managers.length > 0) {
      r.managers.forEach(m => {
        const b = document.createElement('span'); 
        b.className='badge';
        b.innerHTML = `
          ${escapeHtml(m.label)} 
          <span class="remove" title="Remove manager">×</span>
        `;
        b.querySelector('.remove').addEventListener('click', async () => {
          if (!confirm(`Remove "${m.label}" as manager?`)) return;
          
          try {
            await fetchJSON('/departments/api?a=manager.remove', { 
              method:'POST', 
              body: JSON.stringify({ department_id: deptId, user_id: m.id }) 
            });
            await refreshManagers(deptId, boxEl);
          } catch (error) {
            alert('Failed to remove manager: ' + error.message);
          }
        });
        boxEl.appendChild(b);
      });
    } else {
      boxEl.innerHTML = '<span class="small-text">No managers assigned</span>';
    }
  } catch (error) {
    boxEl.innerHTML = '<span class="small-text" style="color: var(--danger)">Failed to load managers</span>';
    console.error('Failed to refresh managers:', error);
  }
}

async function onAddDept() {
  const n = prompt('Enter department name:');
  if (!n) return;
  
  if (!n.trim()) {
    alert('Department name cannot be empty');
    return;
  }
  
  try {
    const res = await fetchJSON('/departments/api?a=department.create', { 
      method:'POST', 
      body: JSON.stringify({ name: n.trim() }) 
    });
    DEPTS.push({ id: res.id, name: n.trim(), role_count: 0 });
    renderList();
  } catch (error) {
    alert('Failed to create department: ' + error.message);
  }
}

function escapeHtml(t = '') { 
  const d = document.createElement('div'); 
  d.textContent = t; 
  return d.innerHTML; 
}
</script>