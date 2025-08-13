
<?php require 'app/views/templates/header.php'; ?>

<style>
/* Homebase-inspired styling */
.schedule-container {
  background: #f8fafc;
  min-height: 100vh;
  padding: 0;
}

.schedule-header {
  background: white;
  border-bottom: 1px solid #e2e8f0;
  padding: 1.5rem 0;
  margin-bottom: 0;
}

.schedule-nav {
  background: white;
  border-bottom: 1px solid #e2e8f0;
  padding: 0;
}

.schedule-nav .nav-tabs {
  border-bottom: none;
  margin: 0;
}

.schedule-nav .nav-link {
  border: none;
  border-radius: 0;
  padding: 1rem 1.5rem;
  color: #64748b;
  font-weight: 500;
  background: transparent;
  transition: all 0.2s;
}

.schedule-nav .nav-link.active {
  background: white;
  color: #1e293b;
  border-bottom: 3px solid #3b82f6;
}

.schedule-nav .nav-link:hover {
  color: #1e293b;
  background: #f8fafc;
}

.schedule-content {
  background: white;
  margin: 0;
  padding: 0;
}

.week-controls {
  background: #f8fafc;
  padding: 1rem 1.5rem;
  border-bottom: 1px solid #e2e8f0;
  display: flex;
  align-items: center;
  gap: 1rem;
}

