<?php require 'app/views/templates/header.php';
require 'app/views/templates/spinner.php';
?>

<style>
:root {
  --primary: #09194D;
  --primary-light: #1A2A6C;
  --secondary: #D97F76;
  --light: #E4E4EF;
  --lighter: #F4F5F0;
  --neutral: #9B9498;
  --accent: #B59E5F;
  --accent-secondary: #8D77AB;
  --success: #10b981;
  --warning: #f59e0b;
  --danger: #ef4444;
  --info: #3b82f6;
  --card: #ffffff;
  --ink: var(--primary);
  --muted: var(--neutral);
  --border: #E0E0E8;
  --shadow: 0 2px 8px rgba(9, 25, 77, 0.06);
  --shadow-lg: 0 8px 24px rgba(9, 25, 77, 0.1);
  --radius: 12px;
  --radius-lg: 16px;
}

* { box-sizing: border-box; margin: 0; padding: 0; }

body {
  background: linear-gradient(135deg, var(--lighter) 0%, var(--light) 100%);
  color: var(--ink);
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Inter', sans-serif;
  min-height: 100vh;
  line-height: 1.6;
}

.page-wrap { 
  padding: 1rem 0;
  min-height: 100vh;
}

.page-header {
  margin-bottom: 1.5rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  flex-wrap: wrap;
}

.page-title {
  font-size: clamp(1.5rem, 4vw, 2rem);
  font-weight: 700;
  color: var(--primary);
  margin: 0;
}

.page-subtitle {
  color: var(--muted);
  font-size: 0.875rem;
  margin-top: 0.25rem;
}

.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: var(--radius);
  padding: 0.75rem 1.5rem;
  font-weight: 600;
  font-size: 0.875rem;
  border: none;
  cursor: pointer;
  transition: all 0.2s ease;
  gap: 0.5rem;
  white-space: nowrap;
  text-decoration: none;
  line-height: 1.5;
  min-height: 44px; /* Touch-friendly */
}

.btn:hover { transform: translateY(-1px); }
.btn:active { transform: translateY(0); }
.btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  transform: none !important;
}

.btn-primary {
  background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
  color: white;
  box-shadow: 0 2px 8px rgba(9, 25, 77, 0.2);
}

.btn-success {
  background: linear-gradient(135deg, var(--success) 0%, #0da271 100%);
  color: white;
}

.btn-outline {
  background: white;
  border: 1.5px solid var(--border);
  color: var(--primary);
}

.btn-outline:hover {
  border-color: var(--primary);
  background: var(--lighter);
}

.card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-lg);
  overflow: hidden;
  margin-bottom: 1.5rem;
}

.card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: linear-gradient(90deg, var(--accent), var(--accent-secondary));
}

.search-bar {
  padding: 1rem;
  border-bottom: 1px solid var(--border);
  background: rgba(244, 245, 240, 0.3);
}

.search-input {
  width: 100%;
  padding: 0.75rem 1rem 0.75rem 2.75rem;
  border: 1.5px solid var(--border);
  border-radius: var(--radius);
  font-size: 0.875rem;
  background: white url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='%239B9498' d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3e%3c/svg%3e") no-repeat 0.75rem center;
  background-size: 20px 20px;
}

.search-input:focus {
  outline: none;
  border-color: var(--accent);
  box-shadow: 0 0 0 3px rgba(181, 158, 95, 0.1);
}

.dept-section {
  border-bottom: 1px solid var(--border);
}

.dept-section:last-child {
  border-bottom: none;
}

