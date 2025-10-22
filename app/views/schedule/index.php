<?php
// app/views/schedule/index.php
require 'app/views/templates/header.php';
require 'app/views/templates/spinner.php';
?>
<style>
:root {
  --primary: #09194D;
  --primary-hover: #1a2a6c;
  --secondary: #D97F76;
  --secondary-hover: #c96a61;
  --light: #E4E4EF;
  --lighter: #F4F5F0;
  --neutral: #9B9498;
  --dark: #2D2926;
  --accent: #B59E5F;
  --accent-hover: #a38d54;
  --accent-secondary: #8D77AB;
  --accent-tertiary: #DA70D6;
  --success: #10b981;
  --warning: #f59e0b;
  --danger: #ef4444;
  --danger-hover: #dc2626;
  
  --gray-50: #f9fafb;
  --gray-100: #f3f4f6;
  --gray-200: #e5e7eb;
  --gray-300: #d1d5db;
  --gray-400: #9ca3af;
  --gray-500: #6b7280;
  --gray-700: #374151;
  --gray-900: #111827;
  
  --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
  --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
  --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  --radius: 0.375rem;
  --radius-lg: 0.5rem;
  --transition: all 0.2s ease;
}

.schedule-container { 
  background: linear-gradient(135deg, var(--lighter) 0%, var(--light) 100%); 
  min-height: 100vh; 
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; 
}

.page-header { 
  margin-bottom: clamp(1.5rem, 3vw, 2rem);
  display: flex;
  flex-direction: column;
  gap: clamp(1.25rem, 2.5vw, 1.75rem);
}
.page-header-top {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}
.page-title { 
  font-size: clamp(1.75rem, 4vw, 2.25rem); 
  font-weight: 800; 
  color: var(--primary); 
  margin: 0;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  line-height: 1.2;
}
.page-title i {
  color: var(--accent-secondary);
  opacity: 0.9;
}
.page-subtitle { 
  color: var(--gray-600); 
  font-size: clamp(0.95rem, 1.8vw, 1.05rem); 
  font-weight: 500;
  line-height: 1.5;
  margin: 0;
}

.btn { 
  display: inline-flex; 
  align-items: center; 
  justify-content: center; 
  border-radius: var(--radius); 
  padding: 0.625rem 1.25rem; 
  font-weight: 500; 
  font-size: 0.875rem; 
  border: 1px solid transparent; 
  cursor: pointer; 
  transition: var(--transition); 
  gap: 0.5rem; 
}
.btn:hover { transform: translateY(-1px); }
.btn-sm { padding: 0.5rem 0.875rem; font-size: 0.8125rem; }
.btn-primary { 
  background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%); 
  color: white; 
  box-shadow: var(--shadow-sm); 
}
.btn-primary:hover { 
  background: linear-gradient(135deg, var(--primary-hover) 0%, var(--primary) 100%); 
  box-shadow: var(--shadow); 
}
.btn-outline { 
  background: transparent; 
  border: 1px solid var(--gray-300); 
  color: var(--gray-700); 
}
.btn-outline:hover { 
  background: var(--gray-50); 
  border-color: var(--primary);
  color: var(--primary);
}
.btn-danger { 
  background: var(--danger); 
  color: white; 
}
.btn-danger:hover { 
  background: var(--danger-hover); 
}
.btn-success { 
  background: var(--success); 
  color: white; 
}
.btn-success:hover { 
  background: #0da271; 
}
.btn-icon { 
  padding: 0.5rem; 
  border-radius: var(--radius); 
}

.badge { 
  display: inline-block; 
  padding: 0.35rem 0.65rem; 
  font-size: 0.75rem; 
  font-weight: 600; 
  line-height: 1; 
  text-align: center; 
  white-space: nowrap; 
  vertical-align: baseline; 
  border-radius: 9999px; 
}
.badge-success { 
  background: linear-gradient(135deg, var(--success) 0%, #0da271 100%); 
  color: white; 
}
.badge-warning { 
  background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%); 
  color: white; 
}
.badge-accent {
  background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%);
  color: white;
}

.week-controls { 
  display: flex; 
  align-items: center; 
  justify-content: space-between;
  gap: clamp(1rem, 2.5vw, 1.5rem); 
  flex-wrap: wrap;
  padding: clamp(1rem, 2vw, 1.25rem);
  background: white;
  border-radius: 0.75rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
  border: 1px solid var(--gray-200);
}
.week-navigation { 
  display: flex; 
  align-items: center; 
  gap: 0.75rem;
  flex: 1 1 auto;
}
.week-nav-btn { 
  background: linear-gradient(135deg, var(--lighter) 0%, white 100%); 
  border: 2px solid var(--gray-300); 
  border-radius: var(--radius); 
  padding: 0.625rem 0.75rem; 
  color: var(--primary); 
  cursor: pointer; 
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
  display: flex; 
  align-items: center; 
  justify-content: center; 
  min-width: 44px;
  min-height: 44px;
  font-weight: 600;
}
.week-nav-btn:hover { 
  background: linear-gradient(135deg, var(--accent-secondary) 0%, var(--accent-tertiary) 100%); 
  border-color: var(--accent-secondary);
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(141, 119, 171, 0.3);
}
.week-display { 
  font-weight: 700; 
  color: var(--primary); 
  min-width: min(280px, 70vw); 
  text-align: center; 
  font-size: clamp(0.875rem, 1.3vw, 0.95rem); 
  line-height: 1.4;
  padding: 0 0.5rem;
  letter-spacing: -0.01em;
}
.publish-section { 
  display: flex; 
  align-items: center; 
  gap: clamp(0.75rem, 1.5vw, 1rem); 
  flex-wrap: wrap;
  flex-shrink: 0;
}

/* tools dropdown */
.tools-wrap { position: relative; }
.tools-menu { 
  display: none; 
  position: absolute; 
  right: 0; 
  top: 110%; 
  background: var(--lighter);
  border: 1px solid var(--light); 
  border-radius: .5rem; 
  box-shadow: var(--shadow); 
  min-width: 240px; 
  z-index: 10; 
}
.tools-menu.show { display: block; }
.tools-item { 
  width:100%; 
  text-align:left; 
  background:var(--lighter); 
  border:0; 
  padding:.6rem .9rem; 
  font-size:.875rem; 
  color:var(--primary); 
  transition: var(--transition);
}
.tools-item:hover { 
  background: var(--light); 
  color: var(--accent-secondary);
}

