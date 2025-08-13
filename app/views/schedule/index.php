<?php require 'app/views/templates/header.php'; ?>

<div class="container-fluid" style="max-width:1400px">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Team & Schedule</h1>
    <div class="d-flex align-items-center gap-3">
      <div class="d-flex align-items-center gap-2">
        <label class="form-label mb-0 fw-semibold">Week:</label>
        <input id="weekInput" type="date" class="form-control">
      </div>
      <button id="publishBtn" class="btn btn-primary">
        <i class="fas fa-share-alt me-1"></i>Publish week
      </button>
      <span id="pubStatus" class="badge bg-secondary">…</span>
    </div>
  </div>

  <ul class="nav nav-tabs nav-justified" role="tablist">
    <li class="nav-item">
      <button class="nav-link active d-flex align-items-center justify-content-center" data-bs-toggle="tab" data-bs-target="#pane-schedule" type="button">
        <i class="fas fa-calendar-alt me-2"></i>Schedule
      </button>
    </li>
    <li class="nav-item">
      <button class="nav-link d-flex align-items-center justify-content-center" data-bs-toggle="tab" data-bs-target="#pane-roster" type="button">
        <i class="fas fa-users me-2"></i>Team Roster
      </button>
    </li>
    <li class="nav-item">
      <button class="nav-link d-flex align-items-center justify-content-center" data-bs-toggle="tab" data-bs-target="#pane-departments" type="button">
        <i class="fas fa-building me-2"></i>Departments & Roles
      </button>
    </li>
    <li class="nav-item">
      <button class="nav-link d-flex align-items-center justify-content-center admin-only" data-bs-toggle="tab" data-bs-target="#pane-admins" type="button">
        <i class="fas fa-shield-alt me-2"></i>Admin Settings
      </button>
    </li>
  </ul>

  <div class="tab-content border border-top-0 rounded-bottom" style="min-height: 600px;">
    <!-- Schedule Tab -->
    <div class="tab-pane fade show active" id="pane-schedule">
      <div class="p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="d-flex align-items-center gap-3">
            <h5 class="mb-0" id="weekRangeTitle">Schedule</h5>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="showConflicts">
              <label class="form-check-label" for="showConflicts">Show conflicts</label>
            </div>
          </div>
          <div class="btn-group" role="group">
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="navigateWeek(-1)">
              <i class="fas fa-chevron-left"></i> Previous
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="goToCurrentWeek()">Today</button>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="navigateWeek(1)">
              Next <i class="fas fa-chevron-right"></i>
            </button>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-sm schedule-table" id="schedTable">
            <thead class="table-light"></thead>
            <tbody></tbody>
          </table>
        </div>

        <div class="alert alert-info mt-2 d-none" id="roNote">
          <i class="fas fa-info-circle me-2"></i>You are not an admin. Schedule is read-only.
        </div>

        <!-- Weekly Summary -->
        <div class="card mt-4">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Weekly Summary</h6>
            <small class="text-muted" id="weekRange"></small>
          </div>
          <div class="card-body">
            <div id="weeklySummary" class="row g-3"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Team Roster Tab -->
    <div class="tab-pane fade" id="pane-roster">
      <div class="p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="d-flex align-items-center gap-3">
            <h5 class="mb-0">Team Members</h5>
            <span class="badge bg-secondary" id="teamCount">0 team members</span>
          </div>
          <button class="btn btn-primary admin-only" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
            <i class="fas fa-plus me-1"></i>Add team member
          </button>
        </div>

        <div class="row mb-3">
          <div class="col-md-4">
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-search"></i></span>
              <input type="text" class="form-control" id="searchTeam" placeholder="Search team members...">
            </div>
          </div>
          <div class="col-md-3">
            <select class="form-select" id="filterRole">
              <option value="">All Roles</option>
            </select>
          </div>
          <div class="col-md-3">
            <select class="form-select" id="filterStatus">
              <option value="">All Status</option>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-hover">
            <thead class="table-light">
              <tr>
                <th>Team Member</th>
                <th>Contact Information</th>
                <th>Access Level</th>
                <th>Location</th>
                <th>Role</th>
                <th>Wage</th>
                <th>Status</th>
                <th class="admin-only">Actions</th>
              </tr>
            </thead>
            <tbody id="teamRosterTable">
              <!-- Will be populated by JavaScript -->
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Departments Tab -->
    <div class="tab-pane fade" id="pane-departments">
      <div class="p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="mb-0">Departments & Roles</h5>
          <button class="btn btn-primary admin-only" id="addDepartmentBtn">
            <i class="fas fa-plus me-1"></i>Add new department
          </button>
        </div>
        <div id="departmentsList">
          <!-- Will be populated by JavaScript -->
        </div>
      </div>
    </div>

    <!-- Admin Settings Tab -->
    <div class="tab-pane fade admin-only" id="pane-admins">
      <div class="p-3">
        <div class="alert alert-warning">
          <i class="fas fa-shield-alt me-2"></i>Only admins can modify admin privileges.
        </div>
        <div id="adminList"></div>
      </div>
    </div>
  </div>