.dept-header {
  padding: 1rem 1.5rem;
  background: rgba(244, 245, 240, 0.4);
  font-weight: 700;
  color: var(--primary);
  font-size: 0.875rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.dept-count {
  background: var(--accent);
  color: white;
  padding: 0.25rem 0.625rem;
  border-radius: 12px;
  font-size: 0.75rem;
  font-weight: 600;
}

.user-list {
  list-style: none;
}

.user-item {
  padding: 1rem 1.5rem;
  border-bottom: 1px solid var(--border);
  display: flex;
  align-items: center;
  gap: 1rem;
  cursor: pointer;
  transition: background 0.2s ease;
}

.user-item:hover {
  background: rgba(244, 245, 240, 0.5);
}

.user-item:last-child {
  border-bottom: none;
}

.avatar {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--accent), var(--accent-secondary));
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 700;
  font-size: 1rem;
  flex-shrink: 0;
}

.user-info {
  flex: 1;
  min-width: 0;
}

.user-name {
  font-weight: 600;
  color: var(--primary);
  margin-bottom: 0.125rem;
}

.user-role {
  font-size: 0.8125rem;
  color: var(--muted);
}

.badge {
  display: inline-flex;
  align-items: center;
  padding: 0.375rem 0.75rem;
  font-size: 0.75rem;
  font-weight: 600;
  line-height: 1;
  border-radius: 6px;
  border: 1px solid;
  white-space: nowrap;
}

.badge-success { background: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.2); color: var(--success); }
.badge-danger { background: rgba(239, 68, 68, 0.1); border-color: rgba(239, 68, 68, 0.2); color: var(--danger); }
.badge-warning { background: rgba(245, 158, 11, 0.1); border-color: rgba(245, 158, 11, 0.2); color: var(--warning); }
.badge-info { background: rgba(59, 130, 246, 0.1); border-color: rgba(59, 130, 246, 0.2); color: var(--info); }
.badge-secondary { background: rgba(155, 148, 152, 0.1); border-color: rgba(155, 148, 152, 0.2); color: var(--muted); }
.badge-dark { background: rgba(9, 25, 77, 0.1); border-color: rgba(9, 25, 77, 0.2); color: var(--primary); }

/* Edit Form - Mobile First, Single Column */
.edit-panel {
  position: fixed;
  top: 0;
  right: -100%;
  width: 100%;
  height: 100%;
  background: white;
  z-index: 1050;
  transition: right 0.3s ease;
  display: flex;
  flex-direction: column;
}

.edit-panel.show {
  right: 0;
}

.edit-header {
  padding: 1.25rem 1.5rem;
  border-bottom: 1px solid var(--border);
  background: rgba(244, 245, 240, 0.3);
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.edit-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--primary);
  margin: 0;
}

.edit-body {
  flex: 1;
  overflow-y: auto;
  padding: 1.5rem;
}

.form-section {
  margin-bottom: 2rem;
}

.section-title {
  font-size: 0.875rem;
  font-weight: 700;
  color: var(--primary);
  text-transform: uppercase;
  letter-spacing: 0.05em;
  margin-bottom: 1rem;
  padding-bottom: 0.5rem;
  border-bottom: 2px solid var(--accent);
}

.form-group {
  margin-bottom: 1.25rem;
}

.form-label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 600;
  color: var(--primary);
  font-size: 0.875rem;
}

.form-control, .form-select {
  width: 100%;
  padding: 0.75rem 1rem;
  border: 1.5px solid var(--border);
  border-radius: var(--radius);
  font-size: 0.875rem;
  background: white;
  color: var(--primary);
  transition: all 0.2s ease;
  min-height: 44px; /* Touch-friendly */
}

.form-control:focus, .form-select:focus {
  outline: none;
  border-color: var(--accent);
  box-shadow: 0 0 0 3px rgba(181, 158, 95, 0.1);
}

.form-select {
  padding-right: 2.5rem;
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2309194D' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 0.75rem center;
  background-size: 16px 12px;
  appearance: none;
}

.form-text {
  font-size: 0.75rem;
  color: var(--muted);
  margin-top: 0.375rem;
}

.error-message {
  color: var(--danger);
  font-size: 0.8125rem;
  margin-top: 0.375rem;
  display: none;
}

.error-message.show {
  display: block;
}