.week-picker {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.week-picker input {
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  padding: 0.5rem 0.75rem;
  font-size: 0.875rem;
}

.publish-controls {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-left: auto;
}

.btn-homebase {
  background: #3b82f6;
  color: white;
  border: none;
  border-radius: 0.375rem;
  padding: 0.5rem 1rem;
  font-weight: 500;
  font-size: 0.875rem;
  transition: all 0.2s;
}

.btn-homebase:hover {
  background: #2563eb;
  color: white;
}

.btn-homebase-outline {
  background: transparent;
  color: #3b82f6;
  border: 1px solid #3b82f6;
  border-radius: 0.375rem;
  padding: 0.5rem 1rem;
  font-weight: 500;
  font-size: 0.875rem;
  transition: all 0.2s;
}

.btn-homebase-outline:hover {
  background: #3b82f6;
  color: white;
}

.schedule-table {
  table-layout: fixed;
  width: 100%;
  margin: 0;
}

.schedule-table th {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  padding: 1rem 0.75rem;
  font-weight: 600;
  color: #374151;
  text-align: center;
  font-size: 0.875rem;
}

.schedule-table td {
  border: 1px solid #e2e8f0;
  padding: 0.5rem;
  vertical-align: top;
  min-height: 100px;
  background: white;
}

.employee-cell {
  background: #f8fafc !important;
  position: sticky;
  left: 0;
  z-index: 10;
  width: 200px;
  min-width: 200px;
  max-width: 200px;
}

.employee-name {
  font-weight: 600;
  color: #1e293b;
  font-size: 0.875rem;
  margin-bottom: 0.25rem;
}

.employee-role {
  color: #64748b;
  font-size: 0.75rem;
}

.shift-block {
  background: #3b82f6;
  color: white;
  border-radius: 0.375rem;
  padding: 0.5rem;
  margin-bottom: 0.5rem;
  font-size: 0.75rem;
  position: relative;
}

.shift-time {
  font-weight: 600;
  margin-bottom: 0.25rem;
}

.shift-notes {
  opacity: 0.9;
  font-size: 0.7rem;
}

.shift-delete {
  position: absolute;
  top: 0.25rem;
  right: 0.25rem;
  background: rgba(255,255,255,0.2);
  border: none;
  color: white;
  border-radius: 0.25rem;
  padding: 0.125rem 0.25rem;
  font-size: 0.7rem;
  opacity: 0;
  transition: opacity 0.2s;
}

.shift-block:hover .shift-delete {
  opacity: 1;
}

.add-shift-btn {
  background: #f8fafc;
  border: 2px dashed #d1d5db;
  color: #64748b;
  border-radius: 0.375rem;
  padding: 0.75rem;
  width: 100%;
  font-size: 0.75rem;
  font-weight: 500;
  transition: all 0.2s;
  cursor: pointer;
}

.add-shift-btn:hover {
  border-color: #3b82f6;
  color: #3b82f6;
  background: #eff6ff;
}

.team-roster {
  padding: 1.5rem;
}

.add-employee-form {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  padding: 1.5rem;
  margin-bottom: 1.5rem;
}

.employee-card {
  background: white;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  padding: 1rem;
  margin-bottom: 0.75rem;
  transition: all 0.2s;
}

.employee-card:hover {
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.status-badge {
  font-size: 0.75rem;
  padding: 0.25rem 0.5rem;
  border-radius: 9999px;
  font-weight: 500;
}

.status-published {
  background: #dcfce7;
  color: #166534;
}

.status-draft {
  background: #fef3c7;
  color: #92400e;
}

.modal-homebase .modal-content {
  border: none;
  border-radius: 0.75rem;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.modal-homebase .modal-header {
  border-bottom: 1px solid #e2e8f0;
  padding: 1.5rem;
}

.modal-homebase .modal-body {
  padding: 1.5rem;
}

.modal-homebase .modal-footer {
  border-top: 1px solid #e2e8f0;
  padding: 1.5rem;
}
</style>

<div class="schedule-container">
  <!-- Header -->
  <div class="schedule-header">
    <div class="container-fluid px-4">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <h1 class="h3 mb-1 fw-bold text-gray-900">Schedule</h1>
          <p class="text-muted mb-0">Manage your team's work schedule</p>
        </div>
        <div class="week-controls">
          <div class="week-picker">
            <label class="text-sm fw-medium text-gray-700">Week of:</label>
            <input id="weekInput" type="date" class="form-control form-control-sm">
          </div>
          <div class="publish-controls">
            <span id="pubStatus" class="status-badge status-draft">Draft</span>
            <button id="publishBtn" class="btn btn-homebase btn-sm">Publish Schedule</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Navigation -->
  <div class="schedule-nav">
    <div class="container-fluid px-4">
      <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
          <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pane-schedule" type="button">
            <i class="fas fa-calendar-alt me-2"></i>Schedule
          </button>
        </li>
        <li class="nav-item">
          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-team" type="button">
            <i class="fas fa-users me-2"></i>Team Roster
          </button>
        </li>
        <li class="nav-item">
          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-admin" type="button">
            <i class="fas fa-cog me-2"></i>Settings
          </button>
        </li>
      </ul>
    </div>
  </div>

  <!-- Content -->
  <div class="schedule-content">
    <div class="tab-content">
      
      <!-- Schedule Tab -->
      <div class="tab-pane fade show active" id="pane-schedule">
        <div class="table-responsive">
          <table class="schedule-table" id="schedTable">
            <thead></thead>
            <tbody></tbody>
          </table>
        </div>
        <div class="alert alert-info m-4 d-none" id="roNote">
          <i class="fas fa-info-circle me-2"></i>
          You're viewing in read-only mode. Only administrators can modify schedules.
        </div>
      </div>

      <!-- Team Roster Tab -->
      <div class="tab-pane fade" id="pane-team">
        <div class="team-roster">
          <div class="add-employee-form admin-only">
            <h5 class="mb-3">Add Team Member</h5>
            <div class="row g-3">
              <div class="col-md-4">
                <input id="empName" class="form-control" placeholder="Full name" required>
              </div>
              <div class="col-md-4">
                <input id="empEmail" class="form-control" placeholder="Email address">
              </div>
              <div class="col-md-3">
                <input id="empRole" class="form-control" placeholder="Role/Position">
              </div>
              <div class="col-md-1">
                <button id="addEmpBtn" class="btn btn-homebase w-100">Add</button>
              </div>
            </div>
          </div>
          <div id="empList"></div>
        </div>
      </div>

      <!-- Admin Settings Tab -->
      <div class="tab-pane fade" id="pane-admin">
        <div class="team-roster">
          <div class="alert alert-warning">
            <i class="fas fa-shield-alt me-2"></i>
            Only administrators can modify user permissions.
          </div>
          <div id="adminList"></div>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Add Shift Modal -->
<div class="modal modal-homebase" id="shiftModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Shift</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Start Time</label>
          <input id="startDt" type="datetime-local" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">End Time</label>
          <input id="endDt" type="datetime-local" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">Notes (optional)</label>
          <textarea id="notes" class="form-control" rows="3" placeholder="Add any notes about this shift..."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button id="saveShiftBtn" class="btn btn-homebase" onclick="saveShift()">Save Shift</button>
      </div>
    </div>
  </div>
</div>

<?php require_once 'app/views/templates/footer.php'; ?>

<script>
console.log('[schedule] script loaded');
const api=(a,o={})=>fetch(`/schedule/api?a=${encodeURIComponent(a)}`,{headers:{'Content-Type':'application/json'},...o}).then(r=>r.json());
const $=s=>document.querySelector(s), $$=s=>Array.from(document.querySelectorAll(s));
let employees=[],shifts=[],currentWeekStart=null,is_admin=0,shiftModal,currentShiftEmp=0,currentShiftDate='';

function iso(d){return d.toISOString().slice(0,10)}
function mondayOf(s){const d=new Date(s);const k=(d.getDay()+6)%7;d.setDate(d.getDate()-k);return iso(d)}
function daysOfWeek(){const s=new Date(currentWeekStart);return[...Array(7)].map((_,i)=>{const d=new Date(s);d.setDate(d.getDate()+i);return{iso:iso(d),label:d.toLocaleDateString(undefined,{weekday:'short',month:'short',day:'numeric'})}})}
function EH(s){return (s??'').toString().replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]))}

