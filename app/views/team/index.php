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
  --border: #E0E0E8;
  --ring: var(--accent-light);
  --shadow: 0 2px 8px rgba(9, 25, 77, 0.06);
  --shadow-md: 0 4px 16px rgba(9, 25, 77, 0.08);
  --shadow-lg: 0 8px 24px rgba(9, 25, 77, 0.1);
  --shadow-xl: 0 12px 32px rgba(9, 25, 77, 0.12);
  --radius: 12px;
  --radius-sm: 8px;
  --radius-lg: 16px;
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
    radial-gradient(circle at 20% 80%, rgba(181, 158, 95, 0.03) 0%, transparent 50%),
    radial-gradient(circle at 80% 20%, rgba(141, 119, 171, 0.03) 0%, transparent 50%);
  pointer-events: none;
}
.page-wrap { 
  background: transparent; 
  min-height: 100vh; 
  padding: 1.5rem 0;
  position: relative;
}
.page-header {
  margin-bottom: 1.5rem;
  gap: 1rem;
}
.page-header-content {
  flex: 1;
  min-width: 0;
}
.page-title {
  font-size: clamp(1.5rem, 4vw, 2rem);
  font-weight: 700;
  color: var(--primary);
  margin-bottom: 0.25rem;
  letter-spacing: -0.02em;
}
.page-subtitle {
  color: var(--muted);
  font-size: clamp(0.875rem, 2vw, 1rem);
  font-weight: 500;
  line-height: 1.5;
}
.card { 
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-lg);
  overflow: hidden;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
}
.card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: linear-gradient(90deg, var(--accent), var(--accent-secondary), var(--accent-tertiary));
}
/* Enhanced Buttons */
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: var(--radius);
  padding: 0.625rem 1.25rem;
  font-weight: 600;
  font-size: 0.875rem;
  border: none;
  cursor: pointer;
  transition: all 0.2s ease;
  gap: 0.5rem;
  white-space: nowrap;
  position: relative;
  text-decoration: none;
  line-height: 1.5;
}
.btn:hover {
  transform: translateY(-1px);
}
.btn:active {
  transform: translateY(0);
}
.btn-sm {
  padding: 0.5rem 1rem;
  font-size: 0.8125rem;
}
.btn-primary {
  background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
  color: white;
  box-shadow: 0 2px 8px rgba(9, 25, 77, 0.2);
}
.btn-primary:hover {
  box-shadow: 0 4px 12px rgba(9, 25, 77, 0.3);
}
.btn-outline,
.btn-outline-danger,
.btn-outline-success {
  background: white;
  border: 1.5px solid var(--border);
  color: var(--primary);
}
.btn-outline:hover {
  border-color: var(--primary);
  background: var(--lighter);
}
.btn-outline-danger {
  color: var(--danger);
  border-color: rgba(239, 68, 68, 0.3);
}
.btn-outline-danger:hover {
  border-color: var(--danger);
  background: rgba(239, 68, 68, 0.05);
}
.btn-outline-success {
  color: var(--success);
  border-color: rgba(16, 185, 129, 0.3);
}
.btn-outline-success:hover {
  border-color: var(--success);
  background: rgba(16, 185, 129, 0.05);
}
.btn-danger {
  background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
  color: white;
  box-shadow: 0 2px 8px rgba(239, 68, 68, 0.2);
}
.btn-danger:hover {
  box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}
