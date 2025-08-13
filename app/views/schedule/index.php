
<?php require 'app/views/templates/header.php'; ?>

<style>
/* Homebase exact styling */
.schedule-container {
  background: #f8fafc;
  min-height: 100vh;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.schedule-header {
  background: white;
  border-bottom: 1px solid #e5e7eb;
  padding: 1rem 0;
}

.schedule-title {
  font-size: 1.5rem;
  font-weight: 600;
  color: #111827;
  margin: 0;
}

.schedule-subtitle {
  color: #6b7280;
  font-size: 0.875rem;
  margin: 0;
}

.week-controls {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.week-navigation {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.week-nav-btn {
  background: #f9fafb;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  padding: 0.5rem;
  color: #374151;
  cursor: pointer;
  transition: all 0.2s;
}

.week-nav-btn:hover {
  background: #e5e7eb;
}

.week-display {
  font-weight: 500;
  color: #111827;
  min-width: 200px;
  text-align: center;
}

.publish-section {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.status-indicator {
  padding: 0.375rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 500;
}

.status-draft {
  background: #fef3c7;
  color: #92400e;
}

.status-published {
  background: #d1fae5;
  color: #065f46;
}

.btn-primary {
  background: #3b82f6;
  color: white;
  border: none;
  border-radius: 0.375rem;
  padding: 0.5rem 1rem;
  font-weight: 500;
  font-size: 0.875rem;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-primary:hover {
  background: #2563eb;
}

.schedule-grid {
  background: white;
  border-radius: 0.5rem;
  overflow: hidden;
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
  margin: 1rem;
}

.grid-header {
  display: grid;
  grid-template-columns: 200px repeat(7, 1fr);
  background: #f9fafb;
  border-bottom: 1px solid #e5e7eb;
}

.grid-header-cell {
  padding: 1rem 0.75rem;
  font-weight: 600;
  color: #374151;
  font-size: 0.875rem;
  text-align: center;
  border-right: 1px solid #e5e7eb;
}

.grid-header-cell:first-child {
  text-align: left;
  background: #f3f4f6;
}

.grid-body {
  max-height: 70vh;
  overflow-y: auto;
}

.grid-row {
  display: grid;
  grid-template-columns: 200px repeat(7, 1fr);
  border-bottom: 1px solid #e5e7eb;
  min-height: 120px;
}

.employee-cell {
  background: #f9fafb;
  padding: 1rem 0.75rem;
  border-right: 1px solid #e5e7eb;
  display: flex;
  align-items: flex-start;
  flex-direction: column;
  gap: 0.25rem;
}

.employee-name {
  font-weight: 600;
  color: #111827;
  font-size: 0.875rem;
}

.employee-role {
  color: #6b7280;
  font-size: 0.75rem;
}

.employee-hours {
  color: #6b7280;
  font-size: 0.75rem;
  margin-top: auto;
}

.day-cell {
  padding: 0.5rem;
  border-right: 1px solid #e5e7eb;
  position: relative;
  background: white;
}

.shift-block {
  background: #3b82f6;
  color: white;
  border-radius: 0.375rem;
  padding: 0.5rem;
  margin-bottom: 0.25rem;
  font-size: 0.75rem;
  position: relative;
  cursor: pointer;
  transition: all 0.2s;
}

.shift-block:hover {
  background: #2563eb;
  transform: translateY(-1px);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.shift-time {
  font-weight: 600;
  margin-bottom: 0.25rem;
}

.shift-role {
  opacity: 0.9;
  font-size: 0.7rem;
}

.shift-delete {
  position: absolute;
  top: 0.25rem;
  right: 0.25rem;
  background: rgba(255, 255, 255, 0.2);
  border: none;
  color: white;
  border-radius: 50%;
  width: 20px;
  height: 20px;
  font-size: 0.7rem;
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.2s;
  cursor: pointer;
}

.shift-block:hover .shift-delete {
  opacity: 1;
}

.add-shift-area {
  position: absolute;
  bottom: 0.5rem;
  left: 0.5rem;
  right: 0.5rem;
}

.add-shift-btn {
  width: 100%;
  background: transparent;
  border: 2px dashed #d1d5db;
  color: #6b7280;
  border-radius: 0.375rem;
  padding: 0.5rem;
  font-size: 0.75rem;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.25rem;
}

.add-shift-btn:hover {
  border-color: #3b82f6;
  color: #3b82f6;
  background: #eff6ff;
}

.modal-content {
  border: none;
  border-radius: 0.75rem;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.modal-header {
  border-bottom: 1px solid #e5e7eb;
  padding: 1.5rem;
}

.modal-body {
  padding: 1.5rem;
}

.modal-footer {
  border-top: 1px solid #e5e7eb;
  padding: 1.5rem;
  gap: 0.75rem;
}

.form-group {
  margin-bottom: 1rem;
}

.form-label {
  display: block;
  font-size: 0.875rem;
  font-weight: 500;
  color: #374151;
  margin-bottom: 0.5rem;
}

.form-control {
  width: 100%;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  padding: 0.5rem 0.75rem;
  font-size: 0.875rem;
  transition: border-color 0.2s;
}

.form-control:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.btn-secondary {
  background: #f9fafb;
  color: #374151;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  padding: 0.5rem 1rem;
  font-weight: 500;
  font-size: 0.875rem;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-secondary:hover {
  background: #e5e7eb;
}

.time-input-group {
  display: flex;
  gap: 0.5rem;
  align-items: center;
}

.time-separator {
  color: #6b7280;
  font-weight: 500;
}

.role-select {
  appearance: none;
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
  background-position: right 0.5rem center;
  background-repeat: no-repeat;
  background-size: 1.5em 1.5em;
  padding-right: 2.5rem;
}
</style>

<div class="schedule-container">
  <!-- Header -->
  <div class="schedule-header">
    <div class="container-fluid px-4">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <h1 class="schedule-title">Schedule</h1>
          <p class="schedule-subtitle">Manage your team's work schedule</p>
        </div>
        <div class="week-controls">
          <div class="week-navigation">
            <button class="week-nav-btn" id="prevWeekBtn">
              <i class="fas fa-chevron-left"></i>
            </button>
            <div class="week-display" id="weekDisplay">
              Week of Mon, 28 - Sun, 4
            </div>
            <button class="week-nav-btn" id="nextWeekBtn">
              <i class="fas fa-chevron-right"></i>
            </button>
          </div>
          <div class="publish-section">
            <span class="status-indicator status-draft" id="statusIndicator">Draft</span>
            <button class="btn-primary" id="publishBtn">Publish (0)</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Schedule Grid -->
  <div class="schedule-grid">
    <div class="grid-header">
      <div class="grid-header-cell">Team members (0)</div>
      <div class="grid-header-cell" data-day="0">Mon, 28</div>
      <div class="grid-header-cell" data-day="1">Tue, 29</div>
      <div class="grid-header-cell" data-day="2">Wed, 30</div>
      <div class="grid-header-cell" data-day="3">Thu, 1</div>
      <div class="grid-header-cell" data-day="4">Fri, 2</div>
      <div class="grid-header-cell" data-day="5">Sat, 3</div>
      <div class="grid-header-cell" data-day="6">Sun, 4</div>
    </div>
    <div class="grid-body" id="scheduleGridBody">
      <!-- Employees and shifts will be rendered here -->
    </div>
  </div>
</div>

<!-- Add Shift Modal -->
<div class="modal fade" id="shiftModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Shift</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Time</label>
          <div class="time-input-group">
            <input type="time" id="startTime" class="form-control" value="09:00">
            <span class="time-separator">-</span>
            <input type="time" id="endTime" class="form-control" value="17:00">
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Role</label>
          <select id="shiftRole" class="form-control role-select">
            <option>Support Worker</option>
            <option>Coordinator</option>
            <option>Manager</option>
            <option>Supervisor</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Apply to:</label>
          <div class="d-flex gap-2 flex-wrap mt-2">
            <button type="button" class="btn btn-secondary btn-sm day-selector" data-day="1">Mon</button>
            <button type="button" class="btn btn-secondary btn-sm day-selector" data-day="2">Tue</button>
            <button type="button" class="btn btn-secondary btn-sm day-selector" data-day="3">Wed</button>
            <button type="button" class="btn btn-secondary btn-sm day-selector" data-day="4">Thu</button>
            <button type="button" class="btn btn-secondary btn-sm day-selector" data-day="5">Fri</button>
            <button type="button" class="btn btn-secondary btn-sm day-selector" data-day="6">Sat</button>
            <button type="button" class="btn btn-secondary btn-sm day-selector" data-day="0">Sun</button>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Shift Notes:</label>
          <textarea id="shiftNotes" class="form-control" rows="3" placeholder="Leave a note for your employee, like the address of a job site, and they'll see it when they clock in."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="saveShiftBtn">Add</button>
      </div>
    </div>
  </div>
</div>

<?php require_once 'app/views/templates/footer.php'; ?>

<script>
console.log('[schedule] Homebase-style script loaded');

// API helper
const api = (action, options = {}) => {
  return fetch(`/schedule/api?a=${encodeURIComponent(action)}`, {
    headers: {'Content-Type': 'application/json'},
    ...options
  }).then(r => {
    if (!r.ok) throw new Error(`HTTP ${r.status}`);
    return r.json();
  });
};

// Global state
let employees = [];
let shifts = [];
let currentWeekStart = null;
let isAdmin = false;
let shiftModal;
let currentEmployee = null;
let currentDate = null;
let selectedDays = new Set();

// Date helpers
function mondayOf(dateStr) {
  const d = new Date(dateStr + 'T12:00:00'); // Add time to avoid timezone issues
  const dayOfWeek = d.getDay();
  const daysFromMonday = dayOfWeek === 0 ? 6 : dayOfWeek - 1;
  d.setDate(d.getDate() - daysFromMonday);
  return d.toISOString().slice(0, 10);
}

function formatWeekDisplay(mondayStr) {
  const monday = new Date(mondayStr + 'T12:00:00');
  const sunday = new Date(monday);
  sunday.setDate(sunday.getDate() + 6);
  
  const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                      'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
  
  const mondayMonth = monthNames[monday.getMonth()];
  const sundayMonth = monthNames[sunday.getMonth()];
  
  if (monday.getMonth() === sunday.getMonth()) {
    return `Week of ${monday.toLocaleDateString('en-US', {weekday: 'short'})}, ${mondayMonth} ${monday.getDate()} - ${sunday.toLocaleDateString('en-US', {weekday: 'short'})}, ${sunday.getDate()}`;
  } else {
    return `Week of ${monday.toLocaleDateString('en-US', {weekday: 'short'})}, ${mondayMonth} ${monday.getDate()} - ${sunday.toLocaleDateString('en-US', {weekday: 'short'})}, ${sundayMonth} ${sunday.getDate()}`;
  }
}

function getWeekDays(mondayStr) {
  const monday = new Date(mondayStr + 'T12:00:00');
  const days = [];
  
  for (let i = 0; i < 7; i++) {
    const day = new Date(monday);
    day.setDate(day.getDate() + i);
    
    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    
    days.push({
      date: day.toISOString().slice(0, 10),
      display: `${day.toLocaleDateString('en-US', {weekday: 'short'})}, ${monthNames[day.getMonth()]} ${day.getDate()}`
    });
  }
  
  return days;
}

// Initialize
document.addEventListener('DOMContentLoaded', async () => {
  console.log('[schedule] Initializing...');
  
  shiftModal = new bootstrap.Modal(document.getElementById('shiftModal'));
  
  // Set initial week to current week
  const today = new Date();
  const todayStr = today.toISOString().slice(0, 10);
  currentWeekStart = mondayOf(todayStr);
  
  console.log('Today:', todayStr);
  console.log('Current week start:', currentWeekStart);
  
  // Event listeners
  document.getElementById('prevWeekBtn').addEventListener('click', () => {
    console.log('Previous week clicked');
    changeWeek(-7);
  });
  document.getElementById('nextWeekBtn').addEventListener('click', () => {
    console.log('Next week clicked');
    changeWeek(7);
  });
  document.getElementById('publishBtn').addEventListener('click', togglePublish);
  document.getElementById('saveShiftBtn').addEventListener('click', saveShift);
  
  // Day selector buttons
  document.querySelectorAll('.day-selector').forEach(btn => {
    btn.addEventListener('click', (e) => {
      const day = e.target.dataset.day;
      if (selectedDays.has(day)) {
        selectedDays.delete(day);
        e.target.classList.remove('btn-primary');
        e.target.classList.add('btn-secondary');
      } else {
        selectedDays.add(day);
        e.target.classList.remove('btn-secondary');
        e.target.classList.add('btn-primary');
      }
    });
  });
  
  // Load initial data
  console.log('Loading employees...');
  await loadEmployees();
  console.log('Loading week data...');
  await loadWeek();
  
  console.log('[schedule] Initialization complete');
});

function changeWeek(days) {
  const current = new Date(currentWeekStart + 'T12:00:00');
  current.setDate(current.getDate() + days);
  currentWeekStart = current.toISOString().slice(0, 10);
  console.log('Changing to week:', currentWeekStart);
  loadWeek();
}

async function loadEmployees() {
  try {
    const response = await fetch('/schedule/api?a=employees.list', {
      headers: {'Content-Type': 'application/json'}
    });
    
    if (!response.ok) {
      throw new Error(`HTTP ${response.status}: ${response.statusText}`);
    }
    
    const text = await response.text();
    console.log('Raw employees response:', text);
    
    employees = JSON.parse(text);
    console.log('Loaded employees:', employees);
  } catch (e) {
    console.error('Error loading employees:', e);
    console.error('Response text might be HTML instead of JSON');
    employees = [];
  }
}

async function loadWeek() {
  try {
    const data = await api(`shifts.week`, {
      method: 'GET'
    });
    // Add week parameter to URL
    const response = await fetch(`/schedule/api?a=shifts.week&week=${currentWeekStart}`, {
      headers: {'Content-Type': 'application/json'}
    });
    
    if (!response.ok) throw new Error(`HTTP ${response.status}`);
    const weekData = await response.json();
    
    shifts = weekData.shifts || [];
    isAdmin = weekData.is_admin || false;
    
    updateWeekDisplay();
    renderScheduleGrid();
    await loadPublishStatus();
  } catch (e) {
    console.error('Error loading week:', e);
  }
}

function updateWeekDisplay() {
  console.log('Updating week display for:', currentWeekStart);
  
  const weekDisplay = document.getElementById('weekDisplay');
  weekDisplay.textContent = formatWeekDisplay(currentWeekStart);
  
  const days = getWeekDays(currentWeekStart);
  console.log('Week days:', days);
  
  const headerCells = document.querySelectorAll('.grid-header-cell[data-day]');
  
  headerCells.forEach((cell, index) => {
    if (days[index]) {
      cell.textContent = days[index].display;
      console.log(`Day ${index}: ${days[index].display}`);
    }
  });
  
  // Update team members count
  const activeEmployees = employees.filter(emp => emp.is_active !== false);
  const teamHeader = document.querySelector('.grid-header-cell:first-child');
  if (teamHeader) {
    teamHeader.textContent = `Team members (${activeEmployees.length})`;
  }
}

function renderScheduleGrid() {
  const gridBody = document.getElementById('scheduleGridBody');
  gridBody.innerHTML = '';
  
  const activeEmployees = employees.filter(emp => emp.is_active);
  const days = getWeekDays(currentWeekStart);
  
  activeEmployees.forEach(employee => {
    const row = document.createElement('div');
    row.className = 'grid-row';
    
    // Employee cell
    const employeeCell = document.createElement('div');
    employeeCell.className = 'employee-cell';
    
    const employeeShifts = shifts.filter(s => s.employee_id === employee.id);
    const totalHours = calculateTotalHours(employeeShifts);
    
    employeeCell.innerHTML = `
      <div class="employee-name">${escapeHtml(employee.name)}</div>
      <div class="employee-role">${escapeHtml(employee.role)}</div>
      <div class="employee-hours">${totalHours.toFixed(2)} hrs / $${(totalHours * (employee.wage || 0)).toFixed(2)}</div>
    `;
    
    row.appendChild(employeeCell);
    
    // Day cells
    days.forEach((day, dayIndex) => {
      const dayCell = document.createElement('div');
      dayCell.className = 'day-cell';
      
      const dayShifts = employeeShifts.filter(s => s.start_dt.slice(0, 10) === day.date);
      
      // Render existing shifts
      dayShifts.forEach(shift => {
        const shiftBlock = createShiftBlock(shift);
        dayCell.appendChild(shiftBlock);
      });
      
      // Add shift button (admin only)
      if (isAdmin) {
        const addArea = document.createElement('div');
        addArea.className = 'add-shift-area';
        
        const addBtn = document.createElement('button');
        addBtn.className = 'add-shift-btn';
        addBtn.innerHTML = '<i class="fas fa-plus"></i>';
        addBtn.addEventListener('click', () => openShiftModal(employee, day.date));
        
        addArea.appendChild(addBtn);
        dayCell.appendChild(addArea);
      }
      
      row.appendChild(dayCell);
    });
    
    gridBody.appendChild(row);
  });
}

function createShiftBlock(shift) {
  const block = document.createElement('div');
  block.className = 'shift-block';
  
  const startTime = shift.start_dt.slice(11, 16);
  const endTime = shift.end_dt.slice(11, 16);
  
  block.innerHTML = `
    <div class="shift-time">${startTime}-${endTime}</div>
    <div class="shift-role">${escapeHtml(shift.notes || shift.employee_role)}</div>
    ${isAdmin ? `<button class="shift-delete" onclick="deleteShift(${shift.id})">×</button>` : ''}
  `;
  
  return block;
}

function calculateTotalHours(shifts) {
  return shifts.reduce((total, shift) => {
    const start = new Date(shift.start_dt);
    const end = new Date(shift.end_dt);
    const hours = (end - start) / (1000 * 60 * 60);
    return total + hours;
  }, 0);
}

function openShiftModal(employee, date) {
  if (!isAdmin) return;
  
  currentEmployee = employee;
  currentDate = date;
  selectedDays.clear();
  
  // Reset day selector buttons
  document.querySelectorAll('.day-selector').forEach(btn => {
    btn.classList.remove('btn-primary');
    btn.classList.add('btn-secondary');
  });
  
  // Pre-select the clicked day
  const dayOfWeek = new Date(date).getDay();
  const dayBtn = document.querySelector(`[data-day="${dayOfWeek}"]`);
  if (dayBtn) {
    selectedDays.add(dayOfWeek.toString());
    dayBtn.classList.remove('btn-secondary');
    dayBtn.classList.add('btn-primary');
  }
  
  // Reset form
  document.getElementById('startTime').value = '09:00';
  document.getElementById('endTime').value = '17:00';
  document.getElementById('shiftRole').value = employee.role || 'Support Worker';
  document.getElementById('shiftNotes').value = '';
  
  shiftModal.show();
}

async function saveShift() {
  if (!currentEmployee || selectedDays.size === 0) return;
  
  const startTime = document.getElementById('startTime').value;
  const endTime = document.getElementById('endTime').value;
  const role = document.getElementById('shiftRole').value;
  const notes = document.getElementById('shiftNotes').value;
  
  if (!startTime || !endTime) {
    alert('Please select start and end times');
    return;
  }
  
  try {
    const baseDate = new Date(currentWeekStart);
    
    for (const dayStr of selectedDays) {
      const day = parseInt(dayStr);
      const shiftDate = new Date(baseDate);
      shiftDate.setDate(shiftDate.getDate() + day);
      
      const startDt = `${shiftDate.toISOString().slice(0, 10)} ${startTime}:00`;
      const endDt = `${shiftDate.toISOString().slice(0, 10)} ${endTime}:00`;
      
      await api('shifts.create', {
        method: 'POST',
        body: JSON.stringify({
          employee_id: currentEmployee.id,
          start_dt: startDt,
          end_dt: endDt,
          notes: notes || role
        })
      });
    }
    
    shiftModal.hide();
    await loadWeek();
  } catch (e) {
    console.error('Error saving shift:', e);
    alert('Error saving shift: ' + e.message);
  }
}

async function deleteShift(shiftId) {
  if (!isAdmin || !confirm('Delete this shift?')) return;
  
  try {
    const response = await fetch(`/schedule/api?a=shifts.delete&id=${shiftId}`, {
      method: 'GET',
      headers: {'Content-Type': 'application/json'}
    });
    
    if (!response.ok) throw new Error(`HTTP ${response.status}`);
    await response.json();
    await loadWeek();
  } catch (e) {
    console.error('Error deleting shift:', e);
    alert('Error deleting shift: ' + e.message);
  }
}

async function loadPublishStatus() {
  try {
    const response = await fetch(`/schedule/api?a=publish.status&week=${currentWeekStart}`, {
      headers: {'Content-Type': 'application/json'}
    });
    
    if (!response.ok) throw new Error(`HTTP ${response.status}`);
    const status = await response.json();
    
    const indicator = document.getElementById('statusIndicator');
    const publishBtn = document.getElementById('publishBtn');
    
    if (status.published) {
      indicator.textContent = 'Published';
      indicator.className = 'status-indicator status-published';
      publishBtn.textContent = 'Unpublish (0)';
    } else {
      indicator.textContent = 'Draft';
      indicator.className = 'status-indicator status-draft';
      publishBtn.textContent = 'Publish (0)';
    }
    
    publishBtn.style.display = isAdmin ? 'block' : 'none';
  } catch (e) {
    console.error('Error loading publish status:', e);
  }
}

async function togglePublish() {
  if (!isAdmin) return;
  
  try {
    const status = await api(`publish.status&week=${currentWeekStart}`);
    const newStatus = !status.published;
    
    await api('publish.set', {
      method: 'POST',
      body: JSON.stringify({
        week: currentWeekStart,
        published: newStatus
      })
    });
    
    await loadPublishStatus();
  } catch (e) {
    console.error('Error toggling publish status:', e);
    alert('Error updating publish status: ' + e.message);
  }
}

function escapeHtml(text) {
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML;
}

// Make deleteShift globally available
window.deleteShift = deleteShift;
</script>
