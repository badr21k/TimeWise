<?php require 'app/views/templates/header.php'; ?>

<div class="container" style="max-width:1200px">
  <div class="d-flex align-items-center justify-content-between">
    <h1 class="mb-3">Team &amp; Schedule</h1>
    <div class="d-flex align-items-center gap-2">
      <label class="me-2 fw-semibold">Week:</label>
      <input id="weekInput" type="date" class="form-control form-control-sm" />
      <button id="publishBtn" class="btn btn-sm btn-primary">Publish week</button>
      <span id="pubStatus" class="badge bg-secondary">…</span>
    </div>
  </div>

  <ul class="nav nav-tabs" role="tablist">
    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pane-week" type="button">Schedule</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-emps" type="button">Team Roster</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-depts" type="button">Departments & Roles</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-admins" type="button">Admin Settings</button></li>
  </ul>

  <div class="tab-content border border-top-0 p-3 rounded-bottom">

    <!-- WEEK SCHEDULE -->
    <div class="tab-pane fade show active" id="pane-week">
      <div class="table-responsive">
        <table class="table table-sm align-top" id="schedTable">
          <thead class="table-light"></thead><tbody></tbody>
        </table>
      </div>
      <div class="alert alert-info mt-2 d-none" id="roNote">Only admins can modify the schedule. You’re in read-only mode.</div>
    </div>

    <!-- EMPLOYEES -->
    <div class="tab-pane fade" id="pane-emps">
      <div class="card p-3 mb-3 admin-only">
        <div class="row g-2">
          <div class="col-md-4"><input id="empName" class="form-control" placeholder="Full name"></div>
          <div class="col-md-4"><input id="empEmail" class="form-control" placeholder="Email (optional)"></div>
          <div class="col-md-3"><input id="empRole" class="form-control" placeholder="Role/Position"></div>
          <div class="col-md-1 d-grid"><button id="addEmpBtn" class="btn btn-success">Add</button></div>
        </div>
      </div>
      <div id="empList" class="row gy-2"></div>
    </div>

    <!-- PLACEHOLDER TAB -->
    <div class="tab-pane fade" id="pane-depts">
      <div class="alert alert-secondary">Departments & roles coming soon.</div>
    </div>

    <!-- ADMIN -->
    <div class="tab-pane fade" id="pane-admins">
      <div class="alert alert-warning"><i class="bi bi-shield-lock"></i> Only admins can modify admin privileges.</div>
      <div id="adminList"></div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../templates/footer.php'; ?>

<script>
console.log('[schedule] script loaded');
const api=(a,o={})=>fetch(`/schedule/api?a=${encodeURIComponent(a)}`,{headers:{'Content-Type':'application/json'},...o}).then(r=>r.json());
const $=s=>document.querySelector(s), $$=s=>Array.from(document.querySelectorAll(s));
let employees=[],shifts=[],currentWeekStart=null,is_admin=0,shiftModal;

function iso(d){return d.toISOString().slice(0,10)}
function mondayOf(s){const d=new Date(s);const k=(d.getDay()+6)%7;d.setDate(d.getDate()-k);return iso(d)}
function daysOfWeek(){const s=new Date(currentWeekStart);return[...Array(7)].map((_,i)=>{const d=new Date(s);d.setDate(d.getDate()+i);return{iso:iso(d),label:d.toLocaleDateString(undefined,{weekday:'short',month:'short',day:'numeric'})}})}
function EH(s){return (s??'').toString().replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]))}

document.addEventListener('DOMContentLoaded', async ()=>{
  const today=new Date().toISOString().slice(0,10);
  $('#weekInput').value=today;
  await loadEmployees(); await loadWeek();
  $('#addEmpBtn').onclick=addEmployee;
  $('#weekInput').onchange=loadWeek;
  $('#publishBtn').onclick=togglePublish;
  document.querySelector('[data-bs-target="#pane-admins"]').addEventListener('shown.bs.tab', loadAdmins, {once:true});
});

// Employees
async function loadEmployees(){ employees=await api('employees.list'); renderEmployees(); }
function renderEmployees(){
  const wrap=$('#empList'); wrap.innerHTML='';
  employees.forEach(e=>{
    const col=document.createElement('div'); col.className='col-md-6';
    col.innerHTML=`<div class="border rounded p-2 d-flex align-items-center gap-2">
      <div class="flex-grow-1">
        <div class="fw-bold">${EH(e.name)} <span class="text-muted">(${EH(e.role)})</span></div>
        ${e.email?`<div class="small text-muted">${EH(e.email)}</div>`:''}
      </div>
      <div class="form-check form-switch admin-only"><input class="form-check-input emp-active" type="checkbox" data-id="${e.id}" ${e.is_active?'checked':''}></div>
      <button class="btn btn-outline-danger btn-sm emp-del admin-only" data-id="${e.id}">Delete</button>
    </div>`;
    wrap.appendChild(col);
  });
  wrap.onclick=async ev=>{
    const del=ev.target.closest('.emp-del'); if(del){ if(!confirm('Delete employee and shifts?'))return; await fetch(`/schedule/api?a=employees.delete&id=${del.dataset.id}`); await loadEmployees(); await loadWeek(); return; }
    const sw=ev.target.closest('.emp-active'); if(sw){ await fetch('/schedule/api?a=employees.update',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({id:+sw.dataset.id,is_active: sw.checked?1:0})}); }
  };
}
async function addEmployee(){
  if(!is_admin) return alert('Admin only');
  const name=$('#empName').value.trim(); if(!name) return alert('Name required');
  const email=$('#empEmail').value.trim()||null; const role=$('#empRole').value.trim()||'Staff';
  await fetch('/schedule/api?a=employees.create',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({name,email,role})});
  $('#empName').value=''; $('#empEmail').value=''; $('#empRole').value='';
  await loadEmployees();
}

