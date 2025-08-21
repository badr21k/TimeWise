<?php require 'app/views/templates/header.php'; ?>
<style>
.page-wrap { background:#f8fafc; min-height:100vh; }
.card      { background:#fff; border-radius:.75rem; box-shadow:0 1px 3px rgba(0,0,0,.08); }
.h1 { font-size:1.5rem; font-weight:700; color:#111827; }
.sub { color:#6b7280; }
.btn { border-radius:.5rem; padding:.5rem .9rem; font-weight:600; border:1px solid #d1d5db; background:#f9fafb; }
.btn.primary { background:#3b82f6; color:#fff; border-color:#3b82f6; }
.btn.danger  { background:#fee2e2; border-color:#fecaca; color:#b91c1c; }
.badge { display:inline-flex; align-items:center; gap:.4rem; background:#eef2ff; border:1px solid #c7d2fe; color:#3730a3; border-radius:9999px; padding:.25rem .55rem; font-size:.8rem; margin:.2rem .2rem 0 0; }
.badge .x { cursor:pointer; font-weight:700; }
.row { display:grid; grid-template-columns: 260px 1fr 320px 40px; gap:1rem; align-items:start; padding:1rem; border-bottom:1px solid #e5e7eb; }
.row .title { font-weight:600; color:#111827; }
.input, select { width:100%; border:1px solid #d1d5db; border-radius:.5rem; padding:.5rem .65rem; }
.select-multi { display:flex; flex-wrap:wrap; gap:.35rem; }
.select-multi select { width:auto; min-width:200px; }
.header-row { display:grid; grid-template-columns: 260px 1fr 320px 40px; gap:1rem; padding:.75rem 1rem; background:#f9fafb; border-bottom:1px solid #e5e7eb; font-weight:700; color:#374151; }
.small { font-size:.8rem; color:#6b7280; }
</style>

<div class="page-wrap">
  <div class="container-fluid px-4 py-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <div>
        <div class="h1">Departments & Roles</div>
        <div class="sub">Create departments, attach roles, and assign managers (from users)</div>
      </div>
      <button class="btn primary" id="btnAddDept">Add new department</button>
    </div>

    <div class="card">
      <div class="header-row">
        <div>Department</div>
        <div>Roles <span class="small">(type to create or pick existing)</span></div>
        <div>Managers <span class="small">(users)</span></div>
        <div></div>
      </div>
      <div id="deptList"></div>
    </div>
  </div>
</div>

<?php require 'app/views/templates/footer.php'; ?>

<script>
async function fetchJSON(url, options={}) {
  const res  = await fetch(url, { headers:{'Content-Type':'application/json'}, ...options });
  const text = await res.text();
  if (!res.ok) throw new Error(text || ('HTTP ' + res.status));
  if (!/application\/json/.test(res.headers.get('content-type')||'')) throw new Error('Not JSON');
  return JSON.parse(text);
}

/* State */
let ALL_ROLES = [];
let ALL_USERS = [];
let DEPTS     = [];

/* Init */
document.addEventListener('DOMContentLoaded', async () => {
  await bootstrapData();
  document.getElementById('btnAddDept').addEventListener('click', onAddDept);
});

async function bootstrapData() {
  const data = await fetchJSON('/departments/api?a=bootstrap');
  ALL_ROLES = data.roles || [];
  ALL_USERS = data.users || [];
  DEPTS     = data.departments || [];
  renderList();
}

function renderList() {
  const wrap = document.getElementById('deptList');
  wrap.innerHTML = '';
  DEPTS.forEach(d => wrap.appendChild(deptRow(d)));
}

/* Row component */
function deptRow(d) {
  const row = document.createElement('div');
  row.className = 'row';
  row.dataset.id = d.id;

  /* left: name + rename */
  const left = document.createElement('div');
  const name = document.createElement('div');
  name.className = 'title';
  name.textContent = `${d.name} (${d.role_count})`;
  const rename = document.createElement('button');
  rename.className = 'btn btn-sm mt-2';
  rename.textContent = 'Rename';
  rename.addEventListener('click', async () => {
    const nn = prompt('New name', d.name);
    if (!nn || nn === d.name) return;
    await fetchJSON('/departments/api?a=department.rename', { method:'POST', body: JSON.stringify({ id:d.id, name: nn }) });
    d.name = nn; name.textContent = `${d.name} (${d.role_count})`;
  });
  left.append(name, rename);

  /* middle: roles (chips + add input) */
  const mid = document.createElement('div');
  const chips = document.createElement('div'); chips.className='mb-2';
  mid.appendChild(chips);

  const addWrap = document.createElement('div');
  addWrap.className = 'd-flex gap-2 align-items-center';
  const sel = document.createElement('select');
  sel.className = 'input';
  sel.innerHTML = `<option value="">Pick existing role…</option>` + ALL_ROLES.map(r=>`<option value="${r.id}">${escapeHtml(r.name)}</option>`).join('');
  const txt = document.createElement('input'); txt.className='input'; txt.placeholder='…or type a new role name and press Add';
  const addBtn = document.createElement('button'); addBtn.className='btn'; addBtn.textContent='Add';
  addBtn.addEventListener('click', async () => {
    if (!sel.value && !txt.value.trim()) return;
    if (txt.value.trim()) {
      await fetchJSON('/departments/api?a=role.attach', { method:'POST', body: JSON.stringify({ department_id: d.id, role_name: txt.value.trim() }) });
      txt.value='';
    } else {
      await fetchJSON('/departments/api?a=role.attach', { method:'POST', body: JSON.stringify({ department_id: d.id, role_id: parseInt(sel.value,10) }) });
      sel.value='';
    }
    await refreshRoles(d.id, chips, name, d);
  });
  addWrap.append(sel, txt, addBtn);
  mid.appendChild(addWrap);

  /* right: managers multi-select */
  const right = document.createElement('div');
  const mgrBox = document.createElement('div'); mgrBox.className='select-multi mb-2';
  right.appendChild(mgrBox);

  const choose = document.createElement('select'); choose.className='input';
  choose.innerHTML = `<option value="">Select user…</option>` + ALL_USERS.map(u=>`<option value="${u.id}">${escapeHtml(u.label)}</option>`).join('');
  choose.addEventListener('change', async () => {
    const uid = parseInt(choose.value,10);
    if (!uid) return;
    await fetchJSON('/departments/api?a=manager.add', { method:'POST', body: JSON.stringify({ department_id: d.id, user_id: uid }) });
    choose.value = '';
    await refreshManagers(d.id, mgrBox);
  });
  right.appendChild(choose);

  /* delete */
  const delCol = document.createElement('div');
  const del = document.createElement('button');
  del.className = 'btn danger';
  del.textContent = '🗑';
  del.title = 'Delete department';
  del.addEventListener('click', async () => {
    if (!confirm('Delete this department?')) return;
    await fetchJSON(`/departments/api?a=department.delete&id=${d.id}`);
    DEPTS = DEPTS.filter(x=>x.id!==d.id);
    renderList();
  });
  delCol.appendChild(del);

  row.append(left, mid, right, delCol);

  // initial role & manager chips
  refreshRoles(d.id, chips, name, d);
  refreshManagers(d.id, mgrBox);

  return row;
}

async function refreshRoles(deptId, chipsEl, nameEl, deptRef)
{
  chipsEl.innerHTML = '<span class="small">Loading roles…</span>';
  const r = await fetchJSON(`/departments/api?a=departments.roles.managers&id=${deptId}`);
  chipsEl.innerHTML = '';
  (r.roles || []).forEach(role => {
    const b = document.createElement('span'); b.className='badge';
    b.innerHTML = `${escapeHtml(role.name)} <span class="x" title="Remove">×</span>`;
    b.querySelector('.x').addEventListener('click', async () => {
      await fetchJSON('/departments/api?a=role.detach', { method:'POST', body: JSON.stringify({ department_id: deptId, role_id: role.id }) });
      await refreshRoles(deptId, chipsEl, nameEl, deptRef);
    });
    chipsEl.appendChild(b);
  });
  deptRef.role_count = (r.roles || []).length;
  nameEl.textContent = `${deptRef.name} (${deptRef.role_count})`;
}

async function refreshManagers(deptId, boxEl)
{
  boxEl.innerHTML = '<span class="small">Loading managers…</span>';
  const r = await fetchJSON(`/departments/api?a=departments.roles.managers&id=${deptId}`);
  boxEl.innerHTML = '';
  (r.managers || []).forEach(m => {
    const b = document.createElement('span'); b.className='badge';
    b.innerHTML = `${escapeHtml(m.label)} <span class="x" title="Remove">×</span>`;
    b.querySelector('.x').addEventListener('click', async () => {
      await fetchJSON('/departments/api?a=manager.remove', { method:'POST', body: JSON.stringify({ department_id: deptId, user_id: m.id }) });
      await refreshManagers(deptId, boxEl);
    });
    boxEl.appendChild(b);
  });
}

async function onAddDept() {
  const n = prompt('Department name');
  if (!n) return;
  const res = await fetchJSON('/departments/api?a=department.create', { method:'POST', body: JSON.stringify({ name:n }) });
  DEPTS.push({ id: res.id, name:n, role_count:0 });
  renderList();
}

function escapeHtml(t=''){ const d=document.createElement('div'); d.textContent=t; return d.innerHTML; }
</script>
