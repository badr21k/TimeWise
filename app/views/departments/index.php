<?php require 'app/views/templates/header.php'; 
require 'app/views/templates/spinner.php';
?>

<style>
:root {
  /* Primary Brand Colors */
  --primary: #09194D;
  --primary-light: #1A2A6C;
  --primary-dark: #060F2E;

  /* Secondary Colors */
  --secondary: #D97F76;
  --secondary-light: #E8A8A2;
  --secondary-dark: #C46A61;

  /* Neutral & Background Colors */
  --light: #E4E4EF;
  --lighter: #F4F5F0;
  --neutral: #9B9498;
  --neutral-light: #B8B3B6;
  --neutral-dark: #7A7478;

  /* Accent Colors */
  --accent: #B59E5F;
  --accent-light: #D4C191;
  --accent-dark: #8F7D4C;
  --accent-secondary: #8D77AB;
  --accent-tertiary: #DA70D6;

  /* Semantic Colors */
  --success: #10b981;
  --warning: #f59e0b;
  --danger: #ef4444;
  --info: #3b82f6;

  /* UI Variables */
  --bg: linear-gradient(135deg, var(--lighter) 0%, var(--light) 100%);
  --card: #ffffff;
  --ink: var(--primary);
  --muted: var(--neutral);
  --border: var(--light);
  --ring: var(--accent-light);
  --shadow: 0 8px 32px rgba(9, 25, 77, 0.08);
  --shadow-lg: 0 16px 48px rgba(9, 25, 77, 0.12);
  --shadow-xl: 0 24px 64px rgba(9, 25, 77, 0.15);
  --radius: 24px;
  --radius-sm: 16px;
  --radius-lg: 32px;
}

* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

body {
  background: var(--bg);
  color: var(--ink);
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Inter', sans-serif;
  min-height: 100vh;
  line-height: 1.6;
}

.page-wrap { 
  background: var(--bg); 
  min-height: 100vh; 
  padding: 2rem 0;
  position: relative;
}

.page-wrap::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: 
    radial-gradient(circle at 20% 80%, rgba(181, 158, 95, 0.05) 0%, transparent 50%),
    radial-gradient(circle at 80% 20%, rgba(141, 119, 171, 0.05) 0%, transparent 50%);
  pointer-events: none;
}

.card { 
  background: var(--card);
  border: 2px solid var(--border);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-xl);
  backdrop-filter: blur(20px);
  overflow: hidden;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
}

.card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, var(--accent), var(--accent-secondary), var(--accent-tertiary));
  opacity: 0;
  transition: opacity 0.3s ease;
}

.card:hover::before {
  opacity: 1;
}

.card:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow-xl);
}

.page-header {
  margin-bottom: 2rem;
}

.page-title {
  font-size: 2rem;
  font-weight: 800;
  color: var(--primary);
  margin-bottom: 0.5rem;
  letter-spacing: -0.02em;
}

.page-subtitle {
  color: var(--muted);
  font-size: 1.1rem;
  font-weight: 500;
}

/* Enhanced Buttons */
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: var(--radius-lg);
  padding: 14px 28px;
  font-weight: 600;
  font-size: 0.95rem;
  border: 2px solid transparent;
  cursor: pointer;
  transition: all 0.3s ease;
  gap: 0.5rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  position: relative;
  overflow: hidden;
}

.btn:hover {
  transform: translateY(-2px);
}

.btn-sm {
  padding: 10px 20px;
  font-size: 0.85rem;
}

.btn-primary {
  background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
  color: white;
  box-shadow: 0 8px 32px rgba(9, 25, 77, 0.3);
}

.btn-primary:hover {
  box-shadow: 0 12px 40px rgba(9, 25, 77, 0.4);
}

.btn-outline {
  background: transparent;
  border: 2px solid var(--border);
  color: var(--primary);
}

.btn-outline:hover {
  border-color: var(--accent);
  color: var(--accent);
  background: rgba(181, 158, 95, 0.05);
}

.btn-danger {
  background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
  color: white;
  box-shadow: 0 8px 32px rgba(239, 68, 68, 0.3);
}

.btn-danger:hover {
  box-shadow: 0 12px 40px rgba(239, 68, 68, 0.4);
}

