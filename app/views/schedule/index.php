<?php
// use your existing header/footer from views/templates
require_once VIEWS . DS . 'templates' . DS . 'header.php';
?>

<div class="container" style="max-width:1200px">
  <h1 class="mb-3">Team & Schedule</h1>

  <ul class="nav nav-tabs" id="schedTabs" role="tablist">
    <li class="nav-item" role="presentation"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pane-emps" type="button">Employees</button></li>
    <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-depts" type="button">Departments & Roles</button></li>
    <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-week" type="button">Week Schedule</button></li>
    <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-admins" type="button">Admins</button></li>
    <li class="nav-item ms-auto">
      <div class="d-flex align-items-center gap-2 px-2">
        <input id="weekInput" type="date" class="form-control form-control-sm">
        <button id="publishBtn" class="btn btn-sm btn-primary">Publish week</button>
        <span id="pubStatus" class="badge bg-secondary">…</span>
      </div>
    </li>
  </ul>

  <div class="tab-content border border-top-0 p-3 rounded-bottom">

    <!-- Employees -->
    <div class="tab-pane fade show active" id="pane-emps">
      <div class="card p-3 mb-3 admin-only">
        <div class="row g-2">
          <div class="col-md-4"><input id="empName" class="form-control" placeholder="Full name"></div>
          <div class="col-md-4"><input id="empEmail" class="form-control" placeholder="Email (optional)"></div>
          <div class="col-md-3"><input id="empRole" class="form-control" placeholder="Role/Position (e.g., Support Worker)"></div>
          <div class="col-md-1 d-grid"><button id="addEmpBtn" class="btn btn-success">Add</button></div>
        </div>
      </div>
      <div id="empList" class="row gy-2"></div>
    </div>

    <!-- Departments & Roles -->
    <div class="tab-pane fade" id="pane-depts">
      <div class="row">
        <div class="col-md-6">
          <h5>Departments</h5>
          <div class="input-group mb-2 admin-only">
            <input id="deptName" class="form-control" placeholder="Add department">
            <button id="addDeptBtn" class="btn btn-outline-primary">Add</button>
          </div>
          <ul id="deptList" class="list-group"></ul>
        </div>
        <div class="col-md-6">
          <h5>Roles</h5>
          <div class="input-group mb-2 admin-only">
            <input id="roleName" class="form-control" placeholder="Add role">
            <button id="addRoleBtn" class="btn btn-outline-primary">Add</button>
          </div>
          <ul id="roleList" class="list-group mb-3"></ul>

          <div class="card p-2">
            <div class="mb-2"><strong>Attach roles to department</strong></div>
            <div class="row g-2">
              <div class="col-6"><select id="mapDept" class="form-select"></select></div>
              <div class="col-6"><select id="mapRole" class="form-select"></select></div>
              <div class="col-12 d-grid admin-only"><button id="attachRoleBtn" class="btn btn-primary">Attach</button></div>
            </div>
            <div class="mt-3">
              <div id="deptRolesChips" class="d-flex flex-wrap gap-2"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Week Schedule -->
    <div class="tab-pane fade" id="pane-week">
      <div class="table-responsive">
        <table class="table table-sm align-top" id="schedTable">
          <thead class="table-light"></thead>
          <tbody></tbody>
        </table>
      </div>
      <div class="alert alert-info mt-2 d-none" id="roNote">You are not an admin. Schedule is read-only.</div>
    </div>

    <!-- Admins -->
    <div class="tab-pane fade" id="pane-admins">
      <div class="alert alert-secondary">Only admins can change admin rights.</div>
      <div id="adminList"></div>
    </div>

  </div>
</div>

<?php
require_once VIEWS . DS . 'templates' . DS . 'footer.php';
?>