document.addEventListener('DOMContentLoaded', async ()=>{
  const today=new Date().toISOString().slice(0,10);
  $('#weekInput').value=today;
  shiftModal = new bootstrap.Modal($('#shiftModal'));
  await loadEmployees(); await loadWeek();
  $('#addEmpBtn').onclick=addEmployee;
  $('#weekInput').onchange=loadWeek;
  $('#publishBtn').onclick=togglePublish;
  document.querySelector('[data-bs-target="#pane-admin"]').addEventListener('shown.bs.tab', loadAdmins, {once:true});
});

// Employees
async function loadEmployees(){ 
  try {
    employees=await api('employees.list'); 
    renderEmployees(); 
  } catch(e) {
    console.error('Error loading employees:', e);
    employees = [];
  }
}

function renderEmployees(){
  const wrap=$('#empList'); wrap.innerHTML='';
  employees.forEach(e=>{
    const card=document.createElement('div'); card.className='employee-card';
    card.innerHTML=`
      <div class="d-flex align-items-center justify-content-between">
        <div class="flex-grow-1">
          <div class="employee-name">${EH(e.name)}</div>
          <div class="employee-role">${EH(e.role)}</div>
          ${e.email?`<div class="text-muted" style="font-size:0.75rem">${EH(e.email)}</div>`:''}
        </div>
        <div class="d-flex align-items-center gap-2">
          <span class="status-badge ${e.is_active ? 'status-published' : 'status-draft'}">
            ${e.is_active ? 'Active' : 'Inactive'}
          </span>
          <div class="form-check form-switch admin-only">
            <input class="form-check-input emp-active" type="checkbox" data-id="${e.id}" ${e.is_active?'checked':''}>
          </div>
          <button class="btn btn-outline-danger btn-sm emp-del admin-only" data-id="${e.id}">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </div>
    `;
    wrap.appendChild(card);
  });
  
  wrap.onclick=async ev=>{
    const del=ev.target.closest('.emp-del'); 
    if(del){ 
      if(!confirm('Delete employee and all their shifts?'))return; 
      await fetch(`/schedule/api?a=employees.delete&id=${del.dataset.id}`); 
      await loadEmployees(); 
      await loadWeek(); 
      return; 
    }
    const sw=ev.target.closest('.emp-active'); 
    if(sw){ 
      await fetch('/schedule/api?a=employees.update',{
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body:JSON.stringify({id:+sw.dataset.id,is_active: sw.checked?1:0})
      }); 
      await loadEmployees();
    }
  };
}

async function addEmployee(){
  if(!is_admin) return alert('Admin access required');
  const name=$('#empName').value.trim(); 
  if(!name) return alert('Name is required');
  const email=$('#empEmail').value.trim()||null; 
  const role=$('#empRole').value.trim()||'Staff';
  
  try {
    await fetch('/schedule/api?a=employees.create',{
      method:'POST',
      headers:{'Content-Type':'application/json'},
      body:JSON.stringify({name,email,role})
    });
    $('#empName').value=''; $('#empEmail').value=''; $('#empRole').value='';
    await loadEmployees();
  } catch(e) {
    alert('Error adding employee: ' + e.message);
  }
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
  thead.innerHTML=`<tr>
    <th class="employee-cell">Employee</th>
    ${days.map(d=>`<th style="width:calc((100% - 200px)/7)">${d.label}</th>`).join('')}
  </tr>`;
  tbody.innerHTML='';
  
  employees.filter(e=>e.is_active).sort((a,b)=>a.name.localeCompare(b.name)).forEach(emp=>{
    const tr=document.createElement('tr');
    const nameTd=document.createElement('td'); 
    nameTd.className='employee-cell';
    nameTd.innerHTML=`<div class="employee-name">${EH(emp.name)}</div><div class="employee-role">${EH(emp.role)}</div>`;
    tr.appendChild(nameTd);
    
    days.forEach(d=>{
      const td=document.createElement('td');
      const empShifts=shifts.filter(s=> s.employee_id===emp.id && s.start_dt.slice(0,10)===d.iso);
      
      empShifts.forEach(s=>{
        const div=document.createElement('div'); 
        div.className='shift-block';
        const t1=s.start_dt.slice(11,16), t2=s.end_dt.slice(11,16);
        div.innerHTML=`
          <div class="shift-time">${t1} - ${t2}</div>
          ${s.notes?`<div class="shift-notes">${EH(s.notes)}</div>`:''}
          ${is_admin?`<button class="shift-delete del-shift" data-id="${s.id}">×</button>`:''}
        `;
        td.appendChild(div);
      });
      
      if(is_admin){ 
        const b=document.createElement('button'); 
        b.className='add-shift-btn'; 
        b.innerHTML='<i class="fas fa-plus me-1"></i>Add Shift'; 
        b.onclick=()=>openModal(emp.id,d.iso); 
        td.appendChild(b); 
      }
      tr.appendChild(td);
    });
    tbody.appendChild(tr);
  });
  
  tbody.onclick=async ev=>{ 
    const b=ev.target.closest('.del-shift'); 
    if(!b) return; 
    if(!confirm('Delete this shift?')) return; 
    await fetch(`/schedule/api?a=shifts.delete&id=${b.dataset.id}`); 
    await loadWeek(); 
  };
}