.schedule-grid { 
  background: white; 
  border-radius: 1rem; 
  overflow: auto; 
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08); 
  margin-bottom: clamp(2rem, 4vw, 3rem); 
  border: 1px solid var(--gray-200);
  transition: box-shadow 0.3s ease;
  max-height: 75vh;
  position: relative;
}
.schedule-grid:hover {
  box-shadow: 0 6px 24px rgba(0, 0, 0, 0.12);
}
.grid-header { 
  display: grid; 
  grid-template-columns: 240px repeat(7, 1fr); 
  background: linear-gradient(135deg, var(--primary) 0%, #0a1b50 100%); 
  border-bottom: 2px solid rgba(181, 158, 95, 0.3);
  position: sticky;
  top: 0;
  z-index: 10;
}
.grid-header-cell { 
  padding: clamp(0.875rem, 2vw, 1.125rem) clamp(0.625rem, 1.5vw, 0.875rem); 
  font-weight: 700; 
  color: white; 
  font-size: 0.75rem; 
  text-align: center; 
  border-right: 1px solid rgba(255,255,255,0.15); 
  letter-spacing: 0.02em;
  text-transform: uppercase;
  background: linear-gradient(135deg, var(--primary) 0%, #0a1b50 100%);
  line-height: 1.2;
}
.grid-header-cell:first-child { 
  text-align: left; 
  background: linear-gradient(135deg, rgba(9, 25, 77, 0.95) 0%, rgba(10, 27, 80, 0.95) 100%);
  text-transform: none;
  font-size: clamp(0.8rem, 1.2vw, 0.875rem);
  position: sticky;
  left: 0;
  z-index: 11;
}
.grid-body { 
  background: var(--gray-50);
}
.grid-row { 
  display: grid; 
  grid-template-columns: 240px repeat(7, 1fr); 
  border-bottom: 1px solid var(--gray-200); 
  min-height: 130px; 
  background: white;
  transition: background-color 0.2s ease;
}
.grid-row:nth-child(even) {
  background: var(--gray-50);
}
.grid-row:hover {
  background: rgba(181, 158, 95, 0.04);
}

/* Department grouping */
.department-group-header {
  padding: clamp(0.875rem, 2vw, 1.125rem) clamp(0.875rem, 2vw, 1.25rem);
  background: linear-gradient(135deg, rgba(243, 244, 246, 0.98) 0%, rgba(249, 250, 251, 0.98) 100%);
  font-size: clamp(0.85rem, 1.3vw, 0.925rem);
  font-weight: 700;
  color: var(--primary);
  border-bottom: 2px solid var(--gray-300);
  border-top: 2px solid var(--gray-200);
  margin-top: 1rem;
  letter-spacing: -0.01em;
  position: sticky;
  top: var(--grid-header-height, 3.5rem);
  z-index: 9;
  backdrop-filter: blur(10px);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);
}
.department-group-header:first-child {
  margin-top: 0;
  border-top: none;
}

.employee-cell { 
  background: linear-gradient(135deg, var(--gray-50) 0%, white 100%); 
  padding: clamp(0.875rem, 2vw, 1.125rem) clamp(0.75rem, 1.5vw, 1rem); 
  border-right: 2px solid var(--gray-200); 
  display: flex; 
  flex-direction: column; 
  gap: 0.375rem; 
  position: sticky;
  left: 0;
  z-index: 2;
}
.employee-cell::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 3px;
  background: linear-gradient(135deg, var(--gray-50) 0%, white 100%);
}
.employee-cell::after {
  content: '';
  position: absolute;
  right: 0;
  top: 0;
  bottom: 0;
  width: 2px;
  background: var(--gray-200);
  box-shadow: 2px 0 4px rgba(0, 0, 0, 0.08);
}
.employee-name { 
  font-weight: 700; 
  color: var(--primary); 
  font-size: clamp(0.875rem, 1.2vw, 0.95rem); 
  line-height: 1.3;
}
.employee-role { 
  color: var(--gray-600); 
  font-size: clamp(0.75rem, 1vw, 0.825rem); 
  line-height: 1.3;
  font-weight: 500;
}
.employee-hours { 
  color: var(--gray-500); 
  font-size: clamp(0.7rem, 0.95vw, 0.75rem); 
  margin-top: auto;
  font-weight: 600;
  padding-top: 0.25rem;
  border-top: 1px solid var(--gray-200);
}

.day-cell { 
  padding: clamp(0.5rem, 1.2vw, 0.625rem); 
  border-right: 1px solid var(--gray-200); 
  position: relative; 
  background: transparent; 
  min-height: 130px; 
}