/* Sticky Bottom Bar */
.sticky-footer {
  padding: 1rem 1.5rem;
  border-top: 1px solid var(--border);
  background: white;
  display: flex;
  gap: 0.75rem;
  box-shadow: 0 -4px 12px rgba(9, 25, 77, 0.08);
}

.sticky-footer .btn {
  flex: 1;
}

/* Toast Notifications */
.toast-container {
  position: fixed;
  top: 1rem;
  right: 1rem;
  z-index: 1100;
  max-width: 90%;
}

.toast {
  background: white;
  border-radius: var(--radius);
  padding: 1rem 1.25rem;
  box-shadow: 0 4px 16px rgba(9, 25, 77, 0.15);
  margin-bottom: 0.75rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  border-left: 4px solid;
  animation: slideIn 0.3s ease;
  min-width: 280px;
}

.toast-success { border-left-color: var(--success); }
.toast-error { border-left-color: var(--danger); }

.toast-icon {
  font-size: 1.25rem;
}

.toast-message {
  flex: 1;
  font-size: 0.875rem;
  font-weight: 500;
}

@keyframes slideIn {
  from { transform: translateX(100%); opacity: 0; }
  to { transform: translateX(0); opacity: 1; }
}

.empty-state {
  padding: 3rem 1.5rem;
  text-align: center;
  color: var(--muted);
}

.empty-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
  opacity: 0.5;
}

@media (min-width: 768px) {
  .edit-panel {
    right: -450px;
    width: 450px;
    box-shadow: -4px 0 16px rgba(9, 25, 77, 0.1);
  }
  
  .edit-panel.show {
    right: 0;
  }
}
</style>

<div class="page-wrap">
  <div class="container-fluid px-3 px-md-4 py-3">
    <div class="page-header">
      <div>
        <h1 class="page-title">Team Roster</h1>
        <p class="page-subtitle">View and manage all team members</p>
      </div>
      <button class="btn btn-primary" id="btnAdd" style="display: none;">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
          <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
        </svg>
        Add Member
      </button>
    </div>

    <div class="card">
      <div class="search-bar">
        <input type="text" id="searchInput" class="search-input" placeholder="Search by name, email, or username...">
      </div>
      <div id="userList"></div>
      <div id="emptyState" class="empty-state" style="display: none;">
        <div class="empty-icon">👥</div>
        <div>No team members found</div>
      </div>
    </div>
  </div>
</div>

<!-- Edit Panel -->
<div class="edit-panel" id="editPanel">
  <div class="edit-header">
    <h2 class="edit-title" id="editTitle">Edit User</h2>
    <button class="btn btn-outline" onclick="closeEditPanel()" style="padding: 0.5rem 1rem;">✕</button>
  </div>
  <div class="edit-body">
    <input type="hidden" id="edit_user_id">
    
    <!-- Profile Section -->
    <div class="form-section">
      <div class="section-title">Profile</div>
      <div class="form-group">
        <label class="form-label">Full Name</label>
        <input type="text" class="form-control" id="edit_name">
        <div class="error-message" id="error_name">Name is required</div>
      </div>
      <div class="form-group">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" id="edit_email">
      </div>
    </div>

    <!-- Access Section -->
    <div class="form-section">
      <div class="section-title">Access</div>
      <div class="form-group">
        <label class="form-label">Access Level</label>
        <select class="form-select" id="edit_access_level">
          <option value="0">Level 0 - Inactive</option>
          <option value="1">Level 1 - Full Admin</option>
          <option value="2">Level 2 - Power User</option>
          <option value="3">Level 3 - Team Lead</option>
          <option value="4">Level 4 - Department Admin</option>
        </select>
        <div class="form-text" id="access_description"></div>
      </div>
    </div>

    <!-- Department & Role Section -->
    <div class="form-section">
      <div class="section-title">Department & Role</div>
      <div class="form-group">
        <label class="form-label">Department</label>
        <select class="form-select" id="edit_department">
          <option value="">No department</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Role</label>
        <select class="form-select" id="edit_role">
          <option value="">No role</option>
        </select>
      </div>
    </div>

    <!-- Status Section -->
    <div class="form-section">
      <div class="section-title">Status</div>
      <div class="form-group">
        <label class="form-label">Status</label>
        <select class="form-select" id="edit_status">
          <option value="1">Active</option>
          <option value="0">Inactive</option>
        </select>
      </div>
    </div>
  </div>
  <div class="sticky-footer">
    <button class="btn btn-outline" onclick="closeEditPanel()">Cancel</button>
    <button class="btn btn-success" onclick="saveUser()">Save</button>
  </div>
