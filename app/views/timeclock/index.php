<?php require 'app/views/templates/header.php'; ?>

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
  --success-light: #d1fae5;
  --warning: #f59e0b;
  --warning-light: #fef3c7;
  --danger: #ef4444;
  --danger-light: #fee2e2;
  --info: #3b82f6;
  --info-light: #dbeafe;

  /* UI Variables */
  --bg: var(--lighter);
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
}

body {
  background: linear-gradient(135deg, var(--lighter) 0%, var(--light) 100%);
  color: var(--ink);
  margin: 0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Inter', sans-serif;
  min-height: 100vh;
  line-height: 1.6;
}

.container {
  max-width: 1200px;
  padding: 32px 20px;
}

/* Enhanced Card Design */
.tw-card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  overflow: hidden;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  backdrop-filter: blur(20px);
  margin-bottom: 32px;
  position: relative;
}

.tw-card:hover {
  box-shadow: var(--shadow-lg);
  transform: translateY(-4px);
}

.tw-card::before {
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

.tw-card:hover::before {
  opacity: 1;
}

.tw-card__header {
  padding: 32px 28px;
  text-align: center;
  background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
  color: white;
  position: relative;
  overflow: hidden;
}

.tw-card__header::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(45deg, rgba(181, 158, 95, 0.1) 0%, rgba(141, 119, 171, 0.1) 100%);
  pointer-events: none;
}

.tw-card__title {
  margin: 0;
  font-size: 2rem;
  font-weight: 800;
  letter-spacing: -0.02em;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
}

.tw-card__body {
  padding: 36px 32px;
}

/* Enhanced Status Indicators */
.tw-status {
  display: inline-flex;
  gap: 10px;
  align-items: center;
  padding: 12px 24px;
  border-radius: 50px;
  background: rgba(255, 255, 255, 0.15);
  color: white;
  font-size: 1rem;
  font-weight: 700;
  border: 2px solid rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(20px);
  transition: all 0.3s ease;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.tw-status--in {
  background: linear-gradient(135deg, var(--success) 0%, #0da271 100%);
  border-color: rgba(16, 185, 129, 0.4);
  box-shadow: 0 8px 24px rgba(16, 185, 129, 0.3);
}

.tw-status--break {
  background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%);
  border-color: rgba(181, 158, 95, 0.4);
  box-shadow: 0 8px 24px rgba(181, 158, 95, 0.3);
}

.tw-status--out {
  background: rgba(255, 255, 255, 0.2);
  border-color: rgba(255, 255, 255, 0.3);
}

/* Enhanced Clock Display */
.tc-clock {
  text-align: center;
  margin-bottom: 32px;
  padding: 32px;
  background: linear-gradient(135deg, rgba(181, 158, 95, 0.05) 0%, rgba(141, 119, 171, 0.05) 100%);
  border-radius: var(--radius-lg);
  border: 2px solid var(--border);
  position: relative;
  overflow: hidden;
}

.tc-clock::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23b59e5f' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
  opacity: 0.3;
}

.tc-time {
  font-variant-numeric: tabular-nums;
  font-size: 5rem;
  line-height: 1;
  margin-bottom: 16px;
  font-weight: 300;
  color: var(--primary);
  text-shadow: 0 4px 12px rgba(9, 25, 77, 0.1);
  letter-spacing: -0.03em;
  position: relative;
}

.tc-date {
  color: var(--muted);
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: 24px;
  position: relative;
}

.tc-badges {
  margin-top: 24px;
  display: flex;
  gap: 16px;
  justify-content: center;
  flex-wrap: wrap;
  position: relative;
}

.tc-badge {
  display: inline-flex;
  gap: 10px;
  align-items: center;
  background: var(--light);
  padding: 12px 20px;
  border-radius: 50px;
  color: var(--primary);
  font-size: 1rem;
  font-weight: 700;
  border: 2px solid var(--border);
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(9, 25, 77, 0.08);
  position: relative;
  overflow: hidden;
}

.tc-badge::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
  transition: left 0.5s;
}

.tc-badge:hover::before {
  left: 100%;
}

.tc-badge--break {
  background: linear-gradient(135deg, var(--accent-light) 0%, var(--accent) 100%);
  color: white;
  border-color: var(--accent);
}

.tc-badge--timer {
  background: linear-gradient(135deg, var(--accent-secondary) 0%, var(--accent-tertiary) 100%);
  color: white;
  border-color: var(--accent-secondary);
  font-size: 1.2rem;
  padding: 14px 24px;
  animation: pulse-glow 2s ease-in-out infinite;
}

/* Enhanced Mini Cards */
.tw-mini {
  background: linear-gradient(135deg, var(--lighter) 0%, white 100%);
  border: 2px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 28px;
  transition: all 0.3s ease;
  height: 100%;
  box-shadow: 0 4px 16px rgba(9, 25, 77, 0.05);
  position: relative;
  overflow: hidden;
}

.tw-mini::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 4px;
  height: 100%;
  background: linear-gradient(180deg, var(--accent), var(--accent-secondary));
  transform: scaleY(0);
  transition: transform 0.3s ease;
}

.tw-mini:hover::before {
  transform: scaleY(1);
}

.tw-mini:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow-lg);
  border-color: var(--accent-light);
}

.tw-mini strong {
  font-size: 1.1rem;
  display: flex;
  align-items: center;
  gap: 10px;
  color: var(--primary);
}