function openModal(empId, date) {
  currentShiftEmp = empId;
  currentShiftDate = date;
  $('#startDt').value = date + 'T09:00';
  $('#endDt').value = date + 'T17:00';
  $('#notes').value = '';
  shiftModal.show();
}

async function saveShift() {
  const start = $('#startDt').value;
  const end = $('#endDt').value;
  const notes = $('#notes').value;
  
  if (!start || !end) return alert('Start and end times are required');
  if (new Date(start) >= new Date(end)) return alert('End time must be after start time');
  
  try {
    await fetch('/schedule/api?a=shifts.create', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({
        employee_id: currentShiftEmp,
        start_dt: start,
        end_dt: end,
        notes: notes
      })
    });
    shiftModal.hide();
    await loadWeek();
  } catch(e) {
    alert('Error saving shift: ' + e.message);
  }
}

async function loadPublishStatus(){
  const j=await api('publish.status&week='+currentWeekStart);
  const status = $('#pubStatus');
  const btn = $('#publishBtn');
  
  if(j.published) {
    status.textContent = 'Published';
    status.className = 'status-badge status-published';
    btn.textContent = 'Unpublish Schedule';
  } else {
    status.textContent = 'Draft';
    status.className = 'status-badge status-draft';
    btn.textContent = 'Publish Schedule';
  }
  
  btn.dataset.pub = j.published ? '1':'0';
  btn.style.display = (j.is_admin ? '' : 'none');
}

async function togglePublish(){
  if(!is_admin) return alert('Admin access required');
  const next = $('#publishBtn').dataset.pub==='1' ? 0 : 1;
  await fetch('/schedule/api?a=publish.set',{
    method:'POST',
    headers:{'Content-Type':'application/json'},
    body:JSON.stringify({week:currentWeekStart,published:!!next})
  });
  await loadPublishStatus();
}

// Admins tab
async function loadAdmins(){
  const list=await api('users.list'); 
  const wrap=$('#adminList'); 
  wrap.innerHTML='';
  
  list.forEach(u=>{
    const card=document.createElement('div'); 
    card.className='employee-card';
    card.innerHTML=`
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <div class="employee-name">${EH(u.username)}</div>
          ${u.full_name?`<div class="employee-role">${EH(u.full_name)}</div>`:''}
        </div>
        <div class="d-flex align-items-center gap-2">
          <span class="status-badge ${u.is_admin ? 'status-published' : 'status-draft'}">
            ${u.is_admin ? 'Admin' : 'User'}
          </span>
          <div class="form-check form-switch">
            <input class="form-check-input toggle-admin" type="checkbox" data-id="${u.id}" ${u.is_admin?'checked':''}>
          </div>
        </div>
      </div>
    `;
    wrap.appendChild(card);
  });
  
  wrap.onclick=async ev=>{ 
    const sw=ev.target.closest('.toggle-admin'); 
    if(!sw) return;
    await fetch('/schedule/api?a=users.setAdmin',{
      method:'POST',
      headers:{'Content-Type':'application/json'},
      body:JSON.stringify({id:+sw.dataset.id,is_admin: sw.checked?1:0})
    });
    await loadAdmins();
  };
}
</script>