.shift-block { 
  background: linear-gradient(135deg, rgba(141, 119, 171, 0.12) 0%, rgba(218, 112, 214, 0.08) 100%); 
  color: var(--primary); 
  border-radius: calc(var(--radius) + 0.125rem); 
  padding: clamp(0.5rem, 1.2vw, 0.625rem); 
  margin-bottom: 0.375rem; 
  font-size: clamp(0.7rem, 1vw, 0.75rem); 
  position: relative; 
  cursor: pointer; 
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08); 
  border-left: 3px solid var(--accent-secondary);
  border: 1px solid rgba(141, 119, 171, 0.2);
  border-left: 3px solid var(--accent-secondary);
  backdrop-filter: blur(10px);
}
.shift-block:hover { 
  background: linear-gradient(135deg, var(--accent-secondary) 0%, var(--accent-tertiary) 100%); 
  color: white;
  transform: translateY(-2px) translateX(2px);
  box-shadow: 0 4px 12px rgba(141, 119, 171, 0.25);
  border-color: var(--accent-secondary); 
}
.shift-time { 
  font-weight: 700; 
  margin-bottom: 0.25rem;
  line-height: 1.3;
  letter-spacing: -0.02em;
}
.shift-role { 
  opacity: 0.85; 
  font-size: clamp(0.65rem, 0.9vw, 0.7rem);
  line-height: 1.3; 
  font-weight: 500;
}
.shift-block:hover .shift-role {
  opacity: 0.95;
}
.shift-actions { 
  position: absolute; 
  top: 0.375rem; 
  right: 0.375rem; 
  display: flex; 
  gap: 0.25rem; 
  opacity: 0; 
  transition: opacity 0.3s ease; 
}
.shift-block:hover .shift-actions { 
  opacity: 1; 
}
.shift-mini { 
  border: 0; 
  background: rgba(9, 25, 77, 0.15); 
  color: var(--primary); 
  width: 24px; 
  height: 24px; 
  border-radius: 50%; 
  font-size: 0.7rem; 
  display: flex; 
  align-items: center; 
  justify-content: center; 
  transition: all 0.2s ease;
  cursor: pointer;
}
.shift-block:hover .shift-mini {
  background: rgba(255, 255, 255, 0.25);
  color: white;
}
.shift-mini:hover {
  background: rgba(255, 255, 255, 0.35);
  transform: scale(1.15);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.add-shift-area { 
  position: absolute; 
  bottom: clamp(0.5rem, 1.2vw, 0.625rem); 
  left: clamp(0.5rem, 1.2vw, 0.625rem); 
  right: clamp(0.5rem, 1.2vw, 0.625rem); 
}
.add-shift-btn { 
  width: 100%; 
  background: white; 
  border: 2px dashed var(--gray-300); 
  color: var(--gray-500); 
  border-radius: calc(var(--radius) + 0.125rem); 
  padding: clamp(0.5rem, 1.2vw, 0.625rem); 
  font-size: clamp(0.7rem, 1vw, 0.75rem); 
  cursor: pointer; 
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
  display: flex; 
  align-items: center; 
  justify-content: center; 
  gap: 0.375rem; 
  font-weight: 600;
  min-height: 36px;
}
.add-shift-btn:hover { 
  border-color: var(--accent-secondary); 
  color: white; 
  background: linear-gradient(135deg, var(--accent-secondary) 0%, var(--accent-tertiary) 100%); 
  transform: translateY(-1px);
  box-shadow: 0 3px 10px rgba(141, 119, 171, 0.25);
}

.modal-content { 
  border: none; 
  border-radius: var(--radius-lg); 
  box-shadow: var(--shadow-md); 
  background: var(--lighter);
}
.modal-header { 
  border-bottom: 1px solid var(--light); 
  padding: 1.25rem 1.5rem; 
  background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%);
  color: white;
}
.modal-title { 
  margin-bottom: 0; 
  line-height: 1.5; 
  font-weight: 600; 
  color: white;
  display: flex; 
  align-items: center; 
  gap: 0.5rem; 
}
.modal-body { padding: 1.5rem; }
.modal-footer { 
  border-top: 1px solid var(--light); 
  padding: 1rem 1.5rem; 
  gap: 0.75rem; 
}

.form-group { margin-bottom: 1rem; }
.form-label { 
  display: block; 
  font-size: 0.875rem; 
  font-weight: 600; 
  color: var(--primary); 
  margin-bottom: 0.5rem; 
}
.form-control { 
  width: 100%; 
  border: 1px solid var(--light); 
  border-radius: var(--radius); 
  padding: 0.625rem 0.875rem; 
  font-size: 0.875rem; 
  transition: var(--transition); 
  background: white;
}
.form-control:focus { 
  outline: none; 
  border-color: var(--accent-secondary); 
  box-shadow: 0 0 0 3px rgba(141, 119, 171, 0.15); 
}
.role-select { 
  appearance: none; 
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e"); 
  background-position: right 0.5rem center; 
  background-repeat: no-repeat; 
  background-size: 1.2em 1.2em; 
  padding-right: 2.5rem; 
}
.time-input-group { 
  display: flex; 
  gap: 0.5rem; 
  align-items: center; 
}
.time-separator { 
  color: var(--neutral); 
  font-weight: 600; 
}
.day-selector { 
  padding: 0.375rem 0.75rem; 
  border-radius: var(--radius); 
  font-size: 0.75rem; 
  font-weight: 500; 
  cursor: pointer; 
  transition: var(--transition); 
  border: 1px solid var(--light);
  background: white;
  color: var(--primary);
}
.day-selector:hover,
.day-selector.active {
  background: linear-gradient(135deg, var(--accent-secondary) 0%, var(--accent-tertiary) 100%);
  color: white;
  border-color: var(--accent-secondary);
}

.empty-state { 
  padding: 3rem 1rem; 
  text-align: center; 
  color: var(--neutral); 
}
.empty-state-icon { 
  font-size: 2.5rem; 
  margin-bottom: 1rem; 
  opacity: 0.5; 
}
.empty-state-text { margin-bottom: 1.5rem; }
.loading-shimmer { 
  background: linear-gradient(90deg, var(--light) 25%, var(--lighter) 50%, var(--light) 75%); 
  background-size: 200% 100%; 
  animation: loading 1.5s infinite; 
  border-radius: var(--radius); 
  height: 1rem; 
  margin-bottom: 0.5rem; 
}
@keyframes loading { 
  0% { background-position: 200% 0; } 
  100% { background-position: -200% 0; } 
}

.fade-in { animation: fadeIn 0.3s ease-in; }
@keyframes fadeIn { 
  from { opacity: 0; transform: translateY(10px); } 
  to { opacity: 1; transform: translateY(0); } 
}

/* ===== RESPONSIVE DESIGN ===== */

/* Desktop Large (1200px+) - Default */

/* Tablet Landscape (1024px - 1199px) */
@media (max-width: 1199px) { 
  .grid-header, .grid-row { 
    grid-template-columns: 200px repeat(7, 1fr); 
  }
  .employee-cell {
    padding: 0.875rem 0.625rem;
  }
}

/* Tablet Portrait (768px - 1023px) */
@media (max-width: 1023px) { 
  .schedule-container {
    padding: 1.5rem 0;
  }
  .schedule-grid { 
    border-radius: 0.75rem;
    -webkit-overflow-scrolling: touch;
  } 
  .grid-header, .grid-row { 
    min-width: 1000px;
    grid-template-columns: 200px repeat(7, minmax(110px, 1fr)); 
  }
  .grid-header-cell:first-child,
  .employee-cell {
    position: sticky;
    left: 0;
  }
  .week-controls {
    flex-wrap: wrap;
    gap: 1rem;
  }
  .week-navigation {
    flex: 1;
    min-width: 280px;
  }
  .publish-section {
    flex: 1;
    justify-content: flex-end;
  }
}

/* Mobile Large (640px - 767px) */
@media (max-width: 767px) {
  .page-header-top {
    margin-bottom: 0.5rem;
  }
  .week-controls { 
    flex-direction: column; 
    align-items: stretch; 
    gap: 1.25rem;
    padding: 1rem;
  }
  .week-navigation {
    width: 100%;
    justify-content: center;
  }
  .publish-section { 
    width: 100%; 
    justify-content: space-between; 
  }
  .modal-dialog { 
    margin: 1rem; 
    max-width: calc(100vw - 2rem);
  }
}