.tw-badge {
  font-size: 0.8rem;
  padding: 8px 16px;
  border-radius: 50px;
  border: 2px solid var(--border);
  font-weight: 700;
  white-space: nowrap;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.tw-badge--unscheduled {
  background: linear-gradient(135deg, var(--accent-secondary) 0%, var(--accent-tertiary) 100%);
  color: white;
  border-color: var(--accent-secondary);
}

.tw-badge--upcoming {
  background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%);
  color: white;
  border-color: var(--accent);
}

.tw-badge--completed {
  background: linear-gradient(135deg, var(--success) 0%, #0da271 100%);
  color: white;
  border-color: var(--success);
}

.tw-badge--late {
  background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%);
  color: white;
  border-color: var(--warning);
}

.tw-badge--ontime {
  background: linear-gradient(135deg, var(--info) 0%, #2563eb 100%);
  color: white;
  border-color: var(--info);
}

.small {
  color: var(--muted);
  font-size: 1rem;
  line-height: 1.6;
  margin-top: 12px;
}

/* Enhanced Alert */
.alert-note {
  background: linear-gradient(135deg, var(--light) 0%, var(--lighter) 100%);
  color: var(--primary);
  border: 2px solid var(--accent-light);
  border-radius: var(--radius-lg);
  padding: 20px 24px;
  font-size: 1rem;
  display: flex;
  align-items: center;
  gap: 12px;
  box-shadow: 0 4px 16px rgba(181, 158, 95, 0.1);
  margin-bottom: 24px;
}

.alert-note i {
  color: var(--accent);
  font-size: 1.2rem;
}

/* Enhanced Action Buttons */
.tw-actions {
  gap: 16px;
  margin-top: 32px;
}

.btn {
  border-radius: var(--radius-lg);
  border: 2px solid transparent;
  min-width: 180px;
  font-weight: 700;
  padding: 16px 28px;
  transition: all 0.3s ease;
  font-size: 1rem;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  position: relative;
  overflow: hidden;
  cursor: pointer;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.btn::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
  transition: left 0.6s;
}

.btn:hover::before {
  left: 100%;
}

.btn-success {
  background: linear-gradient(135deg, var(--success) 0%, #0da271 100%);
  border-color: var(--success);
  color: white;
  box-shadow: 0 8px 24px rgba(16, 185, 129, 0.3);
}

.btn-danger {
  background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
  border-color: var(--danger);
  color: white;
  box-shadow: 0 8px 24px rgba(239, 68, 68, 0.3);
}

.btn-secondary {
  background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%);
  border-color: var(--accent);
  color: white;
  box-shadow: 0 8px 24px rgba(181, 158, 95, 0.3);
}

.btn-outline-secondary {
  background: white;
  border-color: var(--border);
  color: var(--primary);
  box-shadow: 0 4px 16px rgba(9, 25, 77, 0.08);
}

.btn-warning {
  background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%);
  border-color: var(--warning);
  color: white;
  box-shadow: 0 8px 24px rgba(245, 158, 11, 0.3);
}

.btn:hover:not(:disabled) {
  filter: brightness(1.1);
  transform: translateY(-4px);
  box-shadow: 0 12px 32px rgba(9, 25, 77, 0.2);
}

.btn:active:not(:disabled) {
  transform: translateY(-2px);
}

.btn:focus {
  outline: 3px solid var(--ring);
  outline-offset: 3px;
}

.btn:disabled {
  opacity: 0.6;
  transform: none;
  cursor: not-allowed;
  filter: grayscale(0.4);
  box-shadow: none;
}

/* Enhanced Table */
.table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  background: white;
}

.table thead th {
  color: var(--primary);
  font-weight: 800;
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  border-bottom: 3px solid var(--border);
  padding: 20px 16px;
  background: var(--lighter);
  position: sticky;
  top: 0;
}

.table tbody tr {
  background: white;
  transition: all 0.3s ease;
  border-bottom: 1px solid var(--light);
}

.table tbody tr:hover {
  background: var(--lighter);
  transform: translateX(8px);
  box-shadow: 0 4px 16px rgba(9, 25, 77, 0.1);
}

.table tbody td {
  padding: 20px 16px;
  border-bottom: 1px solid var(--light);
  font-size: 0.95rem;
  font-weight: 500;
}

.table tbody tr:last-child td {
  border-bottom: none;
}

.table-responsive {
  border-radius: var(--radius-lg);
  overflow: hidden;
  border: 2px solid var(--border);
  background: white;
  box-shadow: 0 4px 24px rgba(9, 25, 77, 0.08);
}

/* Enhanced Toast */
.position-fixed .toast {
  background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
  color: white;
  border: 0;
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-xl);
  min-width: 320px;
  border-left: 4px solid var(--accent);
}

.toast-body {
  padding: 20px 24px;
  font-weight: 600;
  font-size: 1rem;
}

/* Enhanced Busy Overlay */
#busyOverlay {
  position: fixed;
  inset: 0;
  background: rgba(9, 25, 77, 0.7);
  backdrop-filter: blur(12px);
  display: none;
  place-items: center;
  z-index: 9999;
}

#busyOverlay .spinner-border {
  width: 4rem;
  height: 4rem;
  color: var(--accent);
  border-width: 4px;
}

#busyOverlay small {
  display: block;
  color: white;
  margin-top: 20px;
  font-weight: 600;
  font-size: 1.1rem;
}

/* Enhanced Modal */
.modal-content {
  background: white;
  color: var(--primary);
  border: 2px solid var(--border);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-xl);
  overflow: hidden;
}

.modal-header {
  border-bottom: 2px solid var(--border);
  padding: 28px 32px;
  background: linear-gradient(135deg, var(--lighter) 0%, white 100%);
}

