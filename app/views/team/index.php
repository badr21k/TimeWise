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
  position: relative;
}

body::before {
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

.page-wrap { 
  background: var(--bg); 
  min-height: 100vh; 
  padding: 2rem 0;
  position: relative;
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
  flex-shrink: 0; /* Prevents button from shrinking in flex containers */
}

.btn:hover {
  transform: translateY(-2px);
}

.btn:disabled, .btn[disabled] {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
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

.btn-success {
  background: linear-gradient(135deg, var(--success) 0%, #0da271 100%);
  color: white;
  box-shadow: 0 8px 32px rgba(16, 185, 129, 0.3);
}

.btn-success:hover {
  box-shadow: 0 12px 40px rgba(16, 185, 129, 0.4);
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
.btn-outline-danger {
    background: transparent;
    border: 2px solid var(--danger);
    color: var(--danger);
}
.btn-outline-danger:hover {
    background: rgba(239, 68, 68, 0.1);
    color: var(--danger);
}
.btn-outline-success {
    background: transparent;
    border: 2px solid var(--success);
    color: var(--success);
}
.btn-outline-success:hover {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
}


/* Enhanced Badges */
.badge {
  display: inline-block;
  padding: 8px 16px;
  font-size: 0.8rem;
  font-weight: 600;
  line-height: 1;
  text-align: center;
  white-space: nowrap;
  vertical-align: baseline;
  border-radius: var(--radius);
  border: 2px solid;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.badge-success {
  background: rgba(16, 185, 129, 0.1);
  border-color: rgba(16, 185, 129, 0.3);
  color: var(--success);
}

.badge-danger {
  background: rgba(239, 68, 68, 0.1);
  border-color: rgba(239, 68, 68, 0.3);
  color: var(--danger);
}

.badge-dark {
  background: rgba(9, 25, 77, 0.1);
  border-color: rgba(9, 25, 77, 0.3);
  color: var(--primary);
}

.badge-secondary {
  background: rgba(157, 148, 152, 0.1);
  border-color: rgba(157, 148, 152, 0.3);
  color: var(--muted);
}
.badge-info {
    background: rgba(59, 130, 246, 0.1);
    border-color: rgba(59, 130, 246, 0.3);
    color: var(--info);
}
.badge-warning {
    background: rgba(245, 158, 11, 0.1);
    border-color: rgba(245, 158, 11, 0.3);
    color: var(--warning);
}


/* Enhanced Table */
.table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
}

.table th {
  font-weight: 700;
  color: var(--primary);
  background: var(--lighter);
  padding: 1.25rem 1.5rem;
  border-bottom: 2px solid var(--border);
  text-align: left;
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.table td {
  padding: 1.5rem;
  border-bottom: 2px solid var(--border);
  vertical-align: middle;
  font-weight: 500;
}

.table tbody tr {
  transition: all 0.3s ease;
}

.table tbody tr:hover {
  background: var(--lighter);
  transform: translateX(4px);
}

/* Enhanced Form Controls */
.input-group {
  position: relative;
  display: flex;
  flex-wrap: wrap;
  align-items: stretch;
  width: 100%;
}

.input-group-text {
  display: flex;
  align-items: center;
  padding: 14px 20px;
  font-size: 0.95rem;
  font-weight: 500;
  color: var(--muted);
  text-align: center;
  white-space: nowrap;
  background: var(--lighter);
  border: 2px solid var(--border);
  border-radius: var(--radius-sm);
  border-right: 0;
  border-top-right-radius: 0;
  border-bottom-right-radius: 0;
}

.form-control {
  display: block;
  width: 100%;
  padding: 14px 20px;
  font-size: 0.95rem;
  font-weight: 500;
  line-height: 1.5;
  color: var(--primary);
  background: var(--lighter);
  border: 2px solid var(--border);
  border-radius: var(--radius-sm);
  transition: all 0.3s ease;
}

.form-control:focus {
  outline: none;
  border-color: var(--accent);
  box-shadow: 0 0 0 4px rgba(181, 158, 95, 0.15);
  background: white;
}

.form-select {
  display: block;
  width: 100%;
  padding: 14px 20px 14px 20px;
  font-size: 0.95rem;
  font-weight: 500;
  line-height: 1.5;
  color: var(--primary);
  background: var(--lighter);
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2309194D' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 20px center;
  background-size: 16px 12px;
  border: 2px solid var(--border);
  border-radius: var(--radius-sm);
  transition: all 0.3s ease;
  appearance: none;
}

.form-select:focus {
  border-color: var(--accent);
  outline: none;
  box-shadow: 0 0 0 4px rgba(181, 158, 95, 0.15);
  background: white;
}

.form-check {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.form-check-input {
  width: 18px;
  height: 18px;
  border: 2px solid var(--border);
  border-radius: 4px;
  cursor: pointer;
  transition: all 0.3s ease;
  appearance: none;
  position: relative;
  flex-shrink: 0;
}

.form-check-input:checked {
  background-color: var(--accent);
  border-color: var(--accent);
}

.form-check-input:checked::after {
  content: '✓';
  position: absolute;
  color: white;
  font-size: 12px;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.form-check-input:focus {
  border-color: var(--accent);
  box-shadow: 0 0 0 3px rgba(181, 158, 95, 0.15);
}

.form-check-label {
  cursor: pointer;
  font-size: 0.9rem;
  color: var(--primary);
  font-weight: 500;
}

.form-label {
  display: block;
  margin-bottom: 0.75rem;
  font-weight: 600;
  color: var(--primary);
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.form-text {
  margin-top: 0.5rem;
  font-size: 0.8rem;
  color: var(--muted);
  font-weight: 500;
}

.text-muted {
  color: var(--muted) !important;
}

.small-text {
  font-size: 0.8rem;
  color: var(--muted);
  margin-top: 0.5rem;
  font-weight: 500;
}

/* Enhanced Modals (Bootstrap 5) */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1055;
    display: none;
    width: 100%;
    height: 100%;
    overflow-x: hidden;
    overflow-y: auto;
    outline: 0;
}
.modal.show {
    display: block;
}
.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: var(--primary);
    opacity: 0.4;
    z-index: 1050;
}
.modal-dialog {
    position: relative;
    width: auto;
    margin: 1.75rem auto;
    pointer-events: none;
    max-width: 500px; /* Default size */
}
.modal-dialog-scrollable {
    max-height: calc(100% - 3.5rem);
}
.modal-dialog-lg {
    max-width: 800px;
}
.modal-content {
  position: relative;
  display: flex;
  flex-direction: column;
  width: 100%;
  color: var(--ink);
  pointer-events: auto;
  background: var(--card);
  background-clip: padding-box;
  border: 2px solid var(--border);
  border-radius: var(--radius-lg);
  outline: 0;
  box-shadow: var(--shadow-xl);
}
.modal-header {
  padding: 1.5rem 2rem;
  border-bottom: 2px solid var(--border);
  background: var(--lighter);
  border-top-left-radius: var(--radius-lg);
  border-top-right-radius: var(--radius-lg);
}

.modal-title {
  margin-bottom: 0;
  line-height: 1.5;
  font-weight: 700;
  color: var(--primary);
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-size: 1.25rem;
}

.modal-body {
  padding: 2rem;
}

.modal-footer {
  padding: 1.5rem 2rem;
  border-top: 2px solid var(--border);
  background: var(--lighter);
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  border-bottom-left-radius: var(--radius-lg);
  border-bottom-right-radius: var(--radius-lg);
}

.btn-close {
  box-sizing: content-box;
  width: 1em;
  height: 1em;
  padding: 0.5em;
  color: var(--primary);
  background: transparent url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%2309194D'%3e%3cpath d='M.293.293a1 1 0 0 1 1.414 0L8 6.586 14.293.293a1 1 0 1 1 1.414 1.414L9.414 8l6.293 6.293a1 1 0 0 1-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 0 1-1.414-1.414L6.586 8 .293 1.707a1 1 0 0 1 0-1.414z'/%3e%3c/svg%3e") center/1em auto no-repeat;
  border: 0;
  border-radius: var(--radius-sm);
  opacity: 0.7;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-close:hover {
  opacity: 1;
  background-color: var(--light);
}

/* Alert styling for access summary and notifications */
.alert {
    padding: 1rem 1.5rem;
    border-radius: var(--radius-sm);
    font-size: 0.9rem;
    font-weight: 500;
    margin-bottom: 1rem;
    border: 2px solid;
}
.alert-info {
    background: rgba(59, 130, 246, 0.1);
    border-color: rgba(59, 130, 246, 0.3);
    color: var(--info);
}
.alert-danger {
    background: rgba(239, 68, 68, 0.1);
    border-color: rgba(239, 68, 68, 0.3);
    color: var(--danger);
}
.alert-success {
    background: rgba(16, 185, 129, 0.1);
    border-color: rgba(16, 185, 129, 0.3);
    color: var(--success);
}
.alert-container {
    position: fixed;
    top: 1rem;
    right: 1rem;
    z-index: 2000;
    max-width: 400px;
}
.alert-toast {
    box-shadow: var(--shadow-lg);
    margin-bottom: 0.5rem;
    transform: translateX(100%);
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}
.alert-toast.show {
    transform: translateX(0);
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
  .table-responsive {
    overflow-x: auto;
  }
  
  .table {
    min-width: 800px;
  }
}

@media (max-width: 768px) {
  .page-wrap {
    padding: 1rem 0;
  }
  
  .page-header {
    flex-direction: column;
    align-items: flex-start !important;
    gap: 1rem;
  }

  .filter-bar {
    flex-direction: column;
    align-items: flex-start !important;
  }
  .input-group {
    max-width: 100% !important;
    margin-right: 0 !important;
    margin-bottom: 1rem;
  }
  .form-check {
    margin-bottom: 0.5rem;
  }
  
  .modal-dialog {
    margin: 1rem;
    max-width: 100% !important;
  }
  .modal-dialog-lg {
    max-width: 100% !important;
  }
  
  .btn {
    width: 100%;
    justify-content: center;
  }
  .modal-footer .btn {
      width: auto; /* Reset for modal footer buttons */
      flex-grow: 1; /* Allow them to grow */
  }

  .card-body {
    padding: 1.5rem;
  }
  
  .page-title {
    font-size: 1.75rem;
  }
}

@media (max-width: 640px) {
  .container-fluid {
    padding-left: 1rem;
    padding-right: 1rem;
  }
  
  .table th,
  .table td {
    padding: 1rem;
  }
  
  .modal-body {
    padding: 1.5rem;
  }
  
  .empty-state {
    padding: 3rem 1rem;
  }
  
  .page-title {
    font-size: 1.5rem;
  }

  /* Stack modal footer buttons */
  .modal-footer {
    flex-direction: column-reverse;
  }
  .modal-footer .btn {
      width: 100%;
  }
}

/* Utility Classes */
.d-flex { display: flex; }
.align-items-center { align-items: center; }
.justify-content-between { justify-content: space-between; }
.justify-content-end { justify-content: flex-end; }
.text-end { text-align: right; }
.me-3 { margin-right: 1rem; }
.mb-3 { margin-bottom: 1rem; }
.mt-3 { margin-top: 1rem; }
.border-bottom { border-bottom: 2px solid var(--border); }
.p-3 { padding: 1.5rem; }
.g-3 { gap: 1.5rem; } /* grid gap */
/* Responsive Grid Utilities (Based on Bootstrap column system) */
.row { display: flex; flex-wrap: wrap; margin: 0; }
.col-12 { flex: 0 0 auto; width: 100%; padding: 0.75rem; }
.col-md-6 { flex: 0 0 auto; width: 50%; padding: 0.75rem; }
.col-md-3 { flex: 0 0 auto; width: 25%; padding: 0.75rem; }

@media (max-width: 768px) {
    .col-md-6, .col-md-3 {
        width: 100%;
    }
    .row {
        gap: 0; /* Adjust row gap on small devices for better stacking */
    }
}
</style>

<div class="alert-container" id="alertContainer"></div>

<div class="page-wrap">
  <div class="container-fluid px-3 px-md-4 py-4">
    
        <div class="d-flex align-items-center justify-content-between page-header">
      <div>
        <h1 class="page-title">Team Roster ✨</h1>
        <p class="page-subtitle">Add (hire) or terminate team members. Hiring creates rows in **users** and **employees** tables.</p>
      </div>
      <button class="btn btn-primary" id="btnAdd">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
          <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
        </svg>
        Add Team Member
      </button>
    </div>

        <div class="card">
            <div class="d-flex align-items-center p-3 border-bottom filter-bar">
        <div class="input-group me-3" style="max-width:420px">
          <span class="input-group-text" style="flex-shrink: 0;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
              <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
            </svg>
          </span>
          <input id="q" class="form-control" placeholder="Search by name, username, or email…">
        </div>
        <div class="form-check" style="flex-shrink: 0;">
          <input class="form-check-input" type="checkbox" id="showTerminated">
          <label class="form-check-label" for="showTerminated">Show terminated</label>
        </div>
      </div>

            <div class="table-responsive">
        <table class="table" id="tbl">
          <thead>
            <tr>
              <th>Team Member</th>
              <th>Contact</th>
              <th>Access</th>
              <th>Role</th>
              <th>Wage</th>
              <th>Status</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody id="rows"></tbody>
        </table>
      </div>
      
            <div id="emptyState" class="empty-state" style="display: none;">
        <div class="empty-state-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" viewBox="0 0 16 16">
            <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7Zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm-5.784 6A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216ZM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"/>
          </svg>
        </div>
        <div class="empty-state-text">No team members found</div>
        <button class="btn btn-primary" id="btnAddEmpty">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
          </svg>
          Add Team Member
        </button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="hireModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
          </svg>
          **Hire** New Team Member
        </h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Username *</label>
            <input class="form-control" id="h_username" placeholder="e.g. jsmith">
          </div>
          <div class="col-md-6">
            <label class="form-label">Full name</label>
            <input class="form-control" id="h_fullname" placeholder="John Smith">
          </div>
          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input class="form-control" id="h_email" type="email">
          </div>
          <div class="col-md-6">
            <label class="form-label">Mobile phone</label>
            <input class="form-control" id="h_phone" placeholder="(555) 555-5555">
          </div>

          <div class="col-12">
            <label class="form-label">Access Level *</label>
            <select class="form-select" id="h_access">
              <option value="0">Level 0 - Inactive</option>
              <option value="1" selected>Level 1 - Regular User</option>
              <option value="2">Level 2 - Power User</option>
              <option value="3">Level 3 - Team Lead</option>
              <option value="4">Level 4 - Department Admin</option>
            </select>
            <div id="accessSummary" class="alert alert-info mt-3" style="display:none;">
                          </div>
          </div>

          <div class="col-md-6">
            <label class="form-label">Departments (optional)</label>
            <select class="form-select" id="h_departments" multiple size="4">
                          </select>
            <div class="form-text">Hold Ctrl/Cmd to select multiple.</div>
          </div>

          <div class="col-md-6">
            <label class="form-label">Role</label>
            <select class="form-select" id="h_role"></select>
            <div class="form-text">Role is filtered by selected departments.</div>
          </div>
          
          <div class="col-md-3">
            <label class="form-label">Wage</label>
            <input class="form-control" id="h_wage" type="number" step="0.01" value="0.00">
          </div>
          <div class="col-md-3">
            <label class="form-label d-block">Rate</label>
            <select class="form-select" id="h_rate">
              <option value="hourly">Hourly</option>
              <option value="salary">Salary</option>
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label">Start date</label>
            <input class="form-control" id="h_start" type="date" value="<?= date('Y-m-d') ?>">
          </div>
          <div class="col-md-3">
            <label class="form-label">Password (optional)</label>
            <input class="form-control" id="h_password" type="text" placeholder="Auto if blank">
          </div>
        </div>
        <div class="small-text mt-3">Hiring creates a **users** row (login) and an **employees** row (HR profile).</div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" id="hireSave">Add Team Member</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="termModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
          </svg>
          **Terminate** Team Member
        </h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="t_user_id">
        <div class="mb-3">
          <label class="form-label">Reason *</label>
          <select class="form-select" id="t_reason">
            <option value="">Select…</option>
            <option>Resignation</option>
            <option>Dismissal</option>
            <option>Seasonal layoff</option>
            <option>End of contract</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Termination date</label>
          <input class="form-control" id="t_date" type="date" value="<?= date('Y-m-d') ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Internal note (optional)</label>
          <textarea class="form-control" id="t_note" rows="3"></textarea>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="t_rehire" checked>
          <label class="form-check-label" for="t_rehire">Eligible for rehire</label>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-danger" id="termSave">Terminate</button>
      </div>
    </div>
    </div>
</div>

<?php require 'app/views/templates/footer.php'; ?>

<script>
// --- Bootstrap Modal Logic (Required for modern modals) ---
// Note: This relies on a 'bootstrap' library being loaded, as indicated by the original HTML.
// For the modals to function, you need the actual Bootstrap JS library loaded via 'footer.php' or similar.
class bootstrap {
    static Modal = class {
        constructor(element) {
            this._element = element;
            this._backdrop = document.createElement('div');
            this._backdrop.className = 'modal-backdrop fade';
            this._element.addEventListener('click', (e) => {
                if (e.target.dataset.bsDismiss === 'modal' || e.target === this._element) {
                    this.hide();
                }
            });
            this._backdrop.addEventListener('click', () => this.hide());
            this._element.setAttribute('aria-modal', 'true');
            this._element.setAttribute('role', 'dialog');
        }
        show() {
            document.body.appendChild(this._backdrop);
            this._element.classList.add('show');
            setTimeout(() => {
                this._backdrop.classList.add('show');
                this._element.style.display = 'block';
            }, 10);
        }
        hide() {
            this._backdrop.classList.remove('show');
            this._element.classList.remove('show');
            setTimeout(() => {
                this._element.style.display = 'none';
                this._backdrop.remove();
            }, 300); // Match CSS transition time
        }
    };
}
const M_hire  = new bootstrap.Modal(document.getElementById('hireModal'));
const M_term  = new bootstrap.Modal(document.getElementById('termModal'));
// -----------------------------------------------------------

let ROSTER = [];
let ACCESS_LEVEL = 1;

document.addEventListener('DOMContentLoaded', async () => {
  await bootstrapTeam();
  document.getElementById('q').addEventListener('input', render);
  document.getElementById('showTerminated').addEventListener('change', render);

  document.getElementById('btnAdd').addEventListener('click', async () => {
    if (ACCESS_LEVEL < 3) return showError('Team Lead or higher access required to hire.');
    clearHireForm();
    await loadDepartments();
    await loadRolesForHire();
    updateAccessLevelSummary();
    M_hire.show();
  });
  
  document.getElementById('btnAddEmpty').addEventListener('click', async () => {
    if (ACCESS_LEVEL < 3) return showError('Team Lead or higher access required to hire.');
    clearHireForm();
    await loadDepartments();
    await loadRolesForHire();
    updateAccessLevelSummary();
    M_hire.show();
  });

  document.getElementById('h_access').addEventListener('change', updateAccessLevelSummary);
  document.getElementById('h_departments').addEventListener('change', filterRolesByDepartments);
  document.getElementById('hireSave').addEventListener('click', onHireSave);
  document.getElementById('termSave').addEventListener('click', onTermSave);

  // Disable buttons if access is too low on load
  if (ACCESS_LEVEL < 3) {
      document.getElementById('btnAdd').disabled = true;
      document.getElementById('btnAddEmpty').disabled = true;
  }
});

/* ---------- Bootstrap roster ---------- */
async function bootstrapTeam() {
  try {
    const data = await get('/team/api?a=bootstrap');
    ROSTER = data.roster || [];
    ACCESS_LEVEL = parseInt(data.access_level || 1);
    render();
  } catch (error) {
    console.error('Failed to load team data:', error);
    showError('Failed to load team data. Please refresh the page.');
  }
}

/* ---------- Spinner-aware fetch helpers ---------- */
async function fetchJSON(url, options = {}) {
  Spinner.show();
  try {
    const r = await fetch(url, { headers: { 'Content-Type': 'application/json' }, ...options });
    const t = await r.text();
    if (!r.ok) throw new Error(t || `HTTP ${r.status}`);
    return JSON.parse(t);
  } finally {
    Spinner.hide();
  }
}

async function get(url) {
  Spinner.show();
  try {
    const r = await fetch(url);
    const t = await r.text();
    if (!r.ok) throw new Error(t);
    return JSON.parse(t);
  } finally {
    Spinner.hide();
  }
}

async function post(url, data) {
  Spinner.show();
  try {
    const r = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });
    const t = await r.text();
    if (!r.ok) throw new Error(t);
    return JSON.parse(t);
  } finally {
    Spinner.hide();
  }
}

/* ---------- Access Level Summary ---------- */
const ACCESS_LEVEL_DESCRIPTIONS = {
  0: 'Inactive - No access (cannot login).',
  1: 'Regular User - Core modules (Dashboard, Chat, Time Clock, etc.).',
  2: 'Power User - Core modules + enhanced user features (e.g., advanced reporting view).',
  3: 'Team Lead - Includes all Power User features + Team management, Schedule editing, and Admin Reports.',
  4: 'Department Admin - Includes Team Lead features + Department & Role management (View Only).'
};

function updateAccessLevelSummary() {
  const level = document.getElementById('h_access').value;
  const summary = document.getElementById('accessSummary');
  const description = ACCESS_LEVEL_DESCRIPTIONS[level] || 'No summary available for this level.';
  
  summary.innerHTML = `<strong>Level ${level}:</strong> ${description}`;
  summary.style.display = 'block';
}

/* ---------- Departments ---------- */
let ALL_DEPARTMENTS = [];
let ALL_ROLES = [];

async function loadDepartments() {
  try {
    const data = await get('/departments/api?a=list');
    ALL_DEPARTMENTS = data.departments || [];
    
    const sel = document.getElementById('h_departments');
    sel.innerHTML = '';
    if (ALL_DEPARTMENTS.length === 0) {
        sel.innerHTML = '<option value="" disabled>No departments found</option>';
    }
    ALL_DEPARTMENTS.forEach(dept => {
      const opt = document.createElement('option');
      opt.value = dept.id;
      opt.textContent = dept.name;
      sel.appendChild(opt);
    });
  } catch (e) {
    console.error('Failed to load departments:', e);
    showError('Failed to load departments.');
  }
}

/* ---------- Roles ---------- */
async function loadRolesForHire() {
  const sel = document.getElementById('h_role');
  sel.innerHTML = '<option>Loading…</option>';

  async function tryFetch(url) {
    try {
      const r = await fetch(url, { headers: { 'Content-Type': 'application/json' }});
      const txt = await r.text();
      if (!r.ok) throw new Error(txt || `HTTP ${r.status}`);
      const json = JSON.parse(txt);
      return Array.isArray(json) ? json : (Array.isArray(json.roles) ? json.roles : []);
    } catch (e) {
      console.error('[roles.list] error from', url, e);
      return null;
    }
  }

  let roles = await tryFetch('/schedule/api?a=roles.list');
  if (!roles) roles = await tryFetch('/team/api?a=roles.list');
  
  ALL_ROLES = Array.isArray(roles) ? roles : [];
  filterRolesByDepartments();
}

function filterRolesByDepartments() {
  const sel = document.getElementById('h_role');
  const deptSel = document.getElementById('h_departments');
  const selectedDepts = Array.from(deptSel.selectedOptions).map(opt => parseInt(opt.value));
  
  sel.innerHTML = '';
  
  let filteredRoles = ALL_ROLES;
  if (selectedDepts.length > 0) {
    filteredRoles = ALL_ROLES.filter(r => {
      if (!r.department_id) return false;
      return selectedDepts.includes(parseInt(r.department_id));
    });
  }
  
  if (filteredRoles.length) {
    for (const r of filteredRoles) {
      const name = (r && (r.name ?? r.title ?? r.role ?? '')).toString();
      if (!name) continue;
      const opt = document.createElement('option');
      opt.value = name;
      opt.textContent = name;
      sel.appendChild(opt);
    }
  }
  
  if (!sel.options.length) {
    const msg = selectedDepts.length > 0 
      ? '— No roles for selected departments —' 
      : '— No roles found (check API / DB) —';
    sel.innerHTML = `<option value="">${msg}</option>`;
  }
}

/* ---------- Render roster (The Table) ---------- */
function render() {
  const q = (document.getElementById('q').value || '').toLowerCase();
  const showTerm = document.getElementById('showTerminated').checked;
  const tbody = document.getElementById('rows');
  const emptyState = document.getElementById('emptyState');
  
  const filteredRoster = ROSTER.filter(r => {
    const matchQ = !q || 
      (r.name||'').toLowerCase().includes(q) || 
      (r.username||'').toLowerCase().includes(q) || 
      (r.email||'').toLowerCase().includes(q);
    const matchTerm = showTerm ? true : (parseInt(r.is_active) === 1);
    return matchQ && matchTerm;
  });
  
  if (filteredRoster.length === 0) {
    tbody.innerHTML = '';
    emptyState.style.display = 'block';
    return;
  }
  
  emptyState.style.display = 'none';
  tbody.innerHTML = '';

  filteredRoster.forEach(r => {
    const tr = document.createElement('tr');
    tr.classList.add('fade-in');
    
    // Determine the badge class for the Access Level
    const accessLevel = parseInt(r.access_level ?? 1);
    let accessBadgeClass = 'badge-secondary';
    let accessLabel = `Level ${accessLevel}`;
    if (accessLevel === 0) { accessBadgeClass = 'badge-danger'; accessLabel = 'Inactive'; }
    else if (accessLevel === 3) { accessBadgeClass = 'badge-warning'; accessLabel = 'Team Lead'; }
    else if (accessLevel === 4) { accessBadgeClass = 'badge-dark'; accessLabel = 'Dept Admin'; }
    else if (accessLevel === 2) { accessBadgeClass = 'badge-info'; accessLabel = 'Power User'; }
    else { accessLabel = 'Regular User'; } // Level 1 or unhandled

    tr.innerHTML = `
      <td>
        <div class="d-flex align-items-center">
          <div class="me-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16" style="color: var(--neutral-light)">
              <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
              <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
            </svg>
          </div>
          <div>
            <strong>${escapeHtml(r.name || r.username)}</strong>
            <div class="small-text">${escapeHtml(r.username||'')}</div>
          </div>
        </div>
      </td>
      <td>
        <div>${escapeHtml(r.email||'—')}</div>
        <div class="small-text">${escapeHtml(r.phone||'—')}</div>
      </td>
      <td>
        <span class="badge ${accessBadgeClass}">${accessLabel}</span>
      </td>
      <td>${escapeHtml(r.role_title||'—')}</td>
      <td>
        <div>$${Number(r.wage||0).toFixed(2)}</div>
        <div class="small-text">${(r.rate||'hourly').toUpperCase()}</div>
      </td>
      <td>
        ${r.is_active==1
          ? '<span class="badge badge-success">Active</span>'
          : `<span class="badge badge-danger">Terminated</span>
             <div class="small-text">${escapeHtml(r.termination_reason||'N/A')}</div>`}
      </td>
      <td class="text-end">
        ${r.is_active==1
            ? `<button class="btn btn-sm btn-icon btn-outline-danger" ${ACCESS_LEVEL>=3?'':'disabled'} title="Terminate" onclick="openTerminate(${r.user_id})">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                  <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                  <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                </svg>
               </button>`
            : `<button class="btn btn-sm btn-icon btn-outline-success" ${ACCESS_LEVEL>=3?'':'disabled'} title="Rehire" onclick="rehire(${r.user_id})">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                  <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                </svg>
               </button>`}
      </td>
    `;
    tbody.appendChild(tr);
  });
}

/* ---------- Hire ---------- */
function clearHireForm() {
  ['h_username','h_fullname','h_email','h_phone','h_password'].forEach(id => document.getElementById(id).value='');
  document.getElementById('h_wage').value = '0.00';
  document.getElementById('h_rate').value = 'hourly';
  document.getElementById('h_access').value = '1';
  document.getElementById('h_start').value = (new Date()).toISOString().slice(0,10);
  
  const deptSel = document.getElementById('h_departments');
  Array.from(deptSel.options).forEach(opt => opt.selected = false);
}

async function onHireSave(){
  const username = v('h_username');
  if (!username) {
    showError('Username is required');
    return;
  }
  
  const deptSel = document.getElementById('h_departments');
  const selectedDepts = Array.from(deptSel.selectedOptions).map(opt => parseInt(opt.value));
  
  try {
    const payload = {
      username:  username,
      full_name: v('h_fullname'),
      email:     v('h_email'),
      phone:     v('h_phone'),
      role_title: v('h_role'),
      wage:      parseFloat(v('h_wage')||0),
      rate:      v('h_rate'),
      access_level: parseInt(v('h_access'),10),
      departments: selectedDepts,
      start_date:v('h_start'),
      password:  v('h_password')
    };
    
    await post('/team/api?a=hire', payload);
    M_hire.hide();
    await refreshRoster();
    showSuccess('Team member added successfully!');
  } catch (error) {
    showError('Failed to add team member: ' + error.message);
  }
}

function v(id){ return document.getElementById(id).value.trim(); }

/* ---------- Terminate / Rehire ---------- */
function openTerminate(user_id){
  if (ACCESS_LEVEL < 3) return showError('Team Lead or higher access required to terminate.');
  document.getElementById('t_user_id').value = user_id;
  document.getElementById('t_reason').value = '';
  document.getElementById('t_note').value = '';
  document.getElementById('t_rehire').checked = true;
  document.getElementById('t_date').value = (new Date()).toISOString().slice(0,10);
  M_term.show();
}

async function onTermSave(){
  const reason = document.getElementById('t_reason').value.trim();
  if (!reason) {
    showError('Please select a termination reason');
    return;
  }
  
  try {
    const payload = {
      user_id: parseInt(document.getElementById('t_user_id').value,10),
      reason:  reason,
      note:    document.getElementById('t_note').value.trim(),
      termination_date: document.getElementById('t_date').value,
      eligible_for_rehire: document.getElementById('t_rehire').checked ? 1 : 0
    };
    
    await post('/team/api?a=terminate', payload);
    M_term.hide();
    await refreshRoster();
    showSuccess('Team member terminated successfully.');
  } catch (error) {
    showError('Failed to terminate team member: ' + error.message);
  }
}

async function rehire(user_id){
  if (ACCESS_LEVEL < 3) return showError('Team Lead or higher access required to rehire.');
  try {
    await post('/team/api?a=rehire', { user_id, start_date:(new Date()).toISOString().slice(0,10) });
    await refreshRoster();
    showSuccess('Team member rehired successfully!');
  } catch (error) {
    showError('Failed to rehire team member: ' + error.message);
  }
}

/* ---------- Utils ---------- */
async function refreshRoster(){ 
  try {
    const d = await get('/team/api?a=list'); 
    ROSTER = d.roster||[]; 
    render();
  } catch (error) {
    showError('Failed to refresh roster: ' + error.message);
  }
}

function escapeHtml(t=''){ 
  const d = document.createElement('div'); 
  d.textContent = t; 
  return d.innerHTML; 
}

/**
 * Creates and displays a toast notification.
 * @param {string} message The message to display.
 * @param {string} type 'success', 'danger', or 'info'.
 */
function showNotification(message, type) {
    const container = document.getElementById('alertContainer');
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-toast fade-in`;
    alert.innerHTML = message;
    
    // Add to container
    container.appendChild(alert);
    
    // Show animation (using a slight delay to ensure the initial state is rendered)
    setTimeout(() => alert.classList.add('show'), 50);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        alert.classList.remove('show');
        // Wait for fade-out then remove from DOM
        setTimeout(() => alert.remove(), 500); 
    }, 5000);
}

function showError(message) {
  showNotification(message, 'danger');
}

function showSuccess(message) {
  showNotification(message, 'success');
}
</script>