.btn-success {
  background: linear-gradient(135deg, var(--success) 0%, #0da271 100%);
  color: white;
  box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
}
.btn-success:hover {
  box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}
.btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  transform: none !important;
}
/* Enhanced Badges */
.badge {
  display: inline-flex;
  align-items: center;
  padding: 0.375rem 0.75rem;
  font-size: 0.75rem;
  font-weight: 600;
  line-height: 1;
  text-align: center;
  white-space: nowrap;
  border-radius: 6px;
  border: 1px solid;
  flex-shrink: 0; /* Prevents stretching on desktop/tablet */
}
.badge-success {
  background: rgba(16, 185, 129, 0.1);
  border-color: rgba(16, 185, 129, 0.2);
  color: var(--success);
}
.badge-danger {
  background: rgba(239, 68, 68, 0.1);
  border-color: rgba(239, 68, 68, 0.2);
  color: var(--danger);
}
.badge-dark {
  background: rgba(9, 25, 77, 0.1);
  border-color: rgba(9, 25, 77, 0.2);
  color: var(--primary);
}
.badge-secondary {
  background: rgba(157, 148, 152, 0.1);
  border-color: rgba(157, 148, 152, 0.2);
  color: var(--muted);
}
.badge-info {
  background: rgba(59, 130, 246, 0.1);
  border-color: rgba(59, 130, 246, 0.2);
  color: var(--info);
}
.badge-warning {
  background: rgba(245, 158, 11, 0.1);
  border-color: rgba(245, 158, 11, 0.2);
  color: var(--warning);
}
/* Card Toolbar */
.card-toolbar {
  padding: 1rem 1.5rem;
  border-bottom: 1px solid var(--border);
  background: rgba(244, 245, 240, 0.5);
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  align-items: center;
}
.card-toolbar > .input-group {
    flex-grow: 1; /* Allow search to take up available space */
    min-width: 200px;
}
/* Enhanced Table */
.table-responsive {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}
.table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  font-size: 0.875rem;
}
.table th {
  font-weight: 700;
  color: var(--primary);
  background: rgba(244, 245, 240, 0.5);
  padding: 1rem 1.5rem;
  border-bottom: 1px solid var(--border);
  text-align: left;
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  white-space: nowrap;
}
.table td {
  padding: 1rem 1.5rem;
  border-bottom: 1px solid var(--border);
  vertical-align: middle;
}
.table tbody tr {
  transition: all 0.2s ease;
}
.table tbody tr:hover:not(.department-header) {
  background: rgba(244, 245, 240, 0.5);
}
.table tbody tr:last-child td {
  border-bottom: none;
}
.department-header td {
  border-bottom: 1px solid var(--border); /* Ensure a line after the header */
}
/* Avatar & Team Member Cell */
.team-member-cell {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  min-width: 0;
}
.avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--accent-light), var(--accent-secondary));
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  color: white;
  font-weight: 700;
  font-size: 0.875rem;
  box-shadow: var(--shadow);
}
.team-member-info {
  min-width: 0;
  flex: 1;
}
.team-member-name {
  font-weight: 600;
  color: var(--primary);
  display: block;
  margin-bottom: 0.125rem;
}
/* Enhanced Form Controls */
.input-group {
  position: relative;
  display: flex;
  align-items: stretch;
  width: 100%;
}
.input-group-text {
  display: flex;
  align-items: center;
  padding: 0.625rem 1rem;
  font-size: 0.875rem;
  color: var(--muted);
  background: white;
  border: 1.5px solid var(--border);
  border-right: none;
  border-radius: var(--radius);
  border-top-right-radius: 0;
  border-bottom-right-radius: 0;
}
.form-control {
  display: block;
  width: 100%;
  padding: 0.625rem 1rem;
  font-size: 0.875rem;
  font-weight: 500;
  line-height: 1.5;
  color: var(--primary);
  background: white;
  border: 1.5px solid var(--border);
  border-radius: var(--radius);
  transition: all 0.2s ease;
}
.input-group .form-control {
  border-top-left-radius: 0;
  border-bottom-left-radius: 0;
}
.form-control:focus {
  outline: none;
  border-color: var(--accent);
  box-shadow: 0 0 0 3px rgba(181, 158, 95, 0.1);
}
.form-control::placeholder {
  color: var(--neutral-light);
}
.form-select {
  display: block;
  width: 100%;
  padding: 0.625rem 2.5rem 0.625rem 1rem;
  font-size: 0.875rem;
  font-weight: 500;
  line-height: 1.5;
  color: var(--primary);
  background: white;
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2309194D' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 0.75rem center;
  background-size: 16px 12px;
  border: 1.5px solid var(--border);
  border-radius: var(--radius);
  transition: all 0.2s ease;
  appearance: none;
}
.form-select:focus {
  border-color: var(--accent);
  outline: none;
  box-shadow: 0 0 0 3px rgba(181, 158, 95, 0.1);
}
.form-check {
  display: flex;
  align-items: center;
  gap: 0.625rem;
}
.form-check-input {
  width: 18px;
  height: 18px;
  border: 1.5px solid var(--border);
  border-radius: 4px;
  cursor: pointer;
  transition: all 0.2s ease;
  appearance: none;
  position: relative;
  flex-shrink: 0;
  background: white;
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
  font-weight: 700;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}