.modal-title {
  font-weight: 800;
  color: var(--primary);
  display: flex;
  align-items: center;
  gap: 12px;
  font-size: 1.4rem;
}

.modal-body {
  padding: 32px;
}

.modal-footer {
  border-top: 2px solid var(--border);
  padding: 24px 32px;
  background: var(--lighter);
}

.form-select {
  border-radius: var(--radius-lg);
  border: 2px solid var(--border);
  padding: 14px 20px;
  font-size: 1rem;
  transition: all 0.3s ease;
  background: white;
  font-weight: 500;
}

.form-select:focus {
  border-color: var(--accent);
  box-shadow: 0 0 0 4px rgba(181, 158, 95, 0.15);
  outline: none;
}

/* Status Detail */
#statusDetail {
  padding: 16px 24px;
  background: linear-gradient(135deg, rgba(181, 158, 95, 0.08) 0%, rgba(141, 119, 171, 0.08) 100%);
  border-radius: var(--radius-lg);
  border: 2px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  font-weight: 600;
  margin-bottom: 24px;
}

/* Total Hours Display */
.total-hours-display {
  background: linear-gradient(135deg, var(--accent-light) 0%, var(--accent) 100%);
  padding: 20px 28px;
  border-radius: var(--radius-lg);
  color: white;
  display: flex;
  align-items: center;
  gap: 16px;
  font-size: 1.2rem;
  box-shadow: 0 8px 32px rgba(181, 158, 95, 0.3);
  margin-top: 24px;
}

.text-accent {
  font-weight: 800;
  font-size: 1.5rem;
}

/* Shift Status Indicators */
.shift-status {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 8px 16px;
  border-radius: 50px;
  font-size: 0.85rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.shift-status--ontime {
  background: var(--success-light);
  color: var(--success);
  border: 1px solid var(--success);
}

.shift-status--late {
  background: var(--warning-light);
  color: var(--warning);
  border: 1px solid var(--warning);
}

.shift-status--upcoming {
  background: var(--info-light);
  color: var(--info);
  border: 1px solid var(--info);
}

/* Mobile Responsive */
@media (max-width: 768px) {
  .container {
    padding: 20px 16px;
  }

  .btn {
    min-width: calc(50% - 8px);
    font-size: 0.95rem;
    padding: 14px 20px;
  }

  .tw-card__title {
    font-size: 1.6rem;
  }

  .tc-time {
    font-size: 3.5rem;
  }

  .tw-card__body {
    padding: 28px 24px;
  }

  .table thead th,
  .table tbody td {
    padding: 16px 12px;
    font-size: 0.9rem;
  }

  .tw-mini {
    padding: 24px;
  }

  .tc-clock {
    padding: 24px;
  }

  .tw-actions {
    flex-direction: column;
  }

  .btn {
    min-width: 100%;
    margin-bottom: 12px;
  }
}

@media (max-width: 480px) {
  .tc-time {
    font-size: 2.8rem;
  }

  .tc-badge--timer {
    font-size: 1rem;
    padding: 12px 20px;
  }

  .tw-card__header {
    padding: 24px 20px;
  }

  .tw-card__body {
    padding: 24px 20px;
  }
}

/* Animation Enhancements */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateX(-30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes pulse-glow {
  0%, 100% {
    box-shadow: 0 8px 32px rgba(141, 119, 171, 0.4);
  }
  50% {
    box-shadow: 0 8px 40px rgba(141, 119, 171, 0.6);
  }
}

.tw-card {
  animation: fadeIn 0.8s ease-out;
}

.table tbody tr {
  animation: slideIn 0.5s ease-out;
}

/* Status Pulse Animation */
@keyframes status-pulse {
  0%, 100% {
    box-shadow: 0 8px 32px rgba(16, 185, 129, 0.4);
  }
  50% {
    box-shadow: 0 8px 40px rgba(16, 185, 129, 0.6);
  }
}

.tw-status--in {
  animation: status-pulse 2s ease-in-out infinite;
}

.tw-status--break {
  animation: status-pulse 2s ease-in-out infinite;
}

/* Enhanced Focus States */
.btn:focus-visible,
.form-select:focus-visible {
  outline: 3px solid var(--accent);
  outline-offset: 3px;
}

/* Loading States */
.skeleton {
  background: linear-gradient(90deg, var(--light) 25%, var(--lighter) 50%, var(--light) 75%);
  background-size: 200% 100%;
  animation: loading 2s infinite;
  border-radius: var(--radius-sm);
}

@keyframes loading {
  0% {
    background-position: 200% 0;
  }
  100% {
    background-position: -200% 0;
  }
}

/* Grace Period Indicator */
.grace-indicator {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 12px;
  background: var(--warning-light);
  color: var(--warning);
  border: 1px solid var(--warning);
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
  margin-left: 8px;
}

/* Progress Bar for Shift Completion */
.shift-progress {
  height: 6px;
  background: var(--light);
  border-radius: 3px;
  overflow: hidden;
  margin-top: 12px;
}

.shift-progress-bar {
  height: 100%;
  background: linear-gradient(90deg, var(--success), var(--accent));
  border-radius: 3px;
  transition: width 0.3s ease;
}

/* Enhanced Tooltips */
.tooltip {
  font-size: 0.85rem;
}

.tooltip .tooltip-inner {
  background: var(--primary);
  color: white;
  border-radius: var(--radius-sm);
  padding: 8px 12px;
  font-weight: 500;
}

/* Live Timer Animation */
@keyframes timer-pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.8; }
}