</div>

<!-- Add Employee Modal -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Team Member</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Full Name *</label>
            <input id="empName" class="form-control" placeholder="Enter full name">
          </div>
          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input id="empEmail" type="email" class="form-control" placeholder="Enter email address">
          </div>
          <div class="col-md-6">
            <label class="form-label">Role/Position *</label>
            <input id="empRole" class="form-control" placeholder="e.g. Support Worker, Coordinator">
          </div>
          <div class="col-md-6">
            <label class="form-label">Department</label>
            <select id="empDepartment" class="form-select">
              <option value="">Select Department</option>
              <option value="General">General</option>
              <option value="Support">Support</option>
              <option value="Administration">Administration</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Hourly Wage</label>
            <div class="input-group">
              <span class="input-group-text">$</span>
              <input id="empWage" type="number" step="0.01" class="form-control" placeholder="0.00">
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label">Access Level</label>
            <select id="empAccessLevel" class="form-select">
              <option value="Employee">Employee</option>
              <option value="Manager">Manager</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="saveEmployeeBtn">Add Team Member</button>
      </div>
    </div>
  </div>
</div>

<!-- Shift Modal -->
<div class="modal fade" id="shiftModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Shift</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
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
        <div class="row g-2">
          <div class="col-6">
            <label class="form-label">Start Time</label>
            <input id="startDt" type="datetime-local" class="form-control">
          </div>
          <div class="col-6">
            <label class="form-label">End Time</label>
            <input id="endDt" type="datetime-local" class="form-control">
          </div>
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
          <textarea id="notes" class="form-control" rows="2" placeholder="Add any notes or special instructions..."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button id="saveShiftBtn" class="btn btn-primary">Save Shift</button>
      </div>
    </div>
  </div>
</div>

<style>
.schedule-table {
  border-collapse: separate;
  border-spacing: 0;
}

.schedule-table th:first-child,
.schedule-table td:first-child {
  position: sticky;
  left: 0;
  background: #fff;
  z-index: 10;
  border-right: 2px solid #dee2e6;
  min-width: 200px;
}

.schedule-table th {
  background: #f8f9fa;
  border-bottom: 2px solid #dee2e6;
  font-weight: 600;
  text-align: center;
  vertical-align: middle;
  min-width: 160px;
}

.employee-cell {
  padding: 12px;
  background: #fff;
  border-bottom: 1px solid #e9ecef;
}

.employee-info {
  display: flex;
  align-items: center;
  gap: 10px;
}

.employee-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: bold;
  font-size: 14px;
}

.employee-details h6 {
  margin: 0;
  font-weight: 600;
  color: #2c3e50;
}

.employee-details small {
  color: #6c757d;
  font-size: 0.85rem;
}

.shift-block {
  background: #4a90e2;
  color: white;
  border-radius: 6px;
  padding: 8px 10px;
  margin: 2px 0;
  font-size: 0.85rem;
  position: relative;
  cursor: pointer;
  transition: all 0.2s ease;
}