</div>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<?php require 'app/views/templates/footer.php'; ?>

<script>
let DATA = {
  roster: [],
  departments: [],
  roles: [],
  accessLevel: 1,
  userDepartmentIds: []
};

const ACCESS_DESCRIPTIONS = {
  0: 'Cannot login',
  1: 'Full access to all features and departments',
  2: 'Dashboard, Chat, Time Clock, My Shifts, Reminders',
  3: 'Dashboard, Chat, Team Roster, Schedule, Reminders, Admin Reports',
  4: 'Dashboard, Chat, Team Roster (scoped), Departments & Roles (scoped), Admin Reports'
};

document.addEventListener('DOMContentLoaded', async () => {
  await bootstrap();
  
  document.getElementById('searchInput').addEventListener('input', render);
  document.getElementById('btnAdd').addEventListener('click', openAddPanel);
  document.getElementById('edit_access_level').addEventListener('change', updateAccessDescription);
  document.getElementById('edit_department').addEventListener('change', filterRolesByDepartment);
});

async function bootstrap() {
  try {
    Spinner.show();
    const response = await fetch('/team/api?a=bootstrap');
    const text = await response.text();
    if (!response.ok) throw new Error(text);
    
    DATA = JSON.parse(text);
    DATA.accessLevel = parseInt(DATA.access_level || 1);
    DATA.userDepartmentIds = DATA.user_department_ids || [];
    
    // Show Add button only for access_level 1, 3, 4
    if ([1, 3, 4].includes(DATA.accessLevel)) {
      document.getElementById('btnAdd').style.display = 'inline-flex';
    }
    
    render();
  } catch (error) {
    console.error('Bootstrap failed:', error);
    showToast('Failed to load team data', 'error');
  } finally {
    Spinner.hide();
  }
}

function render() {
  const query = document.getElementById('searchInput').value.toLowerCase();
  const userList = document.getElementById('userList');
  const emptyState = document.getElementById('emptyState');
  
  // Filter roster
  const filtered = DATA.roster.filter(user => {
    const name = (user.name || '').toLowerCase();
    const email = (user.email || '').toLowerCase();
    const username = (user.username || '').toLowerCase();
    return name.includes(query) || email.includes(query) || username.includes(query);
  });
  
  if (filtered.length === 0) {
    userList.innerHTML = '';
    emptyState.style.display = 'block';
    return;
  }
  
  emptyState.style.display = 'none';
  
  // Group by department
  const grouped = {};
  filtered.forEach(user => {
    const deptName = user.department_name || 'No Department';
    if (!grouped[deptName]) grouped[deptName] = [];
    grouped[deptName].push(user);
  });
  
  // Render grouped list
  let html = '';
  Object.keys(grouped).sort().forEach(deptName => {
    const users = grouped[deptName];
    html += `
      <div class="dept-section">
        <div class="dept-header">
          <span>${escapeHtml(deptName)}</span>
          <span class="dept-count">${users.length}</span>
        </div>
        <ul class="user-list">
          ${users.map(user => renderUser(user)).join('')}
        </ul>
      </div>
    `;
  });
  
  userList.innerHTML = html;
}