.tc-badge--timer {
  animation: timer-pulse 1s ease-in-out infinite;
}

/* Shift Card States */
.shift-card--completed {
  opacity: 0.7;
  filter: grayscale(0.3);
}

.shift-card--active {
  border-color: var(--success);
  box-shadow: 0 8px 32px rgba(16, 185, 129, 0.2);
}

.shift-card--upcoming {
  border-color: var(--accent);
  box-shadow: 0 8px 32px rgba(181, 158, 95, 0.2);
}
</style>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-10 col-xl-8">

      <!-- Main Time Clock Card -->
      <div class="tw-card shift-card--active">
        <div class="tw-card__header">
          <h1 class="tw-card__title">
            <i class="fas fa-clock"></i>Time Clock
          </h1>
          <div class="mt-4">
            <span id="statusPill" class="tw-status">
              <i class="fas fa-rotate fa-spin"></i> Loading status…
            </span>
          </div>
        </div>

        <div class="tw-card__body">
          <!-- Live Clock Display -->
          <div class="tc-clock mb-5">
            <div id="tcTime" class="tc-time">--:--:--</div> <br>
            <div id="tcDate" class="tc-date">Loading date…</div>
            <div class="tc-badges">
              <span id="tcDuration" class="tc-badge tc-badge--timer" style="display:none">
                <i class="fas fa-stopwatch"></i> <span id="liveTimer">00:00:00</span>
              </span>
              <span id="tcBreakBadge" class="tc-badge tc-badge--break" style="display:none">
                <i class="fas fa-coffee"></i> On Break • <span id="breakTimer">00:00</span>
              </span>
            </div>
          </div>

          <!-- Status Details -->
          <div id="statusDetail" class="small mb-4 text-center">
            <i class="fas fa-sync fa-spin me-2"></i>Fetching your current shift state…
          </div>

          <!-- Shift Information Cards -->
          <div class="row g-4 mb-5">
            <!-- Today's Shift Card -->
            <div class="col-md-6">
              <div class="tw-mini h-100" id="todayShiftCard">
                <div class="d-flex align-items-center justify-content-between mb-3">
                  <strong class="text-primary">
                    <i class="fas fa-calendar-day"></i>Today's Shift
                  </strong>
                  <div>
                    <span id="todayBadge" class="tw-badge tw-badge--unscheduled" style="display:none">
                      Unscheduled
                    </span>
                    <span id="shiftStatusBadge" class="shift-status" style="display:none"></span>
                  </div>
                </div>
                <div id="todayShift" class="small">Loading…</div>
                <div id="shiftProgress" class="shift-progress" style="display:none">
                  <div id="shiftProgressBar" class="shift-progress-bar" style="width: 0%"></div>
                </div>
                <div id="graceIndicator" class="grace-indicator mt-2" style="display:none">
                  <i class="fas fa-clock"></i>
                  <span id="graceText"></span>
                </div>
              </div>
            </div>

            <!-- Next Shift Card -->
            <div class="col-md-6">
              <div class="tw-mini h-100 shift-card--upcoming">
                <div class="d-flex align-items-center justify-content-between mb-3">
                  <strong class="text-primary">
                    <i class="fas fa-calendar-plus"></i>Next Shift
                  </strong>
                  <span class="tw-badge tw-badge--upcoming">Upcoming</span>
                </div>
                <div id="nextShift" class="small">—</div>
                <div id="nextShiftInfo" class="small text-muted mt-2" style="display:none">
                  <i class="fas fa-info-circle me-1"></i>
                  <span id="nextShiftTime"></span>
                </div>
              </div>
            </div>
          </div>

          <!-- Clocking Instructions -->
          <div id="clockHint" class="alert-note mb-4" style="display:none">
            <i class="fas fa-info-circle"></i> 
            <span>No scheduled shift today. You can still <strong>Clock In</strong>—it will be saved as <strong>Unscheduled</strong>.</span>
          </div>

          <!-- Action Buttons -->
          <div class="d-flex flex-wrap tw-actions justify-content-center">
            <button id="btnClockIn" class="btn btn-success" data-bs-toggle="tooltip">
              <i class="fas fa-play"></i> Clock In
            </button>
            <button id="btnBreakStart" class="btn btn-outline-secondary">
              <i class="fas fa-coffee"></i> Start Break
            </button>
            <button id="btnBreakEnd" class="btn btn-secondary">
              <i class="fas fa-mug-hot"></i> End Break
            </button>
            <button id="btnClockOut" class="btn btn-danger">
              <i class="fas fa-stop"></i> Clock Out
            </button>
          </div>
        </div>
      </div>

      <!-- Today's History Card -->
      <div class="tw-card">
        <div class="tw-card__header">
          <h2 class="tw-card__title" style="font-size:1.5rem">
            <i class="fas fa-list-check"></i>Today's History
          </h2>
        </div>
        <div class="tw-card__body">
          <div class="table-responsive">
            <table class="table align-middle mb-0">
              <thead>
                <tr>
                  <th><i class="fas fa-sign-in-alt me-1"></i>Clock In</th>
                  <th><i class="fas fa-sign-out-alt me-1"></i>Clock Out</th>
                  <th><i class="fas fa-pause me-1"></i>Break</th>
                  <th><i class="fas fa-tag me-1"></i>Type</th>
                  <th><i class="fas fa-clock me-1"></i>Hours</th>
                  <th><i class="fas fa-star me-1"></i>Status</th>
                </tr>
              </thead>
              <tbody id="todayList">
                <tr>
                  <td colspan="6" class="small text-center py-5">
                    <i class="fas fa-inbox me-2"></i>No time entries recorded today
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="d-flex justify-content-end align-items-center mt-4">
            <div class="total-hours-display">
              <i class="fas fa-chart-bar"></i>
              <span>Total Today:</span>
              <span id="todayTotalHours" class="text-accent">0.00</span>
              <span>hrs</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Toast Notifications -->
      <div class="position-fixed bottom-0 end-0 p-4" style="z-index:1080">
        <div id="toaster" class="toast align-items-center border-0" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="d-flex">
            <div id="toastMsg" class="toast-body">
              <i class="fas fa-check-circle me-2"></i>Action completed successfully
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Busy Overlay -->
<div id="busyOverlay">
  <div class="text-center">
    <div class="spinner-border" role="status" aria-label="Working…"></div>
    <small id="busyText">Processing your request…</small>
  </div>