/* Mobile Medium (480px - 639px) */
@media (max-width: 639px) {
  .container-fluid { 
    padding-left: 1rem; 
    padding-right: 1rem; 
  }
  .page-header {
    margin-bottom: 1.25rem;
  }
  .page-title {
    font-size: 1.5rem;
  }
  .week-controls {
    padding: 0.875rem;
  }
  .week-nav-btn {
    padding: 0.5rem;
    min-width: 40px;
    min-height: 40px;
  }
  .time-input-group { 
    flex-direction: column; 
    align-items: stretch; 
  }
  .time-separator { 
    text-align: center; 
  }
  .publish-section .btn {
    flex: 1;
  }
}
</style>

<div class="schedule-container">
  <div class="container-fluid px-3 px-md-4 py-4">
    <!-- Page Header -->
    <div class="page-header">
      <div class="page-header-top">
        <h1 class="page-title">
          <i class="fas fa-calendar-alt me-2"></i>Schedule
        </h1>
        <p class="page-subtitle">Manage your team's work schedule</p>
      </div>
      
      <!-- Week Controls Row -->
      <div class="week-controls">
        <div class="week-navigation" role="group" aria-label="Week navigation">
          <button class="week-nav-btn" id="prevWeekBtn" title="Previous week" aria-label="Previous week">
            <i class="fas fa-chevron-left"></i>
          </button>
          <div class="week-display" id="weekDisplay" aria-live="polite"></div>
          <button class="week-nav-btn" id="nextWeekBtn" title="Next week" aria-label="Next week">
            <i class="fas fa-chevron-right"></i>
          </button>
        </div>

        <div class="publish-section">
          <div class="tools-wrap" id="toolsWrap" style="display:none;">
            <button class="btn btn-outline btn-sm" id="toolsBtn">
              <i class="fas fa-tools me-1"></i> Tools
            </button>
            <div class="tools-menu" id="toolsMenu">
              <button class="tools-item" id="copyPrevToThisBtn">
                <i class="fas fa-copy me-2"></i>Copy from previous week → this
              </button>
              <button class="tools-item" id="copyThisToNextBtn">
                <i class="fas fa-forward me-2"></i>Copy this week → next
              </button>
              <button class="tools-item" id="openCopyWeekModalBtn">
                <i class="fas fa-calendar-week me-2"></i>Copy week… (pick weeks)
              </button>
              <hr class="m-0">
              <button class="tools-item" id="openCopyUserModalBtn">
                <i class="fas fa-users me-2"></i>Copy shifts: User → User
              </button>
            </div>
          </div>
          <span class="badge badge-warning" id="statusIndicator">Draft</span>
          <button class="btn btn-primary" id="publishBtn">
            <i class="fas fa-paper-plane me-1"></i>Publish
          </button>
        </div>
      </div>
    </div>

    <div class="schedule-grid">
      <div class="grid-header">
        <div class="grid-header-cell">Team members</div>
        <div class="grid-header-cell" data-day="0">Mon</div>
        <div class="grid-header-cell" data-day="1">Tue</div>
        <div class="grid-header-cell" data-day="2">Wed</div>
        <div class="grid-header-cell" data-day="3">Thu</div>
        <div class="grid-header-cell" data-day="4">Fri</div>
        <div class="grid-header-cell" data-day="5">Sat</div>
        <div class="grid-header-cell" data-day="6">Sun</div>
      </div>
      <div class="grid-body" id="scheduleGridBody"></div>
    </div>

    <div id="emptyState" class="empty-state" style="display: none;">
      <div class="empty-state-icon">
        <i class="fas fa-users-slash"></i>
      </div>
      <div class="empty-state-text">No team members available for scheduling</div>
    </div>
  </div>
</div>

<!-- Add Shift Modal -->
<div class="modal fade" id="shiftModal" tabindex="-1" aria-labelledby="shiftModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="shiftModalLabel" class="modal-title">
          <i class="fas fa-plus-circle me-2"></i>Add Shift
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" title="Close"></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label" for="startTime">Time</label>
          <div class="time-input-group">
            <input type="time" id="startTime" class="form-control" value="09:00">
            <span class="time-separator">–</span>
            <input type="time" id="endTime" class="form-control" value="17:00">
          </div>
        </div>
        <div class="form-group">
          <label class="form-label" for="shiftRole">Role</label>
          <select id="shiftRole" class="form-control role-select"></select>
        </div>
        <div class="form-group">
          <label class="form-label">Apply to:</label>
          <div class="d-flex gap-2 flex-wrap mt-2">
            <button type="button" class="btn btn-outline btn-sm day-selector" data-day="1">Mon</button>
            <button type="button" class="btn btn-outline btn-sm day-selector" data-day="2">Tue</button>
            <button type="button" class="btn btn-outline btn-sm day-selector" data-day="3">Wed</button>
            <button type="button" class="btn btn-outline btn-sm day-selector" data-day="4">Thu</button>
            <button type="button" class="btn btn-outline btn-sm day-selector" data-day="5">Fri</button>
            <button type="button" class="btn btn-outline btn-sm day-selector" data-day="6">Sat</button>
            <button type="button" class="btn btn-outline btn-sm day-selector" data-day="0">Sun</button>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label" for="shiftNotes">Shift Notes:</label>
          <textarea id="shiftNotes" class="form-control" rows="3" placeholder="Notes employees will see."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="saveShiftBtn">
          <i class="fas fa-save me-1"></i>Add Shift
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Copy Week Modal -->
<div class="modal fade" id="copyWeekModal" tabindex="-1" aria-labelledby="copyWeekModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="copyWeekModalLabel" class="modal-title">
          <i class="fas fa-copy me-2"></i>Copy Week
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Source week (Monday)</label>
          <input type="date" id="cwSource" class="form-control" disabled>
        </div>
        <div class="form-group">
          <label class="form-label">Target week (Monday)</label>
          <input type="date" id="cwTarget" class="form-control">
        </div>
        <div class="form-check mt-2">
          <input class="form-check-input" type="checkbox" id="cwOverwrite">
          <label class="form-check-label" for="cwOverwrite">Overwrite target week (delete existing shifts)</label>
        </div>
        <small class="text-muted d-block mt-2">Tip: target must be a Monday—use the date picker.</small>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="cwDoCopyBtn">
          <i class="fas fa-copy me-1"></i>Copy
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Copy User→User Modal -->
<div class="modal fade" id="copyUserModal" tabindex="-1" aria-labelledby="copyUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="copyUserModalLabel" class="modal-title">
          <i class="fas fa-users me-2"></i>Copy Shifts: User → User (this week)
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">From user</label>
          <select id="cuFrom" class="form-control"></select>
        </div>
        <div class="form-group">
          <label class="form-label">To user</label>
          <select id="cuTo" class="form-control"></select>
        </div>
        <div class="form-group">
          <label class="form-label">Days</label>
          <div class="d-flex gap-2 flex-wrap mt-1" id="cuDays">
            <button type="button" class="btn btn-outline btn-sm cu-day" data-day="1">Mon</button>
            <button type="button" class="btn btn-outline btn-sm cu-day" data-day="2">Tue</button>
            <button type="button" class="btn btn-outline btn-sm cu-day" data-day="3">Wed</button>
            <button type="button" class="btn btn-outline btn-sm cu-day" data-day="4">Thu</button>
            <button type="button" class="btn btn-outline btn-sm cu-day" data-day="5">Fri</button>
            <button type="button" class="btn btn-outline btn-sm cu-day" data-day="6">Sat</button>
            <button type="button" class="btn btn-outline btn-sm cu-day" data-day="0">Sun</button>
          </div>
        </div>
        <div class="form-check mt-1">
          <input class="form-check-input" type="checkbox" id="cuOverwrite">
          <label class="form-check-label" for="cuOverwrite">Overwrite target user's shifts for chosen days</label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="cuDoCopyBtn">
          <i class="fas fa-copy me-1"></i>Copy shifts
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Copy Single Shift Modal -->
<div class="modal fade" id="copyOneModal" tabindex="-1" aria-labelledby="copyOneModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="copyOneModalLabel" class="modal-title">
          <i class="fas fa-copy me-2"></i>Copy This Shift
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="coShiftId">
        <div class="form-group">
          <label class="form-label">To user</label>
          <select id="coTo" class="form-control"></select>
        </div>
        <div class="form-group">
          <label class="form-label">Target date</label>
          <input type="date" id="coDate" class="form-control">
        </div>
        <small class="text-muted d-block mt-2">Time and notes will be copied; you can move it to any date.</small>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="coDoCopyBtn">
          <i class="fas fa-copy me-1"></i>Copy
        </button>
      </div>
    </div>
  </div>