function renderUser(user) {
  const initials = getInitials(user.name || user.username || '?');
  const accessBadge = getAccessBadge(parseInt(user.access_level || 1));
  const statusBadge = parseInt(user.is_active) === 1 
    ? '<span class="badge badge-success">Active</span>'
    : '<span class="badge badge-danger">Inactive</span>';
  
  // Check if user can edit this user (department scoping for Level 4)
  const canEdit = canUserEdit(user);
  const clickHandler = canEdit ? `onclick="openEditPanel(${user.user_id})"` : '';
  const cursorStyle = canEdit ? 'cursor: pointer;' : 'cursor: not-allowed; opacity: 0.6;';
  
  return `
    <li class="user-item" ${clickHandler} style="${cursorStyle}">
      <div class="avatar">${initials}</div>
      <div class="user-info">
        <div class="user-name">${escapeHtml(user.name || user.username)}</div>
        <div class="user-role">${escapeHtml(user.role_title || 'No role')} • ${escapeHtml(user.email || 'No email')}</div>
      </div>
      <div style="display: flex; flex-direction: column; gap: 0.375rem; align-items: flex-end;">
        ${accessBadge}
        ${statusBadge}
      </div>
    </li>
  `;
}

function canUserEdit(user) {
  // Level 1 and 3 can edit anyone
  if ([1, 3].includes(DATA.accessLevel)) return true;
  
  // Level 4 can only edit users in their departments
  if (DATA.accessLevel === 4) {
    if (!user.department_id) return false;
    return DATA.userDepartmentIds.includes(parseInt(user.department_id));
  }
  
  // Level 0, 2 cannot edit
  return false;
}

function getInitials(name) {
  return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2) || '?';
}

function getAccessBadge(level) {
  const badges = {
    0: '<span class="badge badge-danger">Inactive</span>',
    1: '<span class="badge badge-dark">Full Admin</span>',
    2: '<span class="badge badge-info">Power User</span>',
    3: '<span class="badge badge-warning">Team Lead</span>',
    4: '<span class="badge badge-secondary">Dept Admin</span>'
  };
  return badges[level] || '<span class="badge badge-secondary">Level ' + level + '</span>';
}

function openAddPanel() {
  // Check permission
  if (![1, 3, 4].includes(DATA.accessLevel)) {
    showToast('You do not have permission to add users', 'error');
    return;
  }
  
  document.getElementById('editTitle').textContent = 'Add Team Member';
  document.getElementById('edit_user_id').value = '';
  document.getElementById('edit_name').value = '';
  document.getElementById('edit_email').value = '';
  document.getElementById('edit_access_level').value = '2';
  document.getElementById('edit_status').value = '1';
  
  loadDepartments();
  updateAccessDescription();
  document.getElementById('editPanel').classList.add('show');
}

function openEditPanel(userId) {
  const user = DATA.roster.find(u => parseInt(u.user_id) === parseInt(userId));
  if (!user) return;
  
  // Check if user can edit this user
  if (!canUserEdit(user)) {
    showToast('You do not have permission to edit this user', 'error');
    return;
  }
  
  document.getElementById('editTitle').textContent = 'Edit User';
  document.getElementById('edit_user_id').value = user.user_id;
  document.getElementById('edit_name').value = user.name || '';
  document.getElementById('edit_email').value = user.email || '';
  document.getElementById('edit_access_level').value = user.access_level || 1;
  document.getElementById('edit_status').value = user.is_active || 1;
  
  loadDepartments(user.department_id);
  updateAccessDescription();
  document.getElementById('editPanel').classList.add('show');
}

function closeEditPanel() {
  document.getElementById('editPanel').classList.remove('show');
}