.form-check-input:focus {
  border-color: var(--accent);
  box-shadow: 0 0 0 3px rgba(181, 158, 95, 0.1);
}
.form-check-label {
  cursor: pointer;
  font-size: 0.875rem;
  color: var(--primary);
  font-weight: 500;
  user-select: none;
}
.form-label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 600;
  color: var(--primary);
  font-size: 0.8125rem;
}
.form-text {
  margin-top: 0.375rem;
  font-size: 0.75rem;
  color: var(--muted);
  font-weight: 500;
}
.alert {
  padding: 0.75rem 1rem;
  border-radius: var(--radius);
  font-size: 0.875rem;
  border: 1px solid;
}
.alert-info {
  background: rgba(59, 130, 246, 0.1);
  border-color: rgba(59, 130, 246, 0.2);
  color: var(--info);
}
.text-muted {
  color: var(--muted) !important;
}
.small-text {
  font-size: 0.75rem;
  color: var(--muted);
  margin-top: 0.125rem;
  line-height: 1.4;
}
/* Enhanced Modals */
.modal-header {
  padding: 1.25rem 1.5rem;
  border-bottom: 1px solid var(--border);
  background: rgba(244, 245, 240, 0.3);
}
.modal-title {
  margin-bottom: 0;
  line-height: 1.5;
  font-weight: 700;
  color: var(--primary);
  display: flex;
  align-items: center;
  gap: 0.625rem;
  font-size: 1.125rem;
}
.modal-body {
  padding: 1.5rem;
}
.modal-footer {
  padding: 1.25rem 1.5rem;
  border-top: 1px solid var(--border);
  background: rgba(244, 245, 240, 0.3);
  display: flex;
  gap: 0.75rem;
  flex-wrap: wrap;
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
  opacity: 0.6;
  cursor: pointer;
  transition: all 0.2s ease;
}
.btn-close:hover {
  opacity: 1;
  background-color: var(--light);
}
/* Empty State */
.empty-state {
  padding: 3rem 1.5rem;
  text-align: center;
  color: var(--muted);
}
.empty-state-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
  opacity: 0.5;
}
.empty-state-text {
  margin-bottom: 1.5rem;
  font-size: 1rem;
  font-weight: 500;
  color: var(--neutral-dark);
}
/* Animations */
.fade-in {
  animation: fadeIn 0.3s ease-out;
}
@keyframes fadeIn {
  from { 
    opacity: 0; 
    transform: translateY(10px); 
  }
  to { 
    opacity: 1; 
    transform: translateY(0); 
  }
}
/* Responsive styles */
@media (max-width: 1024px) {
  .table {
    font-size: 0.8125rem;
  }
  
  .table th,
  .table td {
    padding: 0.875rem 1rem;
  }
}
/* Tablet/Narrow Desktop View (Fixes for sidebar open) */
@media (max-width: 768px) {
  .page-wrap {
    padding: 1rem 2;
  }
  
  .page-header {
    flex-direction: column;
    align-items: flex-start !important;
  }
  
  .btn:not(.btn-sm):not(.btn-icon) {
    width: 100%;
  }
  
  .card-toolbar {
    padding: 1rem;
    flex-direction: column;
    align-items: stretch;
  }
  
  .card-toolbar .input-group {
    max-width: 100% !important;
    min-width: 100% !important; /* Ensure search bar takes full width */
  }

  .table-responsive {
    margin: 0 -1rem;
  }
  
  .table {
    font-size: 0.75rem;
  }
  
  .table th,
  .table td {
    padding: 0.75rem 0.75rem;
  }
  
  .table th:first-child,
  .table td:first-child {
    padding-left: 1rem;
  }
  
  .table th:last-child,
  .table td:last-child {
    padding-right: 1rem;
  }
  
  .team-member-cell {
    gap: 0.5rem;
  }
  
  .avatar {
    width: 32px;
    height: 32px;
    font-size: 0.75rem;
  }
  
  .modal-dialog {
    margin: 0.5rem;
  }
  
  .modal-body {
    padding: 1.25rem;
  }
  
  .modal-footer {
    flex-direction: column-reverse;
  }
  
  .modal-footer .btn {
    width: 100%;
  }
}
/* Mobile Card View - Clean Collapsible Design */
@media (max-width: 640px) {
  .container-fluid {
    padding-left: 0.75rem;
    padding-right: 0.75rem;
  }
  
  .page-title {
    font-size: 1.375rem;
  }
  
  .page-subtitle {
    font-size: 0.8125rem;
  }
  
  .empty-state {
    padding: 2rem 1rem;
  }
  
  /* Hide table header */
  .table thead {
    display: none;
  }
  
  /* Employee card - collapsed by default */
  .table tbody tr:not(.department-header) {
    display: block;
    margin-bottom: 0.75rem;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow);
    background: var(--card);
    transition: all 0.3s ease;
  }
  
  .table tbody tr:not(.department-header):active {
    transform: scale(0.99);
  }
  
  /* Card header - always visible */
  .table td:nth-child(1) { /* Team Member */
    display: flex !important;
    align-items: center;
    justify-content: space-between;
    padding: 1rem;
    border-bottom: 1px solid var(--border);
    background: rgba(244, 245, 240, 0.3);
    cursor: pointer;
    -webkit-tap-highlight-color: transparent;
  }
  
  .table td:nth-child(1)::after {
    content: '›';
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--neutral);
    transition: transform 0.3s ease;
    flex-shrink: 0;
    margin-left: 1rem;
  }
  
  .table tbody tr.expanded td:nth-child(1)::after {
    transform: rotate(90deg);
  }
  
  /* All other cells - hidden when collapsed */
  .table td:not(:nth-child(1)) {
    display: none !important;
  }
  
  /* Show all cells when expanded */
  .table tbody tr.expanded td:not(:nth-child(1)) {
    display: block !important;
    padding: 0.75rem 1rem;
    border-bottom: 1px solid var(--border);
    background: white;
  }
  
  .table tbody tr.expanded td:last-child {
    border-bottom: none;
  }
  
  /* Add labels for expanded fields */
  .table tbody tr.expanded td::before {
    content: attr(data-label);
    font-weight: 700;
    color: var(--primary);
    display: block;
    font-size: 0.65rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.375rem;
    opacity: 0.8;
  }
  
  .table tbody tr.expanded td:nth-child(1)::before {
    display: none;
  }
  
  /* Status badge in collapsed view */
  .table tbody tr:not(.expanded) .team-member-cell::after {
    content: attr(data-status);
    font-size: 0.7rem;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    margin-left: auto;
  }
  
  .table tbody tr:not(.expanded) .team-member-cell[data-status="Active"]::after {
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.2);
    color: var(--success);
  }
  
  .table tbody tr:not(.expanded) .team-member-cell[data-status="Terminated"]::after {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.2);
    color: var(--danger);
  }
  
  /* Department Header */
  .table tbody tr.department-header {
    border: none;
    box-shadow: none;
    background: transparent;
    margin-top: 1rem;
    margin-bottom: 0.5rem;
  }
  
  .table tbody tr.department-header td {
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    border-bottom: 2px solid var(--border);
    border-radius: var(--radius-sm);
  }
  
  /* Adjust button sizing in expanded view */
  .table tbody tr.expanded .btn-sm {
    width: 100%;
    margin-top: 0.5rem;
  }
}
/* Utility Classes */
.d-flex { display: flex; }
.d-block { display: block; }
.align-items-center { align-items: center; }
.justify-content-between { justify-content: space-between; }
.justify-content-end { justify-content: flex-end; }
.text-end { text-align: right; }
.me-3 { margin-right: 1rem; }
.mb-3 { margin-bottom: 1rem; }
.mt-2 { margin-top: 0.5rem; }
.mt-3 { margin-top: 1rem; }
.border-bottom { border-bottom: 1px solid var(--border); }
.p-3 { padding: 1.5rem; }
.g-3 > * { padding: 0.75rem; }
.row { display: flex; flex-wrap: wrap; margin: 0 -0.75rem; }
.col-12 { flex: 0 0 100%; max-width: 100%; }
.col-md-3 { flex: 0 0 100%; max-width: 100%; }
.col-md-6 { flex: 0 0 100%; max-width: 100%; }
@media (min-width: 768px) {
  .col-md-3 { flex: 0 0 25%; max-width: 25%; }
  .col-md-6 { flex: 0 0 50%; max-width: 50%; }
}
</style>
<div class="page-wrap">
  <div class="container-fluid px-3 px-md-4 py-3 py-md-4">
    <div class="d-flex align-items-center justify-content-between page-header">
      <div class="page-header-content">
        <h1 class="page-title">Team Roster</h1>
        <p class="page-subtitle">Add (hire) or terminate team members. Hiring creates rows in <b>users</b> and <b>employees</b> tables.</p>
      </div>
      <button class="btn btn-primary admin-action" id="btnAdd">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
          <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
        </svg>
        <span class="d-none d-md-inline">Add Team Member</span>
        <span class="d-inline d-md-none">Add</span>
      </button>
    </div>
    <div class="card">
      <div class="card-toolbar">
        <div class="input-group" style="max-width:420px;">
          <span class="input-group-text">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
              <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
            </svg>
          </span>
          <input id="q" class="form-control" placeholder="Search team members…">
        </div>
        <div class="form-check admin-action">
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
              <th>Department</th>
              <th>Access</th>
              <th>Role</th>
              <th>Wage</th>
              <th>Status</th>
              <th class="text-end admin-action">Actions</th>
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
        <button class="btn btn-primary admin-action" id="btnAddEmpty">
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
          Add Team Member
        </h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Username <span class="text-danger">*</span></label>
            <input class="form-control" id="h_username" placeholder="e.g. jsmith">
          </div>
          <div class="col-md-6">
            <label class="form-label">Full name</label>
            <input class="form-control" id="h_fullname" placeholder="John Smith">
          </div>
          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input class="form-control" id="h_email" type="email" placeholder="john@example.com">
          </div>
          <div class="col-md-6">
            <label class="form-label">Mobile phone</label>
            <input class="form-control" id="h_phone" placeholder="(555) 555-5555">
          </div>
          <div class="col-12">
            <label class="form-label">Access Level <span class="text-danger">*</span></label>
            <select class="form-select" id="h_access">
              <option value="0">Level 0 - Inactive</option>
              <option value="1" selected>Level 1 - Regular User</option>
              <option value="2">Level 2 - Power User</option>
              <option value="3">Level 3 - Team Lead</option>
              <option value="4">Level 4 - Department Admin</option>
            </select>
            <div id="accessSummary" class="alert alert-info mt-2" style="display:none; font-size: 0.875rem;">
              </div>
          </div>
          <div class="col-md-6">
            <label class="form-label">Department <span class="text-danger">*</span></label>
            <select class="form-select" id="h_departments">
              <option value="">Select a department...</option>
              </select>
            <div class="form-text">Select the employee's primary department</div>
          </div>
          <div class="col-md-6">
            <label class="form-label">Role <span class="text-danger">*</span></label>
            <select class="form-select" id="h_role" required></select>
            <div class="form-text">Required - Filtered by selected departments</div>
          </div>
          
          <div class="col-md-3">
            <label class="form-label">Wage</label>
            <input class="form-control" id="h_wage" type="number" step="0.01" value="0.00">
          </div>
          <div class="col-md-3">
            <label class="form-label">Rate</label>
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
        <div class="small-text mt-3">Hiring creates a <b>users</b> row (login) and an <b>employees</b> row (HR profile).</div>
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
          Terminate Team Member
        </h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="t_user_id">
        <div class="mb-3">
          <label class="form-label">Reason <span class="text-danger">*</span></label>
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
<div class="modal fade" id="changeDeptModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
            <path d="M0 3a2 2 0 0 1 2-2h13.5a.5.5 0 0 1 0 1H15v2a1 1 0 0 1 1 1v8.5a1.5 1.5 0 0 1-1.5 1.5h-12A2.5 2.5 0 0 1 0 12.5V3zm1 1.732V12.5A1.5 1.5 0 0 0 2.5 14h12a.5.5 0 0 0 .5-.5V5H2a1.99 1.99 0 0 1-1-.268zM1 3a1 1 0 0 0 1 1h12V2H2a1 1 0 0 0-1 1z"/>
          </svg>
          Change Department
        </h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="cd_user_id">
        <div class="mb-3">
          <label class="form-label">Select Department</label>
          <select class="form-select" id="cd_department">
            <option value="">Select a department...</option>
            </select>
          <div class="form-text">Select the employee's new department</div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" id="changeDeptSave">Save Changes</button>
      </div>
    </div>
  </div>