</div>

<?php require_once 'app/views/templates/footer.php'; ?>

<script>
// ===== Spinner-aware strict JSON fetch =====
async function fetchJSON(url, options = {}) {
  Spinner.show();
  try {
    const res = await fetch(url, { headers: { 'Content-Type': 'application/json' }, ...options });
    const text = await res.text();
    if (!res.ok) throw new Error(text || `HTTP ${res.status}`);

    let data;
    try { data = JSON.parse(text); } catch (e) { console.error('[fetchJSON] Invalid JSON:', text.slice(0, 400)); throw e; }
    return data;
  } finally {
    Spinner.hide();
  }
}

// ===== State =====
let employees = [];
let shifts = [];
let currentWeekStart = null;
let accessLevel = 1;
let userEditableDeptIds = [];
let shiftModal, copyWeekModal, copyUserModal, copyOneModal;
let currentEmployee = null;
let selectedDays = new Set();
let cuSelectedDays = new Set();

// Department colors (consistent palette)
const DEPT_COLORS = [
  '#3b82f6', // blue
  '#10b981', // green
  '#f59e0b', // amber
  '#8b5cf6', // purple
  '#ec4899', // pink
  '#06b6d4', // cyan
  '#f97316', // orange
  '#6366f1', // indigo
  '#14b8a6', // teal
  '#a855f7'  // violet
];

function getDepartmentColor(deptId) {
  if (!deptId) return '#9ca3af'; // gray for no department
  return DEPT_COLORS[(deptId - 1) % DEPT_COLORS.length];
}

// ===== Dates =====
function mondayOf(dateStr) {
  const d = new Date(dateStr + 'T12:00:00');
  const dow = d.getDay(); // 0..6 (Sun..Sat)
  const offset = (dow === 0) ? 6 : (dow - 1);
  d.setDate(d.getDate() - offset);
  return d.toISOString().slice(0,10);
}
function nextMonday(ymd) {
  const d = new Date(ymd + 'T12:00:00');
  const dow = d.getDay();            // 0=Sun..6=Sat
  const add = (8 - (dow || 7));      // days to next Mon
  d.setDate(d.getDate() + add);
  return d.toISOString().slice(0,10);
}
function formatWeekDisplay(mondayStr) {
  const mon = new Date(mondayStr + 'T12:00:00');
  const sun = new Date(mon); sun.setDate(sun.getDate() + 6);
  const m = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
  const same = mon.getMonth() === sun.getMonth();
  const left  = `${mon.toLocaleDateString('en-US',{weekday:'short'})}, ${m[mon.getMonth()]} ${mon.getDate()}`;
  const right = `${sun.toLocaleDateString('en-US',{weekday:'short'})}, ${same ? '' : (m[sun.getMonth()]+' ')}${sun.getDate()}`;
  return `Week of ${left} - ${right}`;
}
function weekDays(mondayStr) {
  const mon = new Date(mondayStr + 'T12:00:00');
  const m = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
  const arr = [];
  for (let i=0;i<7;i++) {
    const d = new Date(mon); d.setDate(d.getDate()+i);
    arr.push({ date: d.toISOString().slice(0,10), display: `${d.toLocaleDateString('en-US',{weekday:'short'})}, ${m[d.getMonth()]} ${d.getDate()}` });
  }
  return arr;
}