</div>

<!-- Satisfaction Modal -->
<div class="modal fade" id="satisfactionModal" tabindex="-1" aria-labelledby="satisfactionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="satisfactionModalLabel">
          <i class="fas fa-star"></i>End of Shift Feedback
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="mb-3">How satisfied are you with your workday?</p>
        <select id="satisfactionSelect" class="form-select">
          <option value="" selected>— Select your satisfaction level —</option>
          <option value="1">1 — Very Dissatisfied</option>
          <option value="2">2 — Dissatisfied</option>
          <option value="3">3 — Neutral</option>
          <option value="4">4 — Satisfied</option>
          <option value="5">5 — Very Satisfied</option>
        </select>
        <small class="text-muted d-block mt-2">
          <i class="fas fa-info-circle me-1"></i>Your feedback helps improve the workplace experience
        </small>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" id="btnSkip" data-bs-dismiss="modal">
          Skip Survey
        </button>
        <button type="button" class="btn btn-primary" id="btnSubmit">
          <i class="fas fa-paper-plane me-1"></i> Submit Feedback
        </button>
      </div>
    </div>
  </div>
</div>

<script>
(function(){
  const $ = (s)=>document.querySelector(s);
  const $$ = (s)=>document.querySelectorAll(s);
  const tf = new Intl.DateTimeFormat(undefined,{hour:'2-digit',minute:'2-digit',second:'2-digit'});
  const df = new Intl.DateTimeFormat(undefined,{weekday:'long',year:'numeric',month:'long',day:'numeric'});

  const GRACE_MINUTES = 30;

  let state = {
    status:'loading',
    clockIn:null,
    breakStart:null, // Will be set to current time when we detect break status
    breakSeconds:0,
    today:[],
    todaySchedule:null,
    nextSchedule:null,
    breakTimer: null,
    lastBreakCheck: null // Track when we last checked break status
  };

  const ms = (m)=>m*60*1000;
  const parseISO = (v)=> v ? new Date(v) : null;
  const parseMaybe = (obj, a, b) => (obj && (obj[a] || obj[b])) || null;
  const isToday = (d)=> {
    const n=new Date(); return d &&
      d.getFullYear()===n.getFullYear() &&
      d.getMonth()===n.getMonth() &&
      d.getDate()===n.getDate();
  };
  const hasAnyScheduledEntryToday = ()=> state.today?.some(r => (r?.type||'Scheduled') === 'Scheduled' && (r?.in||r?.out));

  function secToHms(s){
    const h=String(Math.floor(s/3600)).padStart(2,'0');
    const m=String(Math.floor((s%3600)/60)).padStart(2,'0');
    const sec=String(Math.floor(s%60)).padStart(2,'0');
    return `${h}:${m}:${sec}`;
  }

  function secToHm(s){
    const h=Math.floor(s/3600);
    const m=String(Math.floor((s%3600)/60)).padStart(2,'0');
    return `${h}:${m}`;
  }

  function setBusy(on,msg){ 
    $('#busyOverlay').style.display = on?'grid':'none'; 
    if(msg) $('#busyText').textContent = msg; 
  }

  function toast(msg, type = 'success'){ 
    const icon = type === 'error' ? 'fas fa-exclamation-circle' : 
                type === 'warning' ? 'fas fa-exclamation-triangle' : 
                'fas fa-check-circle';
    $('#toastMsg').innerHTML = `<i class="${icon} me-2"></i> ${msg}`;
    new bootstrap.Toast($('#toaster'),{delay:3000}).show(); 
  }

  function pill(cls,html){ 
    const el=$('#statusPill'); 
    el.className='tw-status '+cls; 
    el.innerHTML=html; 
  }

  function scheduleWindowInfo(sched){
    const startISO = parseISO(parseMaybe(sched,'startAt',null));
    const endISO   = parseISO(parseMaybe(sched,'endAt',null));
    return { startISO, endISO };
  }

  function canClockInNow(sched){
    const now = new Date();
    if (!sched) return {allowed:true, reason:'Unscheduled shift allowed', unscheduled:true};
    const { startISO, endISO } = scheduleWindowInfo(sched);
    if (!startISO || !isToday(startISO)) return {allowed:true, reason:'Unscheduled shift allowed', unscheduled:true};

    const openAt  = new Date(startISO.getTime() - ms(GRACE_MINUTES));
    const closeAt = new Date(startISO.getTime() + ms(GRACE_MINUTES));
    const shiftEnd = endISO || new Date(startISO.getTime() + (8 * 60 * 60 * 1000)); // Default 8-hour shift

    if (now < openAt) return {
      allowed:false, 
      reason:`Clocking opens ${GRACE_MINUTES} minutes before your shift`,
      timeUntil: openAt - now
    };

    if (now > shiftEnd) return {
      allowed:false,
      reason:'Shift has already ended',
      ended: true
    };

    return {
      allowed:true, 
      late: now > closeAt, 
      onTime: now >= openAt && now <= closeAt,
      withinGrace: now >= openAt && now <= closeAt
    };
  }

  function shouldHideTodaysShiftCard(){
    if (state.status === 'in' || state.status === 'break') return false;
    if (!state.todaySchedule) return false;
    const { endISO } = scheduleWindowInfo(state.todaySchedule);
    if (!endISO) return false;
    const ended = new Date() > endISO;
    return ended && hasAnyScheduledEntryToday();
  }

  function formatTimeRangeOrDash(sched){
    if (!sched) return 'No scheduled shift';
    const start = parseISO(parseMaybe(sched,'startAt',null));
    const end   = parseISO(parseMaybe(sched,'endAt',null));
    if (start && end) return `${tf.format(start)} — ${tf.format(end)}`;
    return `${sched.start || '—'} — ${sched.end || '—'}`;
  }

  function updateLiveTimers(){
    const now = new Date();

    // Main session timer
    if(state.status==='in' && state.clockIn){
      const base = Math.max(0, ((now - new Date(state.clockIn))/1000) - (state.breakSeconds||0));
      $('#tcDuration').style.display = 'inline-flex';
      $('#liveTimer').textContent = secToHms(Math.floor(base));
    } else {
      $('#tcDuration').style.display = 'none';
    }

    // Break timer
    if(state.status==='break' && state.breakStart){
      const breakTime = Math.floor((now - new Date(state.breakStart))/1000);
      $('#breakTimer').textContent = secToHm(breakTime);
    }
  }

  function updateShiftProgress(){
    const progressBar = $('#shiftProgressBar');
    const progressContainer = $('#shiftProgress');
    const { startISO, endISO } = scheduleWindowInfo(state.todaySchedule);

    if (!startISO || !endISO || state.status === 'out') {
      progressContainer.style.display = 'none';
      return;
    }

    const now = new Date();
    const totalDuration = endISO - startISO;
    const elapsed = now - startISO;
    const progress = Math.min(100, Math.max(0, (elapsed / totalDuration) * 100));

    progressBar.style.width = `${progress}%`;
    progressContainer.style.display = 'block';
  }

  function updateUI(){
    // Update status pill and details
    if(state.status==='in'){
      pill('tw-status--in','<i class="fas fa-check-circle"></i> Clocked In'); 
      $('#statusDetail').textContent='Active shift in progress. Your time is being tracked.';
    }
    else if(state.status==='break'){
      pill('tw-status--break','<i class="fas fa-mug-hot"></i> On Break'); 
      $('#statusDetail').textContent='Break in progress. Remember to end break when ready.';
    }
    else { 
      pill('tw-status--out','<i class="far fa-circle"></i> Clocked Out'); 
      $('#statusDetail').textContent='You are currently clocked out. Ready for your next shift.';
    }

    $('#tcBreakBadge').style.display = state.status==='break' ? 'inline-flex' : 'none';

    // Update today's shift card
    const todayCard = $('#todayShiftCard');
    if (shouldHideTodaysShiftCard()) {
      todayCard.style.display = 'none';
    } else {
      todayCard.style.display = '';
      $('#todayShift').textContent = formatTimeRangeOrDash(state.todaySchedule);
      $('#todayBadge').style.display = state.todaySchedule ? 'none' : 'inline-block';

      // Update shift status badge
      const statusBadge = $('#shiftStatusBadge');
      const gate = canClockInNow(state.todaySchedule);

      if (state.status === 'in' || state.status === 'break') {
        statusBadge.className = 'shift-status shift-status--ontime';
        statusBadge.innerHTML = '<i class="fas fa-play-circle"></i> In Progress';
        statusBadge.style.display = 'inline-flex';
      } else if (gate.allowed && !gate.unscheduled) {
        if (gate.late) {
          statusBadge.className = 'shift-status shift-status--late';
          statusBadge.innerHTML = '<i class="fas fa-clock"></i> Late Clock-In';
          statusBadge.style.display = 'inline-flex';
        } else if (gate.onTime) {
          statusBadge.className = 'shift-status shift-status--ontime';
          statusBadge.innerHTML = '<i class="fas fa-check-circle"></i> On Time';
          statusBadge.style.display = 'inline-flex';
        }
      } else {
        statusBadge.style.display = 'none';
      }

      updateShiftProgress();
    }

    // Update next shift info
    if (state.status === 'out') {
      if (state.nextSchedule) {
        const nsStart = parseISO(parseMaybe(state.nextSchedule,'startAt',null));
        const nsEnd   = parseISO(parseMaybe(state.nextSchedule,'endAt',null));
        const dateLbl = state.nextSchedule.date || (nsStart ? df.format(nsStart) : '—');
        const timeLbl = (nsStart && nsEnd) ? `${tf.format(nsStart)} — ${tf.format(nsEnd)}` : `${state.nextSchedule.start||'—'} — ${state.nextSchedule.end||'—'}`;
        $('#nextShift').textContent = `${dateLbl}`;
        $('#nextShiftInfo').style.display = 'block';
        $('#nextShiftTime').textContent = timeLbl;
      } else {
        $('#nextShift').textContent = 'No upcoming shifts';
        $('#nextShiftInfo').style.display = 'none';
      }
    } else {
      $('#nextShift').textContent = '—';
      $('#nextShiftInfo').style.display = 'none';
    }

    // Update button states and tooltips
    const btnIn=$('#btnClockIn'), btnOut=$('#btnClockOut'), bS=$('#btnBreakStart'), bE=$('#btnBreakEnd');

    if(state.status==='out'){ 
      const allow = canClockInNow(state.todaySchedule);
      btnIn.disabled = !allow.allowed;

      if (allow.allowed) {
        if (allow.unscheduled) {
          btnIn.title = 'Clock in for unscheduled work';
          btnIn.innerHTML = '<i class="fas fa-play"></i> Clock In (Unscheduled)';
        } else if (allow.late) {
          btnIn.title = 'Clock in - you are late for your shift';
          btnIn.innerHTML = '<i class="fas fa-clock"></i> Clock In (Late)';
          btnIn.className = 'btn btn-warning';
        } else {
          btnIn.title = 'Clock in on time';
          btnIn.innerHTML = '<i class="fas fa-play"></i> Clock In';
          btnIn.className = 'btn btn-success';
        }

        // Show grace period indicator
        if (allow.withinGrace) {
          $('#graceIndicator').style.display = 'flex';
          $('#graceText').textContent = `Within ${GRACE_MINUTES}-min grace period`;
        } else {
          $('#graceIndicator').style.display = 'none';
        }
      } else {
        btnIn.title = allow.reason;
        $('#graceIndicator').style.display = 'flex';
        $('#graceText').textContent = allow.reason;

        if (allow.timeUntil) {
          const minutes = Math.ceil(allow.timeUntil / (60 * 1000));
          $('#graceText').textContent = `Opens in ${minutes} minutes`;
        }
      }

      btnOut.disabled=true; 
      bS.disabled=true; 
      bE.disabled=true;
      $('#clockHint').style.display = state.todaySchedule ? 'none' : 'block';
    }

    if(state.status==='in'){ 
      btnIn.disabled=true;
      btnIn.title = 'Already clocked in';
      btnIn.innerHTML = '<i class="fas fa-play"></i> Clock In';
      btnIn.className = 'btn btn-success';
      btnOut.disabled=false;
      btnOut.title = 'Clock out and end your shift';
      bS.disabled=false; 
      bE.disabled=true; 
      $('#clockHint').style.display='none';
      $('#graceIndicator').style.display = 'none';
    }

    if(state.status==='break'){ 
      btnIn.disabled=true; 
      btnOut.disabled=true; // Disable clock out while on break
      btnOut.title = 'End your break before clocking out';
      bS.disabled=true; 
      bE.disabled=false; 
      $('#clockHint').style.display='none';
      $('#graceIndicator').style.display = 'none';
    }

    renderToday();

    // Initialize tooltips
    $$('[data-bs-toggle="tooltip"]').forEach(el => {
      new bootstrap.Tooltip(el);
    });
  }

  function renderToday(){
    const tb=$('#todayList'); 
    tb.innerHTML='';

    if(!state.today.length){ 
      tb.innerHTML='<tr><td colspan="6" class="small text-center py-5"><i class="fas fa-inbox me-2"></i>No time entries recorded today</td></tr>'; 
      $('#todayTotalHours').textContent='0.00'; 
      return; 
    }

    let totalSec=0;
    state.today.forEach(r=>{
      const tr=document.createElement('tr');
      const status = r.late ? 'Late' : r.ontime ? 'On Time' : '—';
      const statusClass = r.late ? 'shift-status--late' : r.ontime ? 'shift-status--ontime' : '';

      tr.innerHTML=`
        <td>${r.in||'—'}</td>
        <td>${r.out||'—'}</td>
        <td>${r.break||'—'}</td>
        <td>${r.type||'Scheduled'}</td>
        <td>${r.hours||'0.00'}</td>
        <td><span class="shift-status ${statusClass}">${status}</span></td>
      `;
      tb.appendChild(tr);
      if(r.seconds) totalSec+=r.seconds;
    });

    $('#todayTotalHours').textContent=(totalSec/3600).toFixed(2);
  }

  // Helper to get current timezone name
  function getTimezoneName() {
    try {
      return Intl.DateTimeFormat().resolvedOptions().timeZone || 'UTC';
    } catch {
      return 'UTC';
    }
  }

  // Helper to get client time in ISO format (UTC)
  function getClientTimeISO() {
    return new Date().toISOString();
  }

  async function api(action, extraData={}){
    const url = `/timeclock/api?a=${action}&_=${Date.now()}`;
    
    // Prepare form data with timezone context
    const formData = new FormData();
    formData.append('tz', getTimezoneName());
    formData.append('client_time_iso', getClientTimeISO());
    
    // Add any extra data (like satisfaction)
    Object.keys(extraData).forEach(key => {
      formData.append(key, extraData[key]);
    });

    const res = await fetch(url, {
      method: 'POST',
      headers: {'Accept': 'application/json'},
      body: formData
    });
    
    if(!res.ok) {
      const text = await res.text();
      try {
        const json = JSON.parse(text);
        throw new Error(json.error || 'Request failed');
      } catch {
        throw new Error('Request failed');
      }
    }
    return res.json();
  }

  async function loadState(){
    try{
      const r = await api('status');
      
      // Map backend response to frontend state
      // Backend returns: clocked_in, on_break, entry, entries_today, today_schedule, next_schedule
      const prevStatus = state.status;
      
      if (r.clocked_in && r.on_break) {
        state.status = 'break';
        // If we just entered break state, record the start time
        if (prevStatus !== 'break') {
          state.breakStart = new Date().toISOString();
        }
      } else if (r.clocked_in) {
        state.status = 'in';
        state.breakStart = null; // Clear break start when not on break
      } else {
        state.status = 'out';
        state.breakStart = null;
      }
      
      state.clockIn = r.entry?.clock_in || null;
      state.breakSeconds = r.entry?.total_break_minutes ? r.entry.total_break_minutes * 60 : 0;
      
      // Store schedule information
      state.todaySchedule = r.today_schedule || null;
      state.nextSchedule = r.next_schedule || null;
      
      // Process today's entries for the table
      state.today = (r.entries_today || []).map(entry => {
        const clockIn = entry.clock_in ? new Date(entry.clock_in + 'Z') : null;
        const clockOut = entry.clock_out ? new Date(entry.clock_out + 'Z') : null;
        const breakMins = entry.total_break_minutes || 0;
        
        let hours = '—';
        let seconds = 0;
        if (clockIn && clockOut) {
          seconds = Math.floor((clockOut - clockIn) / 1000) - (breakMins * 60);
          hours = (seconds / 3600).toFixed(2);
        } else if (clockIn && !clockOut) {
          // Still clocked in
          const now = new Date();
          seconds = Math.floor((now - clockIn) / 1000) - (breakMins * 60);
          hours = (seconds / 3600).toFixed(2);
        }
        
        // Determine if this entry was on-time or late based on schedule
        let late = false;
        let ontime = false;
        if (state.todaySchedule && clockIn) {
          const schedStart = parseISO(parseMaybe(state.todaySchedule, 'startAt', null));
          if (schedStart) {
            const gracePeriod = ms(GRACE_MINUTES);
            const diff = clockIn - schedStart;
            if (diff > gracePeriod) {
              late = true;
            } else if (diff >= -gracePeriod && diff <= gracePeriod) {
              ontime = true;
            }
          }
        }
        
        return {
          in: clockIn ? tf.format(clockIn) : '—',
          out: clockOut ? tf.format(clockOut) : '—',
          break: breakMins > 0 ? `${breakMins} min` : '—',
          type: state.todaySchedule ? 'Scheduled' : 'Unscheduled',
          hours,
          seconds,
          satisfaction: entry.satisfaction,
          late,
          ontime
        };
      });
      
      updateUI();
    }catch(e){
      console.error('Failed to load state:', e);
      pill('','<i class="fas fa-triangle-exclamation"></i> Connection Error');
      $('#statusDetail').textContent='Could not load state. Please check your connection.';
      toast('Failed to load time clock data', 'error');
    }
  }

  async function doAction(actionName, label, extraData={}){
    setBusy(true, label+'…');
    try{
      const r = await api(actionName, extraData);
      if(r && r.ok !== false){ 
        await loadState(); 
        toast(r.message || 'Action completed successfully'); 
      }
      else{ 
        toast((r && r.error) || 'Action failed', 'error'); 
      }
    }catch(e){ 
      console.error('Action failed:', e);
      toast(e.message || 'Network error - please try again', 'error'); 
    }
    finally{ setBusy(false); }
  }

  // Event Listeners
  $('#btnClockIn').addEventListener('click', async ()=>{
    const gate = canClockInNow(state.todaySchedule);
    if (!gate.allowed && !gate.unscheduled) {
      toast(`Cannot clock in: ${gate.reason}`, 'warning');
      return;
    }
    await doAction('clock.in', 'Clocking in');
  });

  $('#btnClockOut').addEventListener('click', async ()=>{
    const modal = new bootstrap.Modal($('#satisfactionModal'));
    const submitFeedback = async (rating)=>{
      if (rating) {
        // Clock out with satisfaction rating
        await doAction('clock.out', 'Clocking out', { satisfaction: rating });
      } else {
        // Clock out without rating
        await doAction('clock.out', 'Clocking out');
      }
    };

    $('#btnSubmit').onclick = ()=> {
      const rating = $('#satisfactionSelect').value;
      if (!rating) {
        toast('Please select a satisfaction level', 'warning');
        return;
      }
      modal.hide();
      submitFeedback(rating);
    };

    $('#btnSkip').onclick = ()=> {
      modal.hide();
      submitFeedback(null);
    };

    modal.show();
  });

  $('#btnBreakStart').addEventListener('click', ()=> doAction('break.start', 'Starting break'));
  $('#btnBreakEnd').addEventListener('click', ()=> doAction('break.end', 'Ending break'));

  // Initialize and start timers
  setInterval(() => {
    const now = new Date();
    $('#tcTime').textContent = tf.format(now);
    $('#tcDate').textContent = df.format(now);
    updateLiveTimers();
    updateShiftProgress();
  }, 1000);

  // Initial load
  loadState();

  // Refresh state every 30 seconds
  setInterval(loadState, 30000);

  // Handle visibility changes
  document.addEventListener('visibilitychange', ()=>{
    if (!document.hidden) {
      loadState();
    }
  });

  // Error handling
  window.addEventListener('error', (e)=>{
    console.error('TimeClock error:', e.error);
    toast('An unexpected error occurred', 'error');
  });

  window.addEventListener('offline', ()=>{
    toast('You are offline - some features may be unavailable', 'warning');
  });

  window.addEventListener('online', ()=>{
    toast('Connection restored', 'success');
    loadState();
  });

  // Initialize Bootstrap components
  if (typeof bootstrap !== 'undefined') {
    $$('[data-bs-toggle="tooltip"]').forEach(el => {
      new bootstrap.Tooltip(el);
    });
  }
})();
</script>

<?php require 'app/views/templates/footer.php'; ?>