</div>
<?php require 'app/views/templates/footer.php'; ?>
<script>
let DEPARTMENTS = [];
let ROLES = [];
let USERS = [];
let ACCESS_LEVEL = 1;
let USER_DEPARTMENT_IDS = [];
const M_hire  = new bootstrap.Modal(document.getElementById('hireModal'));
const M_term  = new bootstrap.Modal(document.getElementById('termModal'));
const M_changeDept = new bootstrap.Modal(document.getElementById('changeDeptModal'));
document.addEventListener('DOMContentLoaded', async () => {
  await bootstrapTeam();
  document.getElementById('q').addEventListener('input', render);
  document.getElementById('showTerminated').addEventListener('change', render);
  
  // Show/hide Add button based on access level (only 1, 3, 4 can add)
  const btnAdd = document.getElementById('btnAdd');
  if (btnAdd) {
    if (ACCESS_LEVEL === 1 || ACCESS_LEVEL === 3 || ACCESS_LEVEL === 4) {
      btnAdd.style.display = 'inline-flex';
    } else {
      btnAdd.style.display = 'none';
    }
  }
  document.getElementById('btnAdd').addEventListener('click', async () => {
    if (ACCESS_LEVEL !== 1 && ACCESS_LEVEL !== 3 && ACCESS_LEVEL !== 4) {
      return alert('Admin access required (Level 1, 3, or 4)');
    }
    clearHireForm();
    await loadDepartments();
    await loadRolesForHire();
    updateAccessLevelSummary();
    M_hire.show();
  });
  
  document.getElementById('btnAddEmpty').addEventListener('click', async () => {
    if (ACCESS_LEVEL !== 1 && ACCESS_LEVEL !== 3 && ACCESS_LEVEL !== 4) {
      return alert('Admin access required (Level 1, 3, or 4)');
    }
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
  document.getElementById('changeDeptSave').addEventListener('click', onChangeDeptSave);
});
/* ---------- Bootstrap roster ---------- */
async function bootstrapTeam() {
  try {
    const data = await get('/team/api?a=bootstrap');
    DEPARTMENTS = data.departments || [];
    ROLES = data.roles || [];
    USERS = data.users || [];
    ACCESS_LEVEL = parseInt(data.access_level || 1);
    USER_DEPARTMENT_IDS = data.user_department_ids || [];
    
    // Hide admin actions for Level 2 users (Power User - view only)
    if (ACCESS_LEVEL === 2) {
      document.querySelectorAll('.admin-action').forEach(el => {
        el.style.display = 'none';
      });
    }
    
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
  0: 'Inactive - No access (cannot login)',
  1: 'Regular User - Dashboard, Chat, Time Clock, My Shifts, Reminders',
  2: 'Power User - Dashboard, Chat, Time Clock, My Shifts, Reminders',
  3: 'Team Lead - Dashboard, Chat, Team, Schedule, Reminders, Admin Reports',
  4: 'Department Admin - Dashboard, Chat, Time Clock, My Shifts, Reminders, Admin Reports, Departments & Roles (View Only)'
};
function updateAccessLevelSummary() {
  const level = document.getElementById('h_access').value;
  const summary = document.getElementById('accessSummary');
  const description = ACCESS_LEVEL_DESCRIPTIONS[level] || '';
  
  if (description) {
    summary.innerHTML = `<strong>Access:</strong> ${description}`;
    summary.style.display = 'block';
  } else {
    summary.style.display = 'none';
  }
}
/* ---------- Departments ---------- */
let ALL_DEPARTMENTS = [];
let ALL_ROLES = [];
async function loadDepartments() {
  const sel = document.getElementById('h_departments');
  sel.innerHTML = '<option value="">Loading…</option>';
  
  // Use bootstrap data (already loaded from /team/api?a=bootstrap)
  ALL_DEPARTMENTS = DEPARTMENTS || [];
  
  sel.innerHTML = '<option value="">Select a department...</option>';
  
  if (ALL_DEPARTMENTS.length > 0) {
    ALL_DEPARTMENTS.forEach(dept => {
      const opt = document.createElement('option');
      opt.value = dept.id;
      opt.textContent = dept.name;
      sel.appendChild(opt);
    });
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
  const selectedDeptId = parseInt(deptSel.value) || 0;
  
  sel.innerHTML = '';
  
  // Show all roles (roles can be used across departments)
  let filteredRoles = ALL_ROLES;
  
  // Add roles to dropdown
  if (filteredRoles.length > 0) {
    for (const r of filteredRoles) {
      const name = (r && (r.name ?? r.title ?? r.role ?? '')).toString();
      if (!name) continue;
      const opt = document.createElement('option');
      opt.value = name;
      opt.textContent = name;
      sel.appendChild(opt);
    }
  }
  
  // If no roles available, show message
  if (sel.options.length === 0) {
    sel.innerHTML = '<option value="">— No roles available —</option>';
  }
}
/* ---------- Render roster grouped by department ---------- */
function render() {
  const q = (document.getElementById('q').value || '').toLowerCase();
  const showTerm = document.getElementById('showTerminated').checked;
  const tbody = document.getElementById('rows');
  const emptyState = document.getElementById('emptyState');
  
  // Filter users
  const filteredUsers = USERS.filter(u => {
    const matchQ = !q || 
      (u.name||'').toLowerCase().includes(q) || 
      (u.username||'').toLowerCase().includes(q) || 
      (u.email||'').toLowerCase().includes(q);
    const matchTerm = showTerm ? true : (parseInt(u.status) === 1);
    return matchQ && matchTerm;
  });
  
  if (filteredUsers.length === 0) {
    tbody.innerHTML = '';
    emptyState.style.display = 'block';
    return;
  }
  
  emptyState.style.display = 'none';
  tbody.innerHTML = '';
  
  // Group users by department
  const deptGroups = {};
  filteredUsers.forEach(u => {
    const deptName = u.department_name || 'No Department';
    if (!deptGroups[deptName]) {
      deptGroups[deptName] = [];
    }
    deptGroups[deptName].push(u);
  });
  
  // Render each department group
  Object.keys(deptGroups).sort().forEach(deptName => {
    // Department header row (colspan depends on whether Actions column is visible)
    const colspan = ACCESS_LEVEL === 2 ? 7 : 8;
    const headerRow = document.createElement('tr');
    headerRow.classList.add('department-header');
    headerRow.innerHTML = `
      <td colspan="${colspan}" style="background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%); 
                             color: white; font-weight: 700; padding: 0.75rem 1rem; 
                             font-size: 0.875rem; letter-spacing: 0.5px;">
        ${escapeHtml(deptName)}
      </td>
    `;
    tbody.appendChild(headerRow);
    
    // Users in this department
    deptGroups[deptName].forEach(u => {
      const tr = document.createElement('tr');
      tr.classList.add('fade-in');
      
      // Get initials for avatar
      const name = u.name || u.username || '';
      const initials = name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2) || '?';
      
      // Check if user can edit this user (scoped by department for Level 4)
      const canEdit = canEditUser(u);
      
      tr.innerHTML = `
        <td data-label="Team Member">
          <div class="team-member-cell" data-status="${u.status==1 ? 'Active' : 'Terminated'}">
            <div class="avatar">${initials}</div>
            <div class="team-member-info">
              <span class="team-member-name">${escapeHtml(u.name || u.username)}</span>
              <div class="small-text">${escapeHtml(u.username||'')}</div>
            </div>
          </div>
        </td>
        <td data-label="Contact">
          <div>${escapeHtml(u.email||'—')}</div>
          <div class="small-text">${escapeHtml(u.phone||'—')}</div>
        </td>
        <td data-label="Department">
          <div id="dept-display-${u.id}">
            ${escapeHtml(u.department_name||'No Department')}
            ${canEdit && (ACCESS_LEVEL === 1 || ACCESS_LEVEL === 4) 
              ? `<button class="btn btn-sm btn-outline" style="margin-left: 0.5rem; padding: 0.25rem 0.5rem; margin-top: 0.25rem; line-height: 1;" onclick="openChangeDepartment(${u.id}, ${u.department_id || 0})">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M12.854.146a.5.5 0 0 0-.708 0l-10 10a.5.5 0 0 0-.127.353v3.5a.5.5 0 0 0 .5.5h3.5a.5.5 0 0 0 .353-.127l10-10a.5.5 0 0 0 0-.708l-3.5-3.5zM11.5 1.5l3 3L14.707 5.707 11 2zM3 13.5v-2.192l5.057-5.057 3 3L5.192 13.5H3z"/>
                  </svg>
                  <span class="d-none d-md-inline">Change</span>
                 </button>` 
              : ''}
          </div>
        </td>
        <td data-label="Access">
          ${(() => {
            const level = parseInt(u.access_level ?? 1);
            const labels = {
              0: '<span class="badge badge-danger">Inactive</span>',
              1: '<span class="badge badge-secondary">Regular User</span>',
              2: '<span class="badge badge-info">Power User</span>',
              3: '<span class="badge badge-warning">Team Lead</span>',
              4: '<span class="badge badge-dark">Dept Admin</span>'
            };
            return labels[level] ?? '<span class="badge badge-secondary">Level ' + level + '</span>';
          })()}
        </td>
        <td data-label="Role">${escapeHtml(u.role_title||'—')}</td>
        <td data-label="Wage">
          <div>$${Number(u.wage||0).toFixed(2)}</div>
          <div class="small-text">${u.rate||'hourly'}</div>
        </td>
        <td data-label="Status">
          ${u.status==1
            ? '<span class="badge badge-success">Active</span>'
            : `<span class="badge badge-danger">Terminated</span>
               <div class="small-text">${escapeHtml(u.termination_reason||'')}</div>`}
        </td>
        <td data-label="Actions" class="text-end admin-action">
          ${canEdit
              ? (u.status==1
                  ? `<button class="btn btn-sm btn-outline-danger" onclick="openTerminate(${u.id})">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                      </svg>
                      <span class="d-none d-md-inline">Terminate</span>
                     </button>`
                  : `<button class="btn btn-sm btn-outline-success" onclick="rehire(${u.id})">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                        <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                      </svg>
                      <span class="d-none d-md-inline">Rehire</span>
                     </button>`)
              : ''}
        </td>
      `;
      
      // Add click handler for mobile collapse/expand
      const firstCell = tr.querySelector('td:first-child');
      if (firstCell) {
        firstCell.addEventListener('click', (e) => {
          // Only toggle on mobile (screen width <= 640px)
          if (window.innerWidth <= 640) {
            tr.classList.toggle('expanded');
          }
        });
      }
      
      tbody.appendChild(tr);
    });
  });
}
/* Check if current user can edit a specific user */
function canEditUser(user) {
  // Level 0 and 2 cannot edit
  if (ACCESS_LEVEL === 0 || ACCESS_LEVEL === 2) return false;
  
  // Level 1, 3, and 4 can edit all users (full access)
  if (ACCESS_LEVEL === 1 || ACCESS_LEVEL === 3 || ACCESS_LEVEL === 4) return true;
  
  return false;
}
/* ---------- Hire ---------- */
function clearHireForm() {
  ['h_username','h_fullname','h_email','h_phone','h_password'].forEach(id => document.getElementById(id).value='');
  document.getElementById('h_wage').value = '0.00';
  document.getElementById('h_rate').value = 'hourly';
  document.getElementById('h_access').value = '1';
  document.getElementById('h_start').value = (new Date()).toISOString().slice(0,10);
  
  const deptSel = document.getElementById('h_departments');
  deptSel.value = '';
}
async function onHireSave(){
  const username = v('h_username');
  if (!username) {
    showError('Username is required');
    return;
  }
  
  const roleTitle = v('h_role');
  if (!roleTitle) {
    showError('Role is required');
    return;
  }
  
  const deptSel = document.getElementById('h_departments');
  const selectedDeptId = parseInt(deptSel.value) || 0;
  
  // Validate department selection
  if (!selectedDeptId) {
    showError('Please select a department');
    return;
  }
  
  try {
    const payload = {
      username:  username,
      full_name: v('h_fullname'),
      email:     v('h_email'),
      phone:     v('h_phone'),
      role_title: roleTitle,
      wage:      parseFloat(v('h_wage')||0),
      rate:      v('h_rate'),
      access_level: parseInt(v('h_access'),10),
      department_id: selectedDeptId,  // Send as single department ID
      start_date:v('h_start'),
      password:  v('h_password')
    };
    
    await post('/team/api?a=hire', payload);
    M_hire.hide();
    await refreshRoster();
    showSuccess('Team member added successfully');
  } catch (error) {
    showError('Failed to add team member: ' + error.message);
  }
}
function v(id){ return document.getElementById(id).value.trim(); }
/* ---------- Terminate / Rehire ---------- */
function openTerminate(user_id){
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
      reason:  reason,
      note:    document.getElementById('t_note').value.trim(),
      termination_date: document.getElementById('t_date').value,
      eligible_for_rehire: document.getElementById('t_rehire').checked ? 1 : 0
    };
    
    await post('/team/api?a=terminate', payload);
    M_term.hide();
    await refreshRoster();
    showSuccess('Team member terminated successfully');
  } catch (error) {
    showError('Failed to terminate team member: ' + error.message);
  }
}
async function rehire(user_id){
  try {
    await post('/team/api?a=rehire', { user_id, start_date:(new Date()).toISOString().slice(0,10) });
    await refreshRoster();
    showSuccess('Team member rehired successfully');
  } catch (error) {
    showError('Failed to rehire team member: ' + error.message);
  }
}
/* ---------- Change Department ---------- */
async function openChangeDepartment(userId, currentDeptId) {
  document.getElementById('cd_user_id').value = userId;
  
  // Populate department dropdown
  const deptSelect = document.getElementById('cd_department');
  deptSelect.innerHTML = '<option value="">Select a department...</option>';
  DEPARTMENTS.forEach(dept => {
    const option = document.createElement('option');
    option.value = dept.id;
    option.textContent = dept.name;
    if (dept.id === currentDeptId) {
      option.selected = true;
    }
    deptSelect.appendChild(option);
  });
  
  M_changeDept.show();
}
async function onChangeDeptSave() {
  const userId = parseInt(document.getElementById('cd_user_id').value);
  const newDeptId = parseInt(document.getElementById('cd_department').value);
  
  if (!newDeptId) {
    showError('Please select a department');
    return;
  }
  
  try {
    await post('/team/api?a=change_department', { 
      user_id: userId, 
      department_id: newDeptId 
    });
    M_changeDept.hide();
    await refreshRoster();
    showSuccess('Department changed successfully');
  } catch (error) {
    showError('Failed to change department: ' + error.message);
  }
}
/* ---------- Utils ---------- */
async function refreshRoster(){ 
  try {
    const d = await get('/team/api?a=list'); 
    USERS = d.users||[]; 
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
function showError(message) {
  // You could replace this with a toast notification system
  alert('Error: ' + message);
}
function showSuccess(message) {
  // You could replace this with a toast notification system
  alert('Success: ' + message);
}
</script>