<script>
const api = (a, opts={}) => fetch(`/schedule/api?a=${encodeURIComponent(a)}`, {headers:{'Content-Type':'application/json'}, ...opts}).then(r=>r.json());
const $  = s => document.querySelector(s);
const $$ = s => Array.from(document.querySelectorAll(s));
const esc = s => (s??'').toString().replace(/[&<>"']/g, m=>({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;', "'":'&#39;' }[m]) );

let is_admin = 0, employees=[], roles=[], depts=[], shifts=[], currentWeekStart=null;

document.addEventListener('DOMContentLoaded', init);

async function init(){
  const t = new Date().toISOString().slice(0,10);
  $('#weekInput').value = t;
  is_admin = (await api('me')).is_admin || 0;
  toggleAdminUI();

  await loadEmployees();
  await loadDepts(); await loadRoles();
  refreshDeptRoleMapper();
  await loadWeek();
  await loadAdminsTabOnShow();
  bindEvents();
}

function toggleAdminUI(){ $$('.admin-only').forEach(el => el.style.display = is_admin ? '' : 'none'); $('#roNote').classList.toggle('d-none', !!is_admin); $('#publishBtn').style.display = is_admin ? '' : 'none'; }

function bindEvents(){
  $('#addEmpBtn').onclick = addEmployee;
  $('#weekInput').onchange = loadWeek;
  $('#publishBtn').onclick = togglePublish;

  $('#addDeptBtn').onclick = async ()=>{
    if (!is_admin) return alert('Admin only');
    const name = $('#deptName').value.trim(); if(!name) return;
    await api('departments.create',{method:'POST',body:JSON.stringify({name})});
    $('#deptName').value=''; await loadDepts(); refreshDeptRoleMapper();
  };
  $('#addRoleBtn').onclick = async ()=>{
    if (!is_admin) return alert('Admin only');
    const name = $('#roleName').value.trim(); if(!name) return;
    await api('roles.create',{method:'POST',body:JSON.stringify({name})});
    $('#roleName').value=''; await loadRoles(); refreshDeptRoleMapper();
  };
  $('#attachRoleBtn').onclick = attachRoleToDept;
  document.getElementById('pane-depts').addEventListener('shown.bs.tab', refreshDeptRoleMapper);
}

async function loadEmployees(){
  employees = await api('employees.list');
  const wrap = $('#empList'); wrap.innerHTML='';
  employees.forEach(e=>{
    const col = document.createElement('div'); col.className='col-md-6';
    col.innerHTML = `
      <div class="border rounded p-2 d-flex align-items-center gap-2">
        <div class="flex-grow-1">
          <div class="fw-bold">${esc(e.name)} <span class="text-muted">(${esc(e.role)})</span></div>
          ${e.email ? `<div class="small text-muted">${esc(e.email)}</div>` : ``}
        </div>
        <div class="form-check form-switch admin-only">
          <input class="form-check-input emp-active" type="checkbox" data-id="${e.id}" ${e.is_active ? 'checked':''}>
        </div>
        <button class="btn btn-outline-danger btn-sm emp-del admin-only" data-id="${e.id}">Delete</button>
      </div>`;
    wrap.appendChild(col);
  });
  wrap.onclick = async (ev)=>{
    const del = ev.target.closest('.emp-del');
    if (del){ if(!confirm('Delete employee and shifts?')) return;
      await fetch(`/schedule/api?a=employees.delete&id=${del.dataset.id}`); await loadEmployees(); await loadWeek(); return; }
    const sw = ev.target.closest('.emp-active');
    if (sw){ const id=+sw.dataset.id; await api('employees.update',{method:'POST',body:JSON.stringify({id,is_active: sw.checked?1:0})}); }
  };
}

async function addEmployee(){
  if (!is_admin) return alert('Admin only');
  const name=$('#empName').value.trim(); if(!name) return alert('Name required');
  const email=$('#empEmail').value.trim() || null;
  const role =$('#empRole').value.trim() || 'Staff';
  await api('employees.create',{method:'POST',body:JSON.stringify({name,email,role})});
  $('#empName').value=''; $('#empEmail').value=''; $('#empRole').value='';
  await loadEmployees();
}

async function loadDepts(){
  depts = await api('departments.list');
  const ul = $('#deptList'); ul.innerHTML='';
  depts.forEach(d=>{
    const li=document.createElement('li'); li.className='list-group-item d-flex align-items-center justify-content-between';
    li.innerHTML = `<div><strong>${esc(d.name)}</strong> ${d.is_active?'<span class="badge bg-success">Active</span>':'<span class="badge bg-secondary">Inactive</span>'}</div>
      <div class="admin-only">
        <button class="btn btn-sm btn-outline-secondary me-2" data-act="toggle" data-id="${d.id}" data-active="${d.is_active}">${d.is_active?'Deactivate':'Activate'}</button>
        <button class="btn btn-sm btn-outline-danger" data-act="del" data-id="${d.id}">Delete</button>
      </div>`;
    ul.appendChild(li);
  });
  ul.onclick = async (ev)=>{
    if (!is_admin) return;
    const btn = ev.target.closest('button'); if(!btn) return;
    const id = +btn.dataset.id;
    if (btn.dataset.act==='del'){ if(!confirm('Delete department?')) return; await api('departments.delete&'+new URLSearchParams({id}).toString()); await loadDepts(); refreshDeptRoleMapper(); }
    if (btn.dataset.act==='toggle'){ const active = btn.dataset.active === '1' ? 0 : 1; const d = depts.find(x=>x.id===id); await api('departments.update',{method:'POST',body:JSON.stringify({id,name:d.name,is_active:active})}); await loadDepts(); refreshDeptRoleMapper(); }
  };
}

async function loadRoles(){
  roles = await api('roles.list');
  const ul = $('#roleList'); ul.innerHTML='';
  roles.forEach(r=>{
    const li=document.createElement('li'); li.className='list-group-item d-flex align-items-center justify-content-between';
    li.innerHTML = `<div>${esc(r.name)}</div>
      <div class="admin-only"><button class="btn btn-sm btn-outline-danger" data-id="${r.id}">Delete</button></div>`;
    ul.appendChild(li);
  });
  ul.onclick = async (ev)=>{
    if (!is_admin) return;
    const btn = ev.target.closest('button'); if(!btn) return;
    const id=+btn.dataset.id; if(!confirm('Delete role?')) return;
    await api('roles.delete&'+new URLSearchParams({id}).toString());
    await loadRoles(); refreshDeptRoleMapper();
  };
}

function refreshDeptRoleMapper(){
  const selD = $('#mapDept'), selR = $('#mapRole');
  selD.innerHTML = depts.map(d=>`<option value="${d.id}">${esc(d.name)}</option>`).join('');
  selR.innerHTML = roles.map(r=>`<option value="${r.id}">${esc(r.name)}</option>`).join('');
  updateDeptRoleChips();
  selD.onchange = updateDeptRoleChips;
}

async function updateDeptRoleChips(){
  const dept = +($('#mapDept').value || 0); if(!dept) { $('#deptRolesChips').innerHTML=''; return; }
  const list = await api('dept.roles&dept='+dept);
  const box = $('#deptRolesChips'); box.innerHTML='';
  list.forEach(r=>{
    const chip = document.createElement('span'); chip.className='badge bg-light text-dark border me-2';
    chip.innerHTML = `${esc(r.name)} ${is_admin? `<button class="btn btn-sm btn-link text-danger p-0 ms-2" data-rid="${r.id}">×</button>`:''}`;
    box.appendChild(chip);
  });
  box.onclick = async (ev)=>{
    const b = ev.target.closest('button'); if(!b) return;
    await api('dept.roles.remove',{method:'POST',body:JSON.stringify({department_id:dept, role_id:+b.dataset.rid})});
    updateDeptRoleChips();
  };
}

async function attachRoleToDept(){
  if (!is_admin) return alert('Admin only');
  const department_id = +$('#mapDept').value, role_id = +$('#mapRole').value;
  await api('dept.roles.add',{method:'POST',body:JSON.stringify({department_id, role_id})});
  updateDeptRoleChips();
}

function mondayOf(dateStr){ const d=new Date(dateStr); const day=(d.getDay()+6)%7; d.setDate(d.getDate()-day); return d.toISOString().slice(0,10); }
function daysOfWeek(){
  const start=new Date(currentWeekStart);
  return [...Array(7)].map((_,i)=>{const d=new Date(start); d.setDate(d.getDate()+i);
    return {iso:d.toISOString().slice(0,10), label:d.toLocaleDateString(undefined,{weekday:'short',month:'short',day:'numeric'})};});
}

async function loadWeek(){
  currentWeekStart = mondayOf($('#weekInput').value || new Date().toISOString().slice(0,10));
  const j = await api('shifts.week&week='+currentWeekStart);
  shifts = j.shifts || []; is_admin = j.is_admin ? 1 : 0; toggleAdminUI();
  renderSchedule(); await loadPublishStatus();
}

function renderSchedule(){
  const thead=$('#schedTable thead'), tbody=$('#schedTable tbody'); const days=daysOfWeek();
  thead.innerHTML = `<tr>
    <th style="min-width:220px; position:sticky; left:0; background:#fff; z-index:1">Team member</th>
    ${days.map(d=>`<th style="min-width:160px">${d.label}</th>`).join('')}
  </tr>`;
  tbody.innerHTML = '';

  const list = employees.filter(e=>e.is_active).sort((a,b)=> a.name.localeCompare(b.name));
  list.forEach(emp=>{
    const tr=document.createElement('tr');
    const nameTd=document.createElement('td');
    nameTd.style.position='sticky'; nameTd.style.left='0'; nameTd.style.background='#fff'; nameTd.style.zIndex=1;
    nameTd.innerHTML = `<div class="fw-bold">${esc(emp.name)}</div><div class="small text-muted">${esc(emp.role)}</div>`;
    tr.appendChild(nameTd);

    days.forEach(d=>{
      const td=document.createElement('td');
      const dayShifts=shifts.filter(s=> s.employee_id===emp.id && s.start_dt.slice(0,10)===d.iso);
      dayShifts.forEach(s=>{
        const div=document.createElement('div'); div.className='border rounded p-1 mb-1';
        const t1=s.start_dt.slice(11,16), t2=s.end_dt.slice(11,16);
        div.innerHTML = `<div class="fw-semibold">${t1}–${t2}</div>${s.notes?`<div class="small text-muted">${esc(s.notes)}</div>`:''}
          ${is_admin? `<button class="btn btn-sm btn-outline-danger mt-1 del-shift" data-id="${s.id}">Delete</button>`:''}`;
        td.appendChild(div);
      });
      if (is_admin){
        const b=document.createElement('button'); b.className='btn btn-sm btn-outline-primary'; b.textContent='+ Add';
        b.onclick=()=> openShiftModal(emp.id,d.iso); td.appendChild(b);
      }
      tr.appendChild(td);
    });

    tbody.appendChild(tr);
  });

  tbody.onclick = async (ev)=>{
    const btn = ev.target.closest('.del-shift'); if(!btn) return;
    if (!confirm('Delete shift?')) return;
    await fetch('/schedule/api?a=shifts.delete&id='+btn.dataset.id);
    await loadWeek();
  };
}

async function loadPublishStatus(){
  const j=await api('publish.status&week='+currentWeekStart);
  $('#pubStatus').textContent = j.published ? 'Published' : 'Not published';
  $('#pubStatus').className = 'badge ' + (j.published ? 'bg-success':'bg-secondary');
  $('#publishBtn').dataset.pub = j.published ? '1':'0';
}

async function togglePublish(){
  if (!is_admin) return alert('Admin only');
  const next = ($('#publishBtn').dataset.pub === '1') ? 0 : 1;
  await api('publish.set',{method:'POST',body:JSON.stringify({week:currentWeekStart, published:!!next})});
  await loadPublishStatus();
}

// ===== Shift modal =====
let modalEl, modal;
function ensureModal(){
  if (modal) return;
  const html = `
<div class="modal" id="shiftModal" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Add Shift</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
      <div class="mb-2"><input id="startDt" type="datetime-local" class="form-control"></div>
      <div class="mb-2"><input id="endDt" type="datetime-local" class="form-control"></div>
      <div><textarea id="notes" class="form-control" placeholder="Notes (optional)"></textarea></div>
    </div>
    <div class="modal-footer"><button id="saveShiftBtn" class="btn btn-primary">Save</button></div>
  </div></div></div>`;
  document.body.insertAdjacentHTML('beforeend', html);
  modalEl = document.getElementById('shiftModal');
  modal = new bootstrap.Modal(modalEl);
}
function openShiftModal(empId, dateIso){
  ensureModal();
  $('#startDt').value = `${dateIso}T10:00`;
  $('#endDt').value   = `${dateIso}T18:00`;
  $('#notes').value   = '';
  $('#saveShiftBtn').onclick = async ()=>{
    const start_dt=$('#startDt').value.replace('T',' ')+':00';
    const end_dt  =$('#endDt').value.replace('T',' ')+':00';
    const notes   =$('#notes').value.trim();
    const r = await api('shifts.create',{method:'POST',body:JSON.stringify({employee_id:empId,start_dt,end_dt,notes})});
    if (r.error){ alert(r.error); return; }
    modal.hide(); await loadWeek();
  };
  modal.show();
}

// Admins tab (lazy load)
async function loadAdminsTabOnShow(){
  const tabBtn = document.querySelector('[data-bs-target="#pane-admins"]');
  tabBtn.addEventListener('shown.bs.tab', async ()=>{
    if (!is_admin){ $('#adminList').innerHTML='<div class="text-muted">View only.</div>'; return; }
    const list = await api('users.list');
    const wrap = $('#adminList'); wrap.innerHTML='';
    list.forEach(u=>{
      const row=document.createElement('div'); row.className='border rounded p-2 mb-2 d-flex align-items-center justify-content-between';
      row.innerHTML = `<div><strong>${esc(u.username)}</strong>${u.full_name?` <span class="text-muted">(${esc(u.full_name)})</span>`:''}</div>
        <div class="form-check form-switch">
          <input class="form-check-input toggle-admin" type="checkbox" data-id="${u.id}" ${u.is_admin?'checked':''}>
        </div>`;
      wrap.appendChild(row);
    });
    wrap.onclick = async (ev)=>{
      const sw = ev.target.closest('.toggle-admin'); if(!sw) return;
      await api('users.setAdmin',{method:'POST',body:JSON.stringify({id:+sw.dataset.id,is_admin: sw.checked?1:0})});
    };
  }, {once:true});
}
</script>