// ===== Init =====
document.addEventListener('DOMContentLoaded', async () => {
  shiftModal    = new bootstrap.Modal(document.getElementById('shiftModal'));
  copyWeekModal = new bootstrap.Modal(document.getElementById('copyWeekModal'));
  copyUserModal = new bootstrap.Modal(document.getElementById('copyUserModal'));
  copyOneModal  = new bootstrap.Modal(document.getElementById('copyOneModal'));

  const today = new Date().toISOString().slice(0,10);
  currentWeekStart = mondayOf(today);

  document.getElementById('prevWeekBtn').addEventListener('click', () => changeWeek(-7));
  document.getElementById('nextWeekBtn').addEventListener('click', () => changeWeek(7));
  document.getElementById('publishBtn').addEventListener('click', togglePublish);
  document.getElementById('saveShiftBtn').addEventListener('click', saveShift);

  // day selectors (Add Shift modal)
  document.querySelectorAll('.day-selector').forEach((btn) => {
    btn.addEventListener('click', (e) => {
      const b = e.currentTarget;
      const day = b.dataset.day;
      if (selectedDays.has(day)) {
        selectedDays.delete(day); 
        b.classList.remove('active'); 
        b.classList.add('btn-outline');
      } else {
        selectedDays.add(day); 
        b.classList.remove('btn-outline'); 
        b.classList.add('active');
      }
    });
  });

  // Tools dropdown (admin only)
  const toolsBtn  = document.getElementById('toolsBtn');
  const toolsMenu = document.getElementById('toolsMenu');
  if (toolsBtn) {
    toolsBtn.addEventListener('click', () => toolsMenu.classList.toggle('show'));
    document.addEventListener('click', (e) => { if (!document.getElementById('toolsWrap').contains(e.target)) toolsMenu.classList.remove('show'); });
  }

  // Quick tools actions
  document.getElementById('copyPrevToThisBtn').addEventListener('click', () => copyPrevToThis());
  document.getElementById('copyThisToNextBtn').addEventListener('click', () => copyThisToNext());
  document.getElementById('openCopyWeekModalBtn').addEventListener('click', () => openCopyWeekModal());
  document.getElementById('openCopyUserModalBtn').addEventListener('click', () => openCopyUserModal());
  document.getElementById('cwDoCopyBtn').addEventListener('click', () => doCopyWeek());
  document.getElementById('cuDoCopyBtn').addEventListener('click', () => doCopyUser());
  document.getElementById('coDoCopyBtn').addEventListener('click', () => doCopyOne());

  // days pickers inside Copy User modal
  document.querySelectorAll('.cu-day').forEach((b) => {
    b.addEventListener('click', () => { 
      const d = b.dataset.day;
      if (cuSelectedDays.has(d)) { 
        cuSelectedDays.delete(d); 
        b.classList.remove('active'); 
        b.classList.add('btn-outline'); 
      } else { 
        cuSelectedDays.add(d); 
        b.classList.remove('btn-outline'); 
        b.classList.add('active'); 
      }
    });
  });

  await loadEmployees();
  await loadWeek();
});

// ===== Week nav =====
function changeWeek(deltaDays) {
  const cur = new Date(currentWeekStart + 'T12:00:00');
  cur.setDate(cur.getDate() + deltaDays);
  currentWeekStart = mondayOf(cur.toISOString().slice(0,10));
  loadWeek();
}

// ===== Loads =====
async function loadEmployees() {
  try { 
    const data = await fetchJSON('/schedule/api?a=employees.list');
    employees = data.employees || [];
    userEditableDeptIds = data.user_editable_dept_ids || [];
    accessLevel = parseInt(data.access_level || 1);
  }
  catch (e) { 
    console.error('Error loading employees:', e); 
    employees = [];
    userEditableDeptIds = [];
  }
}

async function loadWeek() {
  try {
    const data = await fetchJSON(`/schedule/api?a=shifts.week&week=${currentWeekStart}`);
    shifts  = data.shifts || [];
    accessLevel = parseInt(data.access_level || 1);

    updateWeekHeader();
    renderGrid();
    await loadPublishStatus();

    // show/hide tools for Level 1 and Level 3+ (exclude Level 2)
    const toolsWrap = document.getElementById('toolsWrap');
    if (toolsWrap) toolsWrap.style.display = (accessLevel === 1 || accessLevel >= 3) ? 'block' : 'none';
    
    // Update grid header height after rendering
    setTimeout(setGridHeaderHeight, 0);
  } catch (e) {
    console.error('Error loading week:', e);
    showError('Failed to load schedule data');
  }
}

async function loadPublishStatus() {
  try {
    const status = await fetchJSON(`/schedule/api?a=publish.status&week=${currentWeekStart}`);
    const ind = document.getElementById('statusIndicator');
    const btn = document.getElementById('publishBtn');
    if (status.published) { 
      ind.textContent = 'Published'; 
      ind.className = 'badge badge-success'; 
      btn.innerHTML = '<i class="fas fa-undo me-1"></i>Unpublish'; 
    } else { 
      ind.textContent = 'Draft'; 
      ind.className = 'badge badge-warning'; 
      btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Publish'; 
    }
    btn.style.display = (accessLevel === 1 || accessLevel >= 3) ? 'block' : 'none';
  } catch (e) { console.error('Error loading publish status:', e); }
}

// ===== UI =====
function updateWeekHeader() {
  document.getElementById('weekDisplay').textContent = formatWeekDisplay(currentWeekStart);
  const days = weekDays(currentWeekStart);
  document.querySelectorAll('.grid-header-cell[data-day]').forEach((cell, idx) => { if (days[idx]) cell.textContent = days[idx].display; });

  const active = employees.filter(emp => emp.is_active !== 0 && emp.is_active !== false);
  const teamHeader = document.querySelector('.grid-header-cell:first-child');
  if (teamHeader) teamHeader.textContent = `Team members (${active.length})`;
}

