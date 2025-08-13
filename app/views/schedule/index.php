<?php require 'app/views/templates/header.php'; ?>

<div class="container" style="max-width:1200px">
  <h1 class="mb-3">Team & Schedule</h1>

  <ul class="nav nav-tabs" role="tablist">
    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pane-emps" type="button">Employees</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-week" type="button">Week Schedule</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-admins" type="button">Admins</button></li>
    <li class="nav-item ms-auto">
      <div class="d-flex align-items-center gap-2 px-2">
        <input id="weekInput" type="date" class="form-control form-control-sm">
        <button id="publishBtn" class="btn btn-sm btn-primary">Publish week</button>
        <span id="pubStatus" class="badge bg-secondary">…</span>
      </div>
    </li>
  </ul>

  <div class="tab-content border border-top-0 p-3 rounded-bottom">
    <div class="tab-pane fade show active" id="pane-emps">
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

    <div class="tab-pane fade" id="pane-week">
      <div class="table-responsive">
        <table class="table table-sm align-top" id="schedTable">
          <thead class="table-light"></thead><tbody></tbody>
        </table>
      </div>
      <div class="alert alert-info mt-2 d-none" id="roNote">You are not an admin. Schedule is read-only.</div>
      
      <!-- Weekly Summary -->
      <div class="card mt-3">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h6 class="mb-0">Weekly Summary</h6>
          <small class="text-muted" id="weekRange"></small>
        </div>
        <div class="card-body">
          <div id="weeklySummary" class="row g-3">
            <!-- Will be populated by JavaScript -->
          </div>
        </div>
      </div>
    </div>

    <div class="tab-pane fade" id="pane-admins">
      <div class="alert alert-secondary">Only admins can change admin rights.</div>
      <div id="adminList"></div>
    </div>
  </div>
</div>

<?php require_once 'templates/footer.php'; ?>

<script>
const api=(a,o={})=>fetch(`/schedule/api?a=${encodeURIComponent(a)}`,{headers:{'Content-Type':'application/json'},...o}).then(r=>r.json());
const $=s=>document.querySelector(s), $$=s=>Array.from(document.querySelectorAll(s));
let employees=[],shifts=[],currentWeekStart=null,is_admin=0,shiftModal,copyShiftData=nulll;

function iso(d){return d.toISOString().slice(0,10)}
function mondayOf(s){const d=new Date(s);const k=(d.getDay()+6)%7;d.setDate(d.getDate()-k);return iso(d)}
function daysOfWeek(){const s=new Date(currentWeekStart);return[...Array(7)].map((_,i)=>{const d=new Date(s);d.setDate(d.getDate()+i);return{iso:iso(d),label:d.toLocaleDateString(undefined,{weekday:'short',month:'short',day:'numeric'})}})}
function EH(s){return (s??'').toString().replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]))}

function renderWeeklySummary() {
  const summary = {};
  let totalHours = 0;
  
  employees.filter(e => e.is_active).forEach(emp => {
    const empShifts = shifts.filter(s => s.employee_id === emp.id);
    let empHours = 0;
    
    empShifts.forEach(s => {
      const start = new Date(s.start_dt);
      const end = new Date(s.end_dt);
      const hours = (end - start) / (1000 * 60 * 60);
      empHours += hours;
    });
    
    if (empHours > 0) {
      summary[emp.name] = {
        hours: empHours,
        shifts: empShifts.length,
        role: emp.role
      };
      totalHours += empHours;
    }
  });
  
  const summaryDiv = $('#weeklySummary');
  summaryDiv.innerHTML = `
    <div class="col-12 mb-2">
      <div class="alert alert-primary mb-0">
        <strong>Total Weekly Hours: ${totalHours.toFixed(1)}h</strong> 
        across ${Object.keys(summary).length} employees
      </div>
    </div>
    ${Object.entries(summary).map(([name, data]) => `
      <div class="col-md-6 col-lg-4">
        <div class="border rounded p-2">
          <div class="fw-bold">${EH(name)}</div>
          <small class="text-muted">${EH(data.role)}</small>
          <div class="mt-1">
            <span class="badge bg-success">${data.hours.toFixed(1)}h</span>
            <span class="badge bg-info">${data.shifts} shifts</span>
          </div>
        </div>
      </div>
    `).join('')}
  `;
}