// Week schedule
async function loadWeek(){
  currentWeekStart=mondayOf($('#weekInput').value || new Date().toISOString().slice(0,10));
  const j=await api('shifts.week&week='+currentWeekStart);
  shifts=j.shifts||[]; is_admin=+j.is_admin||0;
  $$('.admin-only').forEach(el=> el.style.display = is_admin ? '' : 'none');
  document.getElementById('roNote').classList.toggle('d-none', !!is_admin);
  renderSchedule(); await loadPublishStatus();
}
function renderSchedule(){
  const thead=$('#schedTable thead'), tbody=$('#schedTable tbody'), days=daysOfWeek();
  thead.innerHTML=`<tr><th style="min-width:200px;position:sticky;left:0;background:#fff;z-index:1">Employee</th>${days.map(d=>`<th style="min-width:160px">${d.label}</th>`).join('')}</tr>`;
  tbody.innerHTML='';
  employees.filter(e=>e.is_active).sort((a,b)=>a.name.localeCompare(b.name)).forEach(emp=>{
    const tr=document.createElement('tr');
    const nameTd=document.createElement('td'); nameTd.style.position='sticky'; nameTd.style.left='0'; nameTd.style.background='#fff'; nameTd.style.zIndex=1;
    nameTd.innerHTML=`<div class="fw-bold">${EH(emp.name)}</div><div class="small text-muted">${EH(emp.role)}</div>`;
    tr.appendChild(nameTd);
    days.forEach(d=>{
      const td=document.createElement('td');
      const list=shifts.filter(s=> s.employee_id===emp.id && s.start_dt.slice(0,10)===d.iso);
      list.forEach(s=>{
        const div=document.createElement('div'); div.className='border rounded p-1 mb-1';
        const t1=s.start_dt.slice(11,16), t2=s.end_dt.slice(11,16);
        div.innerHTML=`<div class="fw-semibold">${t1}–${t2}</div>${s.notes?`<div class="small text-muted">${EH(s.notes)}</div>`:''}${is_admin?`<button class="btn btn-sm btn-outline-danger mt-1 del-shift" data-id="${s.id}">Delete</button>`:''}`;
        td.appendChild(div);
      });
      if(is_admin){ const b=document.createElement('button'); b.className='btn btn-sm btn-outline-primary'; b.textContent='+ Add'; b.onclick=()=>openModal(emp.id,d.iso); td.appendChild(b); }
      tr.appendChild(td);
    });
    tbody.appendChild(tr);
  });
  tbody.onclick=async ev=>{ const b=ev.target.closest('.del-shift'); if(!b) return; if(!confirm('Delete shift?')) return; await fetch(`/schedule/api?a=shifts.delete&id=${b.dataset.id}`); await loadWeek(); };
}

async function loadPublishStatus(){
  const j=await api('publish.status&week='+currentWeekStart);
  document.getElementById('pubStatus').textContent = j.published ? 'Published' : 'Not published';
  document.getElementById('pubStatus').className = 'badge ' + (j.published ? 'bg-success' : 'bg-secondary');
  document.getElementById('publishBtn').dataset.pub = j.published ? '1':'0';
  document.getElementById('publishBtn').style.display = (j.is_admin? '' : 'none');
}
async function togglePublish(){
  if(!is_admin) return alert('Admin only');
  const next = (document.getElementById('publishBtn').dataset.pub==='1') ? 0 : 1;
  await fetch('/schedule/api?a=publish.set',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({week:currentWeekStart,published:!!next})});
  await loadPublishStatus();
}

// Admins tab
async function loadAdmins(){
  const list=await api('users.list'); const wrap=document.getElementById('adminList'); wrap.innerHTML='';
  list.forEach(u=>{
    const row=document.createElement('div'); row.className='border rounded p-2 mb-2 d-flex align-items-center justify-content-between';
    row.innerHTML=`<div><strong>${EH(u.username)}</strong>${u.full_name?` <span class="text-muted">(${EH(u.full_name)})</span>`:''}</div>
      <div class="form-check form-switch"><input class="form-check-input toggle-admin" type="checkbox" data-id="${u.id}" ${u.is_admin?'checked':''}></div>`;
    wrap.appendChild(row);
  });
  wrap.onclick=async ev=>{ const sw=ev.target.closest('.toggle-admin'); if(!sw) return;
    await fetch('/schedule/api?a=users.setAdmin',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({id:+sw.dataset.id,is_admin: sw.checked?1:0})});
  };
}
</script>

<!-- Minimal Add Shift modal (optional; you can style later) -->
<div class="modal" id="shiftModal" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Add Shift</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
      <div class="mb-2"><input id="startDt" type="datetime-local" class="form-control"></div>
      <div class="mb-2"><input id="endDt" type="datetime-local" class="form-control"></div>
      <div><textarea id="notes" class="form-control" placeholder="Notes (optional)"></textarea></div>
    </div>
    <div class="modal-footer"><button id="saveShiftBtn" class="btn btn-primary" onclick="saveShift()">Save</button></div>
  </div></div>
</div>
<?php require 'app/views/templates/footer.php'; ?> 