function renderGrid() {
  const body = document.getElementById('scheduleGridBody');
  const emptyState = document.getElementById('emptyState');
  body.innerHTML = '';

  const activeEmployees = employees.filter(emp => emp.is_active !== 0 && emp.is_active !== false);
  if (activeEmployees.length === 0) { emptyState.style.display = 'block'; return; }
  emptyState.style.display = 'none';

  const days = weekDays(currentWeekStart);

  // Group employees by department
  const grouped = {};
  activeEmployees.forEach(emp => {
    const deptId = emp.department_id || 0;
    const deptName = emp.department_name || 'No Department';
    if (!grouped[deptId]) {
      grouped[deptId] = { id: deptId, name: deptName, employees: [] };
    }
    grouped[deptId].employees.push(emp);
  });

  // Sort departments by name
  const depts = Object.values(grouped).sort((a, b) => a.name.localeCompare(b.name));

  // Render each department group
  depts.forEach(dept => {
    const deptColor = getDepartmentColor(dept.id);
    const isEditable = userEditableDeptIds.includes(dept.id);

    // Department header
    const deptHeader = document.createElement('div');
    deptHeader.className = 'department-group-header';
    deptHeader.style.borderLeft = `4px solid ${deptColor}`;
    deptHeader.innerHTML = `
      <div style="display: flex; align-items: center; gap: 0.5rem;">
        <div style="width: 12px; height: 12px; border-radius: 50%; background: ${deptColor};"></div>
        <strong>${escapeHtml(dept.name)}</strong>
        <span style="color: var(--neutral); font-size: 0.875rem;">(${dept.employees.length})</span>
        ${!isEditable ? '<span class="badge" style="background: var(--gray-400); color: white; font-size: 0.7rem;">View Only</span>' : ''}
      </div>
    `;
    body.appendChild(deptHeader);

    // Render employees in this department
    dept.employees.forEach(emp => {
      const row = document.createElement('div');
      row.className = 'grid-row fade-in';
      row.style.borderLeft = `4px solid ${deptColor}`;

      const empCell = document.createElement('div');
      empCell.className = 'employee-cell';
      if (!isEditable) empCell.style.opacity = '0.7';

      const empShifts = shifts.filter(s => s.employee_id === emp.id);
      const hours = totalHours(empShifts);

      empCell.innerHTML = `
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <div class="employee-name">${escapeHtml(emp.name)}</div>
            <div class="employee-role">${escapeHtml(emp.role_title || '')}</div>
          </div>
          ${isEditable ? `<button class="btn btn-outline btn-sm" title="Copy this user's shifts to another user" onclick="openCopyUserModal(${emp.id})">
            <i class="fas fa-copy me-1"></i>Copy
          </button>` : ''}
        </div>
        <div class="employee-hours">${hours.toFixed(2)} hrs</div>
      `;
      row.appendChild(empCell);

      days.forEach(day => {
        const cell = document.createElement('div');
        cell.className = 'day-cell';
        cell.style.borderLeft = `1px solid ${deptColor}20`; // 20% opacity

        const todays = empShifts.filter(s => (s.start_dt || '').slice(0,10) === day.date);
        todays.forEach(shift => cell.appendChild(shiftBlock(shift, deptColor, isEditable)));

        // Only allow adding shifts for editable departments
        if (isEditable) {
          const add = document.createElement('div'); 
          add.className = 'add-shift-area';
          const btn = document.createElement('button');
          btn.className = 'add-shift-btn';
          btn.innerHTML = `
            <i class="fas fa-plus"></i> Add shift
          `;
          btn.addEventListener('click', () => openShiftModal(emp, day.date));
          add.appendChild(btn);
          cell.appendChild(add);
        }
        row.appendChild(cell);
      });
      body.appendChild(row);
    });
  });
}

function shiftBlock(shift, deptColor, isEditable) {
  const div = document.createElement('div');
  div.className = 'shift-block';
  div.style.borderLeft = `3px solid ${deptColor}`;
  div.style.background = `linear-gradient(135deg, ${deptColor}15, ${deptColor}08)`;
  
  const t1 = (shift.start_dt || '').slice(11,16);
  const t2 = (shift.end_dt   || '').slice(11,16);
  div.innerHTML = `
    <div class="shift-time">${t1}-${t2}</div>
    <div class="shift-role">${escapeHtml(shift.notes || shift.employee_role || '')}</div>
    ${isEditable ? `
      <div class="shift-actions">
        <button class="shift-mini" title="Copy" onclick="openCopyOne(${shift.id}, '${(shift.start_dt||'').slice(0,10)}')">
          <i class="fas fa-copy"></i>
        </button>
        <button class="shift-mini" title="Delete" onclick="deleteShift(${shift.id})">
          <i class="fas fa-times"></i>
        </button>
      </div>` : ''}
  `;
  return div;
}

async function loadRolesIntoModal() {
  try {
    const roles = await fetchJSON('/schedule/api?a=roles.list');
    const sel = document.getElementById('shiftRole');
    sel.innerHTML = '';
    roles.forEach(r => { const o = document.createElement('option'); o.value = r.name; o.textContent = r.name; sel.appendChild(o); });
  } catch (e) { console.error('Could not load roles:', e); }
}

// ===== Modal/CRUD =====
async function openShiftModal(emp, ymd) {
  if (!(accessLevel === 1 || accessLevel >= 3)) return;
  currentEmployee = emp;
  selectedDays.clear();
  document.querySelectorAll('.day-selector').forEach((b) => { b.classList.remove('active'); b.classList.add('btn-outline'); });

  const dow = new Date(ymd + 'T12:00:00').getDay();
  const pre = document.querySelector(`.day-selector[data-day="${dow}"]`);
  if (pre) { selectedDays.add(String(dow)); pre.classList.add('active'); pre.classList.remove('btn-outline'); }

  await loadRolesIntoModal();

  document.getElementById('startTime').value = '09:00';
  document.getElementById('endTime').value   = '17:00';
  document.getElementById('shiftRole').value = emp.role_title || '';
  document.getElementById('shiftNotes').value = '';

  shiftModal.show();
}

async function saveShift() {
  if (!currentEmployee || selectedDays.size === 0) { showError('Please select at least one day'); return; }
  const startTime = document.getElementById('startTime').value;
  const endTime   = document.getElementById('endTime').value;
  const role      = document.getElementById('shiftRole').value;
  const notes     = document.getElementById('shiftNotes').value;
  if (!startTime || !endTime) { showError('Please select start and end times'); return; }

  try {
    const base = new Date(currentWeekStart + 'T12:00:00');
    for (const dayStr of selectedDays) {
      const dow = parseInt(dayStr, 10);
      let offset; if (dow === 0) offset = 6; else offset = dow - 1;
      const d = new Date(base); d.setDate(base.getDate() + offset);
      const ymd = d.toISOString().slice(0,10);
      const start_dt = `${ymd} ${startTime}:00`;
      const end_dt   = `${ymd} ${endTime}:00`;
      await fetchJSON('/schedule/api?a=shifts.create', { method: 'POST', body: JSON.stringify({ employee_id: currentEmployee.id, start_dt, end_dt, notes: notes || role }) });
    }
    shiftModal.hide();
    await loadWeek();
    showSuccess('Shift(s) added successfully');
  } catch (e) { console.error('Error saving shift:', e); showError('Error saving shift: ' + e.message); }
}

async function deleteShift(id) {
  if (!(accessLevel === 1 || accessLevel >= 3) || !confirm('Are you sure you want to delete this shift?')) return;
  try { await fetchJSON(`/schedule/api?a=shifts.delete&id=${id}`); await loadWeek(); showSuccess('Shift deleted successfully'); }
  catch (e) { console.error('Error deleting shift:', e); showError('Error deleting shift: ' + e.message); }
}