document.addEventListener('DOMContentLoaded', async ()=>{
  const today=new Date().toISOString().slice(0,10);
  $('#weekInput').value=today;
  shiftModal=new bootstrap.Modal(document.getElementById('shiftModal'));
  await loadEmployees(); await loadWeek();
  $('#addEmpBtn').onclick=addEmployee;
  $('#weekInput').onchange=loadWeek;
  $('#publishBtn').onclick=togglePublish;
  document.querySelector('[data-bs-target="#pane-admins"]').addEventListener('shown.bs.tab', loadAdmins, {once:true});
  document.getElementById('saveShiftBtn').onclick=saveShift;
});

async function loadEmployees(){ employees=await api('employees.list'); renderEmployees(); }
function renderEmployees(){
  const wrap=$('#empList'); wrap.innerHTML='';
  employees.forEach(e=>{
    const col=document.createElement('div'); col.className='col-md-6';
    col.innerHTML=`<div class="border rounded p-2 d-flex align-items-center gap-2">
      <div class="flex-grow-1"><div class="fw-bold">${EH(e.name)} <span class="text-muted">(${EH(e.role)})</span></div>${e.email?`<div class="small text-muted">${EH(e.email)}</div>`:''}</div>
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
  
  // Update week range display
  const start = new Date(currentWeekStart);
  const end = new Date(start);
  end.setDate(end.getDate() + 6);
  $('#weekRange').textContent = `${start.toLocaleDateString()} - ${end.toLocaleDateString()}`;
  employees.filter(e=>e.is_active).sort((a,b)=>a.name.localeCompare(b.name)).forEach(emp=>{
    const tr=document.createElement('tr');
    const nameTd=document.createElement('td'); nameTd.style.position='sticky'; nameTd.style.left='0'; nameTd.style.background='#fff'; nameTd.style.zIndex=1;
    nameTd.innerHTML=`<div class="fw-bold">${EH(emp.name)}</div><div class="small text-muted">${EH(emp.role)}</div>`;
    tr.appendChild(nameTd);
    days.forEach(d=>{
      const td=document.createElement('td');
      const list=shifts.filter(s=> s.employee_id===emp.id && s.start_dt.slice(0,10)===d.iso);
      list.forEach(s=>{
        const div=document.createElement('div'); div.className='border rounded p-1 mb-1 position-relative shift-card';
        const t1=s.start_dt.slice(11,16), t2=s.end_dt.slice(11,16);
        const startTime = new Date(s.start_dt);
        const endTime = new Date(s.end_dt);
        const hours = ((endTime - startTime) / (1000 * 60 * 60)).toFixed(1);
        
        div.innerHTML=`
          <div class="fw-semibold d-flex justify-content-between">
            <span>${t1}–${t2}</span>
            <small class="text-muted">${hours}h</small>
          </div>
          ${s.notes?`<div class="small text-muted">${EH(s.notes)}</div>`:''}
          ${is_admin?`
            <div class="btn-group btn-group-sm mt-1 w-100">
              <button class="btn btn-outline-primary btn-sm copy-shift" data-shift='${JSON.stringify(s)}' title="Copy to another day">Copy</button>
              <button class="btn btn-outline-danger btn-sm del-shift" data-id="${s.id}" title="Delete shift">Del</button>
            </div>
          `:''}
        `;
        td.appendChild(div);
      });
      if(is_admin){ const b=document.createElement('button'); b.className='btn btn-sm btn-outline-primary'; b.textContent='+ Add'; b.onclick=()=>openModal(emp.id,d.iso); td.appendChild(b); }
      tr.appendChild(td);
    });
    tbody.appendChild(tr);
  });
  
  // Render weekly summary
  renderWeeklySummary();
  
  tbody.onclick=async ev=>{ 
    const del=ev.target.closest('.del-shift'); 
    if(del){ 
      if(!confirm('Delete shift?')) return; 
      await fetch(`/schedule/api?a=shifts.delete&id=${del.dataset.id}`); 
      await loadWeek(); 
      return;
    }
    
    const copy=ev.target.closest('.copy-shift');
    if(copy){
      const shift = JSON.parse(copy.dataset.shift);
      copyShiftData = shift;
      showToast('info', 'Shift Copied', 'Click "+ Add" on any day to paste this shift');
      return;
    } };
}

function openModal(empId,dateIso){
  const emp = employees.find(e => e.id === empId);
  document.querySelector('#shiftModal .modal-title').textContent = `Add Shift - ${emp ? emp.name : 'Employee'}`;
  document.getElementById('saveShiftBtn').dataset.emp=empId;
  
  // If we have copied shift data, use it
  if (copyShiftData) {
    const startTime = copyShiftData.start_dt.slice(11,16);
    const endTime = copyShiftData.end_dt.slice(11,16);
    document.getElementById('startDt').value = `${dateIso}T${startTime}`;
    document.getElementById('endDt').value = `${dateIso}T${endTime}`;
    document.getElementById('notes').value = copyShiftData.notes || '';
    copyShiftData = null; // Clear after use
    showToast('success', 'Shift Pasted', 'Shift details have been filled in');
  } else {
    // Default times
    document.getElementById('startDt').value=`${dateIso}T10:00`;
    document.getElementById('endDt').value=`${dateIso}T18:00`;
    document.getElementById('notes').value='';
  }
  document.getElementById('breakDuration').value='30';
  
  shiftModal.show();
}
function setShiftTime(start, end) {
  const dateIso = document.getElementById('startDt').value.slice(0,10);
  document.getElementById('startDt').value = `${dateIso}T${start}`;
  
  if (end === '00:00') {
    // Next day for overnight shifts
    const nextDay = new Date(dateIso);
    nextDay.setDate(nextDay.getDate() + 1);
    document.getElementById('endDt').value = `${nextDay.toISOString().slice(0,10)}T${end}`;
  } else {
    document.getElementById('endDt').value = `${dateIso}T${end}`;
  }
}

function calculateHours(start, end, breakMins = 0) {
  const startTime = new Date(start);
  const endTime = new Date(end);
  const diffMs = endTime - startTime;
  const hours = (diffMs / (1000 * 60 * 60)) - (breakMins / 60);
  return Math.max(0, hours).toFixed(2);
}

async function saveShift(){
  const employee_id=+document.getElementById('saveShiftBtn').dataset.emp;
  const start_dt=document.getElementById('startDt').value.replace('T',' ')+':00';
  const end_dt  =document.getElementById('endDt').value.replace('T',' ')+':00';
  const breakDuration = +document.getElementById('breakDuration').value;
  const hours = calculateHours(start_dt, end_dt, breakDuration);
  let notes = document.getElementById('notes').value.trim();
  if (breakDuration > 0) {
    notes += (notes ? '\n' : '') + `Break: ${breakDuration}min, Hours: ${hours}`;
  }
  
  const r=await fetch('/schedule/api?a=shifts.create',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({employee_id,start_dt,end_dt,notes})});
  const j=await r.json(); if(j.error){alert(j.error);return;}
  shiftModal.hide(); await loadWeek();
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

<!-- Shift Modal -->
<div class="modal" id="shiftModal" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Add Shift</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
      <div class="mb-3">
        <label class="form-label">Quick Templates</label>
        <div class="btn-group w-100 mb-2" role="group">
          <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setShiftTime('08:00','16:00')">8am-4pm</button>
          <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setShiftTime('09:00','17:00')">9am-5pm</button>
          <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setShiftTime('10:00','18:00')">10am-6pm</button>
          <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setShiftTime('16:00','00:00')">4pm-12am</button>
        </div>
      </div>
      <div class="mb-2">
        <label class="form-label">Start Time</label>
        <input id="startDt" type="datetime-local" class="form-control">
      </div>
      <div class="mb-2">
        <label class="form-label">End Time</label>
        <input id="endDt" type="datetime-local" class="form-control">
      </div>
      <div class="mb-2">
        <label class="form-label">Break Duration</label>
        <select id="breakDuration" class="form-select">
          <option value="0">No break</option>
          <option value="30" selected>30 minutes</option>
          <option value="60">1 hour</option>
        </select>
      </div>
      <div>
        <label class="form-label">Notes</label>
        <textarea id="notes" class="form-control" placeholder="Notes (optional)"></textarea>
      </div>
    </div>
    <div class="modal-footer"><button id="saveShiftBtn" class="btn btn-primary">Save</button></div>
  </div></div>
</div>


<?php require 'app/views/templates/footer.php'; ?> 