.btn-icon {
  padding: 12px;
  border-radius: var(--radius);
  width: 44px;
  height: 44px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

/* Enhanced Badges */
.badge {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background: linear-gradient(135deg, var(--lighter) 0%, var(--light) 100%);
  border: 2px solid var(--border);
  color: var(--primary);
  border-radius: var(--radius);
  padding: 8px 16px;
  font-size: 0.85rem;
  font-weight: 500;
  margin: 0.25rem 0.5rem 0.25rem 0;
  transition: all 0.3s ease;
}

.badge:hover {
  border-color: var(--accent);
  transform: translateY(-1px);
}

.badge .remove {
  cursor: pointer;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 18px;
  height: 18px;
  border-radius: 50%;
  background: var(--neutral-light);
  color: var(--primary);
  transition: all 0.3s ease;
}

.badge .remove:hover {
  background: var(--danger);
  color: white;
}

/* Department Grid Layout */
.dept-row {
  display: grid;
  grid-template-columns: minmax(180px, 1fr) minmax(250px, 1.5fr) minmax(200px, 1.25fr) minmax(250px, 1.5fr) auto;
  gap: 1.5rem;
  align-items: start;
  padding: 2rem;
  border-bottom: 2px solid var(--border);
  transition: all 0.3s ease;
}

.dept-row:hover {
  background: var(--lighter);
}

.dept-header {
  display: grid;
  grid-template-columns: minmax(180px, 1fr) minmax(250px, 1.5fr) minmax(200px, 1.25fr) minmax(250px, 1.5fr) auto;
  gap: 1.5rem;
  padding: 1.5rem 2rem;
  background: var(--lighter);
  border-bottom: 2px solid var(--border);
  font-weight: 700;
  color: var(--primary);
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.dept-name {
  font-weight: 700;
  color: var(--primary);
  margin-bottom: 0.75rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-size: 1.1rem;
}

.dept-count {
  font-size: 0.9rem;
  color: var(--muted);
  font-weight: 500;
}

/* Enhanced Form Controls */
.input, select {
  width: 100%;
  border: 2px solid var(--border);
  border-radius: var(--radius-sm);
  padding: 14px 20px;
  font-size: 0.95rem;
  font-weight: 500;
  transition: all 0.3s ease;
  background: var(--lighter);
  color: var(--primary);
}

.input:focus, select:focus {
  outline: none;
  border-color: var(--accent);
  box-shadow: 0 0 0 4px rgba(181, 158, 95, 0.15);
  background: white;
}

.input::placeholder {
  color: var(--neutral-light);
  font-weight: 400;
}

.input-group {
  display: flex;
  gap: 0.75rem;
  margin-top: 1rem;
}

.input-group .input {
  flex: 1;
}

.select-multi {
  display: flex;
  flex-wrap: wrap;
  gap: 0.75rem;
  margin-bottom: 1rem;
}

.small-text {
  font-size: 0.85rem;
  color: var(--muted);
  margin-top: 0.5rem;
  font-weight: 500;
}

/* Empty State */
.empty-state {
  padding: 4rem 2rem;
  text-align: center;
  color: var(--muted);
}

.empty-state-icon {
  font-size: 3rem;
  margin-bottom: 1.5rem;
  opacity: 0.7;
}

.empty-state-text {
  margin-bottom: 2rem;
  font-size: 1.1rem;
  font-weight: 500;
}

/* Animations */
.fade-in {
  animation: fadeIn 0.4s ease-out;
}

@keyframes fadeIn {
  from { 
    opacity: 0; 
    transform: translateY(20px); 
  }
  to { 
    opacity: 1; 
    transform: translateY(0); 
  }
}

@keyframes float {
  0%, 100% { 
    transform: translateY(0px); 
  }
  50% { 
    transform: translateY(-8px); 
  }
}

.loading-shimmer {
  background: linear-gradient(90deg, var(--light) 25%, var(--lighter) 50%, var(--light) 75%);
  background-size: 200% 100%;
  animation: loading 1.5s infinite;
  border-radius: var(--radius-sm);
  height: 1rem;
  margin-bottom: 0.75rem;
}

@keyframes loading {
  0% { background-position: 200% 0; }
  100% { background-position: -200% 0; }
}

/* Responsive styles */
@media (max-width: 1024px) {
  .dept-row, .dept-header {
    grid-template-columns: 1fr 1fr;
    grid-template-areas: 
      "name actions"
      "roles managers"
      "members members";
    gap: 1.5rem;
  }
  
  .dept-row > div:nth-child(1) { grid-area: name; }
  .dept-row > div:nth-child(2) { grid-area: roles; }
  .dept-row > div:nth-child(3) { grid-area: managers; }
  .dept-row > div:nth-child(4) { grid-area: members; }
  .dept-row > div:nth-child(5) { grid-area: actions; }
  
  .dept-header > div:nth-child(1) { grid-area: name; }
  .dept-header > div:nth-child(2) { grid-area: roles; }
  .dept-header > div:nth-child(3) { grid-area: managers; }
  .dept-header > div:nth-child(4) { grid-area: members; }
  .dept-header > div:nth-child(5) { grid-area: actions; }
}

@media (max-width: 768px) {
  .page-wrap {
    padding: 1rem 0;
  }
  
  .card-body {
    padding: 1.5rem;
  }
  
  .dept-row, .dept-header {
    grid-template-columns: 1fr;
    grid-template-areas: 
      "name"
      "roles"
      "managers"
      "members"
      "actions";
    gap: 1rem;
    padding: 1.5rem;
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
  
  .page-title {
    font-size: 1.75rem;
  }
}

@media (max-width: 480px) {
  .dept-row, .dept-header {
    padding: 1rem;
  }
  
  .input, select {
    padding: 12px 16px;
  }
  
  .empty-state {
    padding: 3rem 1rem;
  }
  
  .page-title {
    font-size: 1.5rem;
  }
}

/* Utility Classes */
.d-flex { display: flex; }
.align-items-center { align-items: center; }
.justify-content-between { justify-content: space-between; }
.justify-content-end { justify-content: flex-end; }
.mt-2 { margin-top: 0.5rem; }
.mb-2 { margin-bottom: 0.5rem; }
</style>

<div class="page-wrap">
  <div class="container-fluid px-3 px-md-4 py-4">
    <div class="d-flex align-items-center justify-content-between page-header">
      <div>
        <h1 class="page-title">Departments & Roles</h1>
        <p class="page-subtitle">Create departments, attach roles, and assign managers</p>
      </div>
      <button class="btn btn-primary" id="btnAddDept">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
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
        <div>Members & Access</div>
        <div>Actions</div>
      </div>
      <div id="deptList"></div>
      <div id="emptyState" class="empty-state" style="display: none;">
        <div class="empty-state-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" viewBox="0 0 16 16">
            <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
          </svg>
        </div>
        <div class="empty-state-text">No departments yet. Create your first department to get started.</div>
        <button class="btn btn-primary" id="btnAddFirstDept">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
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
let IS_VIEW_ONLY = false;
let ACCESS_LEVEL = 1;

/* Init */
document.addEventListener('DOMContentLoaded', async () => {
  await bootstrapData();
  
  const btnAddDept = document.getElementById('btnAddDept');
  const btnAddFirstDept = document.getElementById('btnAddFirstDept');
  
  btnAddDept.addEventListener('click', onAddDept);
  btnAddFirstDept.addEventListener('click', onAddDept);
  
  if (IS_VIEW_ONLY) {
    btnAddDept.style.display = 'none';
    btnAddFirstDept.style.display = 'none';
    
    const subtitle = document.querySelector('.page-subtitle');
    if (subtitle) {
      subtitle.textContent = 'View departments and roles (Read-only access for Level 4 users)';
    }
  }
});

async function bootstrapData() {
  try {
    const data = await fetchJSON('/departments/api?a=bootstrap');
    ALL_ROLES = data.roles || [];
    ALL_USERS = data.users || [];
    DEPTS     = data.departments || [];
    IS_VIEW_ONLY = data.is_view_only || false;
    ACCESS_LEVEL = data.access_level || 1;
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
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16" style="color: var(--accent);">
      <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5z"/>
    </svg>
    ${escapeHtml(d.name)}
  `;
  const count = document.createElement('div');
  count.className = 'dept-count';
  count.textContent = `${d.role_count} role${d.role_count !== 1 ? 's' : ''}`;
  
  // Add "View Only" badge if not editable
  if (d.editable === false) {
    const badge = document.createElement('span');
    badge.className = 'badge';
    badge.style.cssText = 'background: var(--gray-400); color: white; font-size: 0.7rem; margin-left: 0.5rem;';
    badge.textContent = 'View Only';
    count.appendChild(badge);
  }
  
  if (d.editable !== false) {
    const rename = document.createElement('button');
    rename.className = 'btn btn-outline btn-sm mt-2';
    rename.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
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
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16" style="color: var(--accent);">
            <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5z"/>
          </svg>
          ${escapeHtml(d.name)}
        `;
      } catch (error) {
        alert('Failed to rename department: ' + error.message);
      }
    });
    left.append(name, count, rename);
  } else {
    left.append(name, count);
  }

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
  addBtn.className='btn btn-primary btn-sm';
  addBtn.innerHTML = `
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
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
  addManagerBtn.className = 'btn btn-primary btn-sm';
  addManagerBtn.innerHTML = `
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
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

  /* members section with access level management */
  const membersCol = document.createElement('div');
  const membersBox = document.createElement('div');
  membersBox.className = 'select-multi';
  membersBox.style.fontSize = '0.875rem';
  membersCol.appendChild(membersBox);

  /* delete */
  const delCol = document.createElement('div');
  delCol.className = 'd-flex justify-content-end';
  
  if (d.editable !== false) {
    const del = document.createElement('button');
    del.className = 'btn btn-icon btn-danger';
    del.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
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
  }
  
  row.append(left, mid, right, membersCol, delCol);

  // initial role, manager, and member chips with loading state
  showLoadingRoles(chips);
  showLoadingManagers(mgrBox);
  showLoadingMembers(membersBox);
  
  // load actual data
  refreshRoles(d.id, chips, count, d);
  refreshManagers(d.id, mgrBox);
  refreshMembers(d.id, membersBox);

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

function showLoadingMembers(container) {
  container.innerHTML = `
    <div class="loading-shimmer" style="width: 75%"></div>
  `;
}

async function refreshMembers(deptId, boxEl) {
  try {
    const members = await fetchJSON(`/departments/api?a=members.list&dept_id=${deptId}`);
    boxEl.innerHTML = '';
    
    if (members && members.length > 0) {
      members.forEach(m => {
        const memberDiv = document.createElement('div');
        memberDiv.style.cssText = 'margin-bottom: 0.75rem; padding: 0.5rem; background: var(--lighter); border-radius: var(--radius-sm);';
        
        const nameDiv = document.createElement('div');
        nameDiv.style.cssText = 'font-weight: 500; margin-bottom: 0.25rem;';
        nameDiv.textContent = m.label;
        
        const accessDiv = document.createElement('div');
        accessDiv.style.cssText = 'display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem;';
        
        const levelLabel = document.createElement('span');
        levelLabel.textContent = 'Level:';
        levelLabel.style.color = 'var(--text-secondary)';
        
        if (IS_VIEW_ONLY) {
          const levelValue = document.createElement('span');
          levelValue.style.cssText = 'font-weight: 600; color: var(--accent);';
          levelValue.textContent = m.access_level;
          accessDiv.append(levelLabel, levelValue);
        } else {
          const levelSelect = document.createElement('select');
          levelSelect.className = 'input';
          levelSelect.style.cssText = 'padding: 0.25rem 0.5rem; font-size: 0.8rem; width: 50px;';
          levelSelect.innerHTML = `
            <option value="0" ${m.access_level === 0 ? 'selected' : ''}>0</option>
            <option value="1" ${m.access_level === 1 ? 'selected' : ''}>1</option>
            <option value="2" ${m.access_level === 2 ? 'selected' : ''}>2</option>
            <option value="3" ${m.access_level === 3 ? 'selected' : ''}>3</option>
            <option value="4" ${m.access_level === 4 ? 'selected' : ''}>4</option>
          `;
          
          levelSelect.addEventListener('change', async () => {
            const newLevel = parseInt(levelSelect.value, 10);
            if (!confirm(`Change ${m.label}'s access level to ${newLevel}?`)) {
              levelSelect.value = m.access_level;
              return;
            }
            
            try {
              await fetchJSON('/departments/api?a=member.update_access', {
                method: 'POST',
                body: JSON.stringify({ user_id: m.id, access_level: newLevel })
              });
              m.access_level = newLevel;
              alert(`Access level updated to ${newLevel}`);
            } catch (error) {
              levelSelect.value = m.access_level;
              alert('Failed to update access level: ' + error.message);
            }
          });
          
          accessDiv.append(levelLabel, levelSelect);
        }
        
        memberDiv.append(nameDiv, accessDiv);
        boxEl.appendChild(memberDiv);
      });
    } else {
      boxEl.innerHTML = '<span class="small-text">No members in this department</span>';
    }
  } catch (error) {
    boxEl.innerHTML = '<span class="small-text" style="color: var(--danger)">Failed to load members</span>';
    console.error('Failed to refresh members:', error);
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