async function togglePublish() {
  if (!(accessLevel === 1 || accessLevel >= 3)) return;
  try {
    const status = await fetchJSON(`/schedule/api?a=publish.status&week=${currentWeekStart}`);
    const newStatus = !status.published;
    await fetchJSON('/schedule/api?a=publish.set', { method: 'POST', body: JSON.stringify({ week: currentWeekStart, published: newStatus ? 1 : 0 }) });
    await loadPublishStatus();
    showSuccess(`Schedule ${newStatus ? 'published' : 'unpublished'} successfully`);
  } catch (e) { console.error('Error toggling publish status:', e); showError('Error: ' + e.message); }
}

// ===== Tools: Copy week / user / one =====
function openCopyWeekModal() {
  if (!(accessLevel === 1 || accessLevel >= 3)) return;
  document.getElementById('cwSource').value = currentWeekStart;
  document.getElementById('cwTarget').value = nextMonday(currentWeekStart);
  document.getElementById('cwOverwrite').checked = false;
  copyWeekModal.show();
}
async function doCopyWeek() {
  const source_week = document.getElementById('cwSource').value;
  const target_week = document.getElementById('cwTarget').value;
  const overwrite   = document.getElementById('cwOverwrite').checked ? 1 : 0;
  if (!target_week) { showError('Pick a target Monday'); return; }
  try {
    await fetchJSON('/schedule/api?a=shifts.copyWeek', { method:'POST', body: JSON.stringify({ source_week, target_week, overwrite }) });
    copyWeekModal.hide();
    await loadWeek(); // in case you copied to current week
    showSuccess('Week copied');
  } catch (e) { showError('Copy failed: ' + e.message); }
}
async function copyPrevToThis() {
  const prevMon = (()=>{ const d=new Date(currentWeekStart+'T12:00:00'); d.setDate(d.getDate()-7); return d.toISOString().slice(0,10); })();
  try {
    await fetchJSON('/schedule/api?a=shifts.copyWeek', { method:'POST', body: JSON.stringify({ source_week: prevMon, target_week: currentWeekStart, overwrite: 0 }) });
    await loadWeek();
    showSuccess('Copied previous week to this week');
  } catch (e) { showError('Copy failed: ' + e.message); }
}
async function copyThisToNext() {
  const nextMon = nextMonday(currentWeekStart);
  try {
    await fetchJSON('/schedule/api?a=shifts.copyWeek', { method:'POST', body: JSON.stringify({ source_week: currentWeekStart, target_week: nextMon, overwrite: 0 }) });
    showSuccess('Copied this week to next week');
  } catch (e) { showError('Copy failed: ' + e.message); }
}

function openCopyUserModal(prefillFromId = null) {
  if (!(accessLevel === 1 || accessLevel >= 3)) return;
  const fromSel = document.getElementById('cuFrom');
  const toSel   = document.getElementById('cuTo');
  fromSel.innerHTML = ''; toSel.innerHTML = '';
  employees.forEach(e => {
    const o1 = document.createElement('option'); o1.value = e.id; o1.textContent = e.name; fromSel.appendChild(o1);
    const o2 = document.createElement('option'); o2.value = e.id; o2.textContent = e.name; toSel.appendChild(o2);
  });
  if (prefillFromId) fromSel.value = String(prefillFromId);
  cuSelectedDays = new Set(['1','2','3','4','5','6','0']); // all days default
  document.querySelectorAll('.cu-day').forEach(b => { b.classList.remove('btn-outline'); b.classList.add('active'); });
  document.getElementById('cuOverwrite').checked = false;
  copyUserModal.show();
}
async function doCopyUser() {
  const from_employee_id = parseInt(document.getElementById('cuFrom').value,10);
  const to_employee_id   = parseInt(document.getElementById('cuTo').value,10);
  const overwrite        = document.getElementById('cuOverwrite').checked ? 1 : 0;
  if (!from_employee_id || !to_employee_id || from_employee_id === to_employee_id) { showError('Choose two different users'); return; }
  const days = Array.from(cuSelectedDays).map(x=>parseInt(x,10));
  try {
    await fetchJSON('/schedule/api?a=shifts.copyUserToUser', { method:'POST', body: JSON.stringify({ week: currentWeekStart, from_employee_id, to_employee_id, days, overwrite }) });
    copyUserModal.hide();
    await loadWeek();
    showSuccess('Shifts copied between users');
  } catch (e) { showError('Copy failed: ' + e.message); }
}

// Single-shift copy
function openCopyOne(shiftId, dateYmd) {
  if (!(accessLevel === 1 || accessLevel >= 3)) return;
  document.getElementById('coShiftId').value = String(shiftId);
  document.getElementById('coDate').value = dateYmd;
  const toSel = document.getElementById('coTo'); toSel.innerHTML = '';
  employees.forEach(e => { const o=document.createElement('option'); o.value=e.id; o.textContent=e.name; toSel.appendChild(o); });
  copyOneModal.show();
}
async function doCopyOne() {
  const shift_id = parseInt(document.getElementById('coShiftId').value,10);
  const to_employee_id = parseInt(document.getElementById('coTo').value,10);
  const target_date = document.getElementById('coDate').value;
  if (!shift_id || !to_employee_id || !target_date) { showError('Missing info'); return; }
  try {
    await fetchJSON('/schedule/api?a=shifts.copyShift', { method:'POST', body: JSON.stringify({ shift_id, to_employee_id, target_date }) });
    copyOneModal.hide();
    await loadWeek();
    showSuccess('Shift copied');
  } catch (e) { showError('Copy failed: ' + e.message); }
}

// ===== Utils =====
function totalHours(list) {
  return list.reduce((acc, s) => { const a=new Date(s.start_dt), b=new Date(s.end_dt); const h=(b-a)/36e5; return acc + (isFinite(h) ? h : 0); }, 0);
}
function escapeHtml(t=''){ const d = document.createElement('div'); d.textContent = t; return d.innerHTML; }
function showError(message) { alert('Error: ' + message); }
function showSuccess(message) { alert('Success: ' + message); }

// Measure and set grid header height as CSS variable for sticky positioning
function setGridHeaderHeight() {
  const gridHeader = document.querySelector('.grid-header');
  if (gridHeader) {
    const height = gridHeader.offsetHeight;
    document.documentElement.style.setProperty('--grid-header-height', `${height}px`);
  }
}

// Debounce helper
function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

// Call on load, resize (debounced), and orientation change
window.addEventListener('DOMContentLoaded', setGridHeaderHeight);
window.addEventListener('resize', debounce(setGridHeaderHeight, 150));
window.addEventListener('orientationchange', setGridHeaderHeight);

window.deleteShift = deleteShift;
window.openCopyUserModal = openCopyUserModal;
window.openCopyOne = openCopyOne;
</script>