.shift-block:hover {
  transform: translateY(-1px);
  box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.shift-block.coordinator { background: #5d4e75; }
.shift-block.support { background: #f39c12; }
.shift-block.volunteer { background: #27ae60; }
.shift-block.admin { background: #e74c3c; }

.shift-time {
  font-weight: 600;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.shift-duration {
  font-size: 0.75rem;
  opacity: 0.9;
}

.shift-notes {
  font-size: 0.75rem;
  margin-top: 4px;
  opacity: 0.9;
}

.add-shift-btn {
  width: 100%;
  min-height: 40px;
  border: 2px dashed #dee2e6;
  background: transparent;
  color: #6c757d;
  border-radius: 4px;
  transition: all 0.2s ease;
}

.add-shift-btn:hover {
  border-color: #4a90e2;
  color: #4a90e2;
  background: rgba(74, 144, 226, 0.05);
}

.day-header {
  text-align: center;
  padding: 15px 8px;
}

.day-name {
  font-weight: 600;
  font-size: 0.9rem;
  color: #2c3e50;
}

.day-date {
  font-size: 0.85rem;
  color: #7f8c8d;
  margin-top: 2px;
}

.team-member-card {
  border: 1px solid #e9ecef;
  border-radius: 8px;
  padding: 16px;
  transition: all 0.2s ease;
}

.team-member-card:hover {
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  transform: translateY(-1px);
}

.wage-badge {
  background: #28a745;
  color: white;
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 0.75rem;
  font-weight: 600;
}

.status-active {
  color: #28a745;
  font-weight: 600;
}

.status-inactive {
  color: #dc3545;
  font-weight: 600;
}

@media (max-width: 768px) {
  .schedule-table th,
  .schedule-table td {
    min-width: 120px;
  }

  .employee-avatar {
    width: 32px;
    height: 32px;
    font-size: 12px;
  }

  .shift-block {
    padding: 6px 8px;
    font-size: 0.8rem;
  }
}
</style>

<script>
const api=(a,o={})=>fetch(`/schedule/api?a=${encodeURIComponent(a)}`,{headers:{'Content-Type':'application/json'},...o}).then(r=>r.json());
const $=s=>document.querySelector(s), $$=s=>Array.from(document.querySelectorAll(s));
let employees=[],shifts=[],currentWeekStart=null,is_admin=0,shiftModal,addEmployeeModal,copyShiftData=null;

function iso(d){return d.toISOString().slice(0,10)}
function mondayOf(s){const d=new Date(s);const k=(d.getDay()+6)%7;d.setDate(d.getDate()-k);return iso(d)}
function daysOfWeek(){const s=new Date(currentWeekStart);return[...Array(7)].map((_,i)=>{const d=new Date(s);d.setDate(d.getDate()+i);return{iso:iso(d),label:d.toLocaleDateString(undefined,{weekday:'long'}),shortLabel:d.toLocaleDateString(undefined,{weekday:'short'}),dateLabel:d.toLocaleDateString(undefined,{month:'short',day:'numeric'})}})}
function EH(s){return (s??'').toString().replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]))}

function getInitials(name) {
  return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
}

function getShiftColor(role) {
  const roleColors = {
    'Coordinator': 'coordinator',
    'Support Worker': 'support',
    'Volunteer': 'volunteer',
    'Admin': 'admin'
  };
  return roleColors[role] || 'support';
}

function navigateWeek(direction) {
  const currentDate = new Date($('#weekInput').value);
  currentDate.setDate(currentDate.getDate() + (direction * 7));
  $('#weekInput').value = iso(currentDate);
  loadWeek();
}

function goToCurrentWeek() {
  $('#weekInput').value = iso(new Date());
  loadWeek();
}

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
        <div class="border rounded p-3">
          <div class="fw-bold">${EH(name)}</div>
          <small class="text-muted">${EH(data.role)}</small>
          <div class="mt-2">
            <span class="badge bg-success me-1">${data.hours.toFixed(1)}h</span>
            <span class="badge bg-info">${data.shifts} shifts</span>
          </div>
        </div>
      </div>
    `).join('')}
  `;
}

function renderTeamRoster() {
  const tbody = $('#teamRosterTable');
  const searchTerm = $('#searchTeam').value.toLowerCase();
  const roleFilter = $('#filterRole').value;
  const statusFilter = $('#filterStatus').value;

  let filteredEmployees = employees.filter(emp => {
    const matchesSearch = emp.name.toLowerCase().includes(searchTerm) || 
                         (emp.email && emp.email.toLowerCase().includes(searchTerm));
    const matchesRole = !roleFilter || emp.role === roleFilter;
    const matchesStatus = !statusFilter || 
                         (statusFilter === 'active' && emp.is_active) ||
                         (statusFilter === 'inactive' && !emp.is_active);

    return matchesSearch && matchesRole && matchesStatus;
  });

  tbody.innerHTML = filteredEmployees.map(emp => `
    <tr>
      <td>
        <div class="d-flex align-items-center gap-2">
          <div class="employee-avatar">${getInitials(emp.name)}</div>
          <div>
            <div class="fw-semibold">${EH(emp.name)}</div>
            <small class="text-muted">ID: ${emp.id}</small>
          </div>
        </div>
      </td>
      <td>
        ${emp.email ? `
          <div>${EH(emp.email)}</div>
          <small class="text-muted">Add phone</small>
        ` : '<small class="text-muted">Add email</small>'}
      </td>
      <td>Employee</td>
      <td>AUSU</td>
      <td>${EH(emp.role || emp.role_title || 'Staff')}</td>
      <td>
        ${emp.wage ? `<span class="wage-badge">$${emp.wage}/hr</span>` : '<small class="text-muted">Add wage</small>'}
      </td>
      <td>
        <span class="${emp.is_active ? 'status-active' : 'status-inactive'}">
          ${emp.is_active ? 'Active' : 'Inactive'}
        </span>
      </td>
      <td class="admin-only">
        <div class="btn-group btn-group-sm">
          <button class="btn btn-outline-secondary edit-emp" data-id="${emp.id}" title="Edit">
            <i class="fas fa-edit"></i>
          </button>
          <button class="btn btn-outline-danger del-emp" data-id="${emp.id}" title="Delete">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </td>
    </tr>
  `).join('');

  $('#teamCount').textContent = `${filteredEmployees.length} team members`;
}

document.addEventListener('DOMContentLoaded', async ()=>{
  const today=iso(new Date());
  $('#weekInput').value=today;
  shiftModal=new bootstrap.Modal(document.getElementById('shiftModal'));
  addEmployeeModal=new bootstrap.Modal(document.getElementById('addEmployeeModal'));

  console.log('Schedule app loaded');
  console.log('Admin status check:', <?php echo json_encode([
            'is_admin_session' => $_SESSION['is_admin'] ?? 'not set',
            'username' => $_SESSION['username'] ?? 'not set',
            'auth' => $_SESSION['auth'] ?? 'not set'
        ]); ?>);
  await loadEmployees();
  await loadWeek();

  $('#saveEmployeeBtn').onclick=addEmployee;
  $('#weekInput').onchange=loadWeek;
  $('#publishBtn').onclick=togglePublish;
  $('#searchTeam').oninput=renderTeamRoster;
  $('#filterRole').onchange=renderTeamRoster;
  $('#filterStatus').onchange=renderTeamRoster;

  document.querySelector('[data-bs-target="#pane-admins"]').addEventListener('shown.bs.tab', loadAdmins, {once:true});
  document.querySelector('[data-bs-target="#pane-roster"]').addEventListener('shown.bs.tab', renderTeamRoster);
  document.getElementById('saveShiftBtn').onclick=saveShift;
});

async function loadEmployees(){ 
  employees=await api('employees.list'); 
  renderTeamRoster();

  // Update role filter options
  const roles = [...new Set(employees.map(emp => emp.role))];
  $('#filterRole').innerHTML = '<option value="">All Roles</option>' + 
    roles.map(role => `<option value="${EH(role)}">${EH(role)}</option>`).join('');
}

async function addEmployee(){
  if(!is_admin) return alert('Admin only');
  const name=$('#empName').value.trim(); 
  if(!name) return alert('Name required');

  const email=$('#empEmail').value.trim()||null; 
  const role=$('#empRole').value.trim()||'Staff';
  const department=$('#empDepartment').value||null;
  const wage=$('#empWage').value||null;

  try {
    const response = await fetch('/schedule/api?a=employees.create',{
      method:'POST',
      headers:{'Content-Type':'application/json'},
      body:JSON.stringify({name,email,role,department,wage})
    });

    const result = await response.json();

    if (!response.ok || result.error) {
      alert('Error: ' + (result.error || 'Failed to add team member'));
      return;
    }

    // Clear form
    $('#empName').value=''; 
    $('#empEmail').value=''; 
    $('#empRole').value='';
    $('#empDepartment').value='';
    $('#empWage').value='';

    addEmployeeModal.hide();
    await loadEmployees();
    showToast('success', 'Success', 'Team member added successfully');
  } catch (error) {
    console.error('Error adding employee:', error);
    alert('Failed to add team member. Please try again.');
  }
}

async function loadWeek(){
  currentWeekStart=mondayOf($('#weekInput').value || iso(new Date()));
  const j=await api('shifts.week&week='+currentWeekStart);
  shifts=j.shifts||[]; 
  is_admin=+j.is_admin||0;

  $$('.admin-only').forEach(el=> el.style.display = is_admin ? '' : 'none');
  document.getElementById('roNote').classList.toggle('d-none', !!is_admin);

  renderSchedule(); 
  await loadPublishStatus();
}

function renderSchedule(){
  const thead=$('#schedTable thead'), tbody=$('#schedTable tbody'), days=daysOfWeek();

  // Update week range
  const start = new Date(currentWeekStart);
  const end = new Date(start);
  end.setDate(end.getDate() + 6);
  $('#weekRange').textContent = `${start.toLocaleDateString()} - ${end.toLocaleDateString()}`;
  $('#weekRangeTitle').textContent = `Schedule - ${start.toLocaleDateString('en-US', {month: 'long', day: 'numeric'})} - ${end.toLocaleDateString('en-US', {month: 'long', day: 'numeric', year: 'numeric'})}`;

  thead.innerHTML=`<tr>
    <th class="employee-cell">Team Member</th>
    ${days.map(d=>`
      <th class="day-header">
        <div class="day-name">${d.label}</div>
        <div class="day-date">${d.dateLabel}</div>
      </th>
    `).join('')}
  </tr>`;

  tbody.innerHTML='';

  employees.filter(e=>e.is_active).sort((a,b)=>a.name.localeCompare(b.name)).forEach(emp=>{
    const tr=document.createElement('tr');

    // Employee info cell
    const empCell=document.createElement('td');
    empCell.className='employee-cell';
    empCell.innerHTML=`
      <div class="employee-info">
        <div class="employee-avatar">${getInitials(emp.name)}</div>
        <div class="employee-details">
          <h6>${EH(emp.name)}</h6>
          <small>${EH(emp.role)}</small>
        </div>
      </div>
    `;
    tr.appendChild(empCell);

    // Day cells
    days.forEach(d=>{
      const td=document.createElement('td');
      td.style.padding='8px';
      td.style.verticalAlign='top';

      const dayShifts=shifts.filter(s=> s.employee_id===emp.id && s.start_dt.slice(0,10)===d.iso);

      dayShifts.forEach(s=>{
        const div=document.createElement('div');
        div.className=`shift-block ${getShiftColor(emp.role)}`;

        const startTime=s.start_dt.slice(11,16);
        const endTime=s.end_dt.slice(11,16);
        const start = new Date(s.start_dt);
        const end = new Date(s.end_dt);
        const hours = ((end - start) / (1000 * 60 * 60)).toFixed(1);

        div.innerHTML=`
          <div class="shift-time">
            <span>${startTime}–${endTime}</span>
            <span class="shift-duration">${hours}h</span>
          </div>
          ${s.notes ? `<div class="shift-notes">${EH(s.notes)}</div>` : ''}
        `;

        if(is_admin){
          div.innerHTML += `
            <div class="btn-group btn-group-sm mt-1 w-100">
              <button class="btn btn-outline-light btn-sm copy-shift" data-shift='${JSON.stringify(s)}' title="Copy shift">
                <i class="fas fa-copy"></i>
              </button>
              <button class="btn btn-outline-light btn-sm del-shift" data-id="${s.id}" title="Delete shift">
                <i class="fas fa-trash"></i>
              </button>
            </div>
          `;
        }

        td.appendChild(div);
      });

      if(is_admin){ 
        const addBtn=document.createElement('button'); 
        addBtn.className='btn add-shift-btn'; 
        addBtn.innerHTML='<i class="fas fa-plus"></i> Add'; 
        addBtn.onclick=()=>openModal(emp.id,d.iso); 
        td.appendChild(addBtn); 
      }

      tr.appendChild(td);
    });

    tbody.appendChild(tr);
  });

  renderWeeklySummary();

  tbody.onclick=async ev=>{ 
    const del=ev.target.closest('.del-shift'); 
    if(del){ 
      if(!confirm('Delete this shift?')) return; 
      await fetch(`/schedule/api?a=shifts.delete&id=${del.dataset.id}`); 
      await loadWeek(); 
      return;
    }

    const copy=ev.target.closest('.copy-shift');
    if(copy){
      const shift = JSON.parse(copy.dataset.shift);
      copyShiftData = shift;
      showToast('info', 'Shift Copied', 'Click "Add" on any day to paste this shift');
      return;
    }
  };
}

function openModal(empId,dateIso){
  const emp = employees.find(e => e.id === empId);
  document.querySelector('#shiftModal .modal-title').textContent = `Add Shift - ${emp ? emp.name : 'Employee'}`;
  document.getElementById('saveShiftBtn').dataset.emp=empId;

  if (copyShiftData) {
    const startTime = copyShiftData.start_dt.slice(11,16);
    const endTime = copyShiftData.end_dt.slice(11,16);
    document.getElementById('startDt').value = `${dateIso}T${startTime}`;
    document.getElementById('endDt').value = `${dateIso}T${endTime}`;
    document.getElementById('notes').value = copyShiftData.notes || '';
    copyShiftData = null;
    showToast('success', 'Shift Pasted', 'Shift details have been filled in');
  } else {
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
    const nextDay = new Date(dateIso);
    nextDay.setDate(nextDay.getDate() + 1);
    document.getElementById('endDt').value = `${nextDay.toISOString().slice(0,10)}T${end}`;
  } else {
    document.getElementById('endDt').value = `${dateIso}T${end}`;
  }
}

async function saveShift(){
  const employee_id=+document.getElementById('saveShiftBtn').dataset.emp;
  const start_dt=document.getElementById('startDt').value.replace('T',' ')+':00';
  const end_dt=document.getElementById('endDt').value.replace('T',' ')+':00';
  const breakDuration = +document.getElementById('breakDuration').value;
  let notes = document.getElementById('notes').value.trim();

  if (breakDuration > 0) {
    const hours = ((new Date(end_dt) - new Date(start_dt)) / (1000 * 60 * 60)) - (breakDuration / 60);
    notes += (notes ? '\n' : '') + `Break: ${breakDuration}min, Hours: ${Math.max(0, hours).toFixed(2)}`;
  }

  const r=await fetch('/schedule/api?a=shifts.create',{
    method:'POST',
    headers:{'Content-Type':'application/json'},
    body:JSON.stringify({employee_id,start_dt,end_dt,notes})
  });

  const j=await r.json(); 
  if(j.error){
    alert(j.error);
    return;
  }

  shiftModal.hide(); 
  await loadWeek();
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
  await fetch('/schedule/api?a=publish.set',{
    method:'POST',
    headers:{'Content-Type':'application/json'},
    body:JSON.stringify({week:currentWeekStart,published:!!next})
  });
  await loadPublishStatus();
}

async function loadAdmins(){
  const list=await api('users.list'); 
  const wrap=document.getElementById('adminList'); 
  wrap.innerHTML='';

  list.forEach(u=>{
    const row=document.createElement('div'); 
    row.className='border rounded p-3 mb-2 d-flex align-items-center justify-content-between';
    row.innerHTML=`
      <div class="d-flex align-items-center gap-2">
        <div class="employee-avatar">${getInitials(u.username)}</div>
        <div>
          <strong>${EH(u.username)}</strong>
          ${u.full_name?` <span class="text-muted">(${EH(u.full_name)})</span>`:''}
        </div>
      </div>
      <div class="form-check form-switch">
        <input class="form-check-input toggle-admin" type="checkbox" data-id="${u.id}" ${u.is_admin?'checked':''}>
        <label class="form-check-label">Admin Rights</label>
      </div>
    `;
    wrap.appendChild(row);
  });

  wrap.onclick=async ev=>{ 
    const sw=ev.target.closest('.toggle-admin'); 
    if(!sw) return;
    await fetch('/schedule/api?a=users.setAdmin',{
      method:'POST',
      headers:{'Content-Type':'application/json'},
      body:JSON.stringify({id:+sw.dataset.id,is_admin: sw.checked?1:0})
    });
  };
}
</script>

<?php require 'app/views/templates/footer.php'; ?>