function loadDepartments(selectedDeptId = null) {
  const select = document.getElementById('edit_department');
  select.innerHTML = '<option value="">No department</option>';
  
  // For Level 4, only show their assigned departments
  let availableDepts = DATA.departments;
  if (DATA.accessLevel === 4) {
    availableDepts = DATA.departments.filter(dept => 
      DATA.userDepartmentIds.includes(parseInt(dept.id))
    );
  }
  
  availableDepts.forEach(dept => {
    const option = document.createElement('option');
    option.value = dept.id;
    option.textContent = dept.name;
    if (selectedDeptId && parseInt(selectedDeptId) === parseInt(dept.id)) {
      option.selected = true;
    }
    select.appendChild(option);
  });
  
  filterRolesByDepartment();
}

function filterRolesByDepartment() {
  const deptId = document.getElementById('edit_department').value;
  const roleSelect = document.getElementById('edit_role');
  
  roleSelect.innerHTML = '<option value="">No role</option>';
  
  if (!deptId) return;
  
  const filteredRoles = DATA.roles.filter(role => 
    parseInt(role.department_id) === parseInt(deptId)
  );
  
  filteredRoles.forEach(role => {
    const option = document.createElement('option');
    option.value = role.id;
    option.textContent = role.name;
    roleSelect.appendChild(option);
  });
}

function updateAccessDescription() {
  const level = document.getElementById('edit_access_level').value;
  const desc = ACCESS_DESCRIPTIONS[level] || '';
  document.getElementById('access_description').textContent = desc;
}

async function saveUser() {
  const userId = document.getElementById('edit_user_id').value;
  const isAdd = !userId;
  
  // Validate
  const name = document.getElementById('edit_name').value.trim();
  if (!name) {
    document.getElementById('error_name').classList.add('show');
    return;
  }
  document.getElementById('error_name').classList.remove('show');
  
  const deptId = document.getElementById('edit_department').value;
  const roleId = document.getElementById('edit_role').value;
  
  // Check department permission for Level 4
  if (DATA.accessLevel === 4 && deptId) {
    if (!DATA.userDepartmentIds.includes(parseInt(deptId))) {
      showToast('You can only assign users to your departments', 'error');
      return;
    }
  }
  
  const payload = {
    user_id: userId ? parseInt(userId) : null,
    full_name: name,
    email: document.getElementById('edit_email').value.trim(),
    access_level: parseInt(document.getElementById('edit_access_level').value),
    department_id: deptId ? parseInt(deptId) : null,
    role_id: roleId ? parseInt(roleId) : null,
    is_active: parseInt(document.getElementById('edit_status').value)
  };
  
  try {
    Spinner.show();
    const endpoint = isAdd ? '/team/api?a=hire' : '/team/api?a=update';
    
    if (isAdd) {
      // Generate username for new user
      payload.username = name.toLowerCase().replace(/\s+/g, '') + Math.floor(Math.random() * 1000);
      payload.password = 'temp' + Math.floor(Math.random() * 100000);
    }
    
    const response = await fetch(endpoint, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    
    const text = await response.text();
    if (!response.ok) throw new Error(text);
    
    closeEditPanel();
    await bootstrap();
    showToast(isAdd ? 'User added successfully' : 'User updated successfully', 'success');
  } catch (error) {
    console.error('Save failed:', error);
    showToast('Failed to save: ' + error.message, 'error');
  } finally {
    Spinner.hide();
  }
}

function showToast(message, type = 'success') {
  const container = document.getElementById('toastContainer');
  const toast = document.createElement('div');
  toast.className = `toast toast-${type}`;
  
  const icon = type === 'success' ? '✓' : '✕';
  toast.innerHTML = `
    <div class="toast-icon">${icon}</div>
    <div class="toast-message">${escapeHtml(message)}</div>
  `;
  
  container.appendChild(toast);
  
  setTimeout(() => {
    toast.style.animation = 'slideIn 0.3s ease reverse';
    setTimeout(() => toast.remove(), 300);
  }, 3000);
}

function escapeHtml(text) {
  const div = document.createElement('div');
  div.textContent = text || '';
  return div.innerHTML;
}
</script>
