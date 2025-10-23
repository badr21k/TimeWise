<?php
// app/views/schedule/my.php
require 'app/views/templates/header.php';
require 'app/views/templates/spinner.php';
?>

<style>
:root {
  /* Primary Brand Colors */
  --primary: #09194D;       /* Deep Navy - Main brand color */
  --primary-light: #1A2A6C; /* Lighter Navy for gradients */
  --primary-dark: #060F2E;  /* Darker Navy for contrasts */
  
  /* Secondary Colors */
  --secondary: #D97F76;     /* Coral - For highlights and accents */
  --secondary-light: #E8A8A2; /* Light Coral */
  --secondary-dark: #C46A61;  /* Dark Coral */
  
  /* Neutral & Background Colors */
  --light: #E4E4EF;         /* Light Lavender Gray */
  --lighter: #F4F5F0;       /* Warm White */
  --neutral: #9B9498;       /* Muted Gray-Purple */
  --neutral-light: #B8B3B6; /* Light Neutral */
  --neutral-dark: #7A7478;  /* Dark Neutral */
  
  /* Accent Colors */
  --accent: #B59E5F;        /* Gold - Primary accent */
  --accent-light: #D4C191;  /* Light Gold */
  --accent-dark: #8F7D4C;   /* Dark Gold */
  
  --accent-secondary: #8D77AB; /* Muted Purple - Secondary accent */
  --accent-tertiary: #DA70D6;  /* Orchid - Tertiary accent */
  
  /* Semantic Colors */
  --success: #10b981;
  --warning: #f59e0b;
  --danger: #ef4444;
  
  /* UI Variables */
  --shadow-sm: 0 2px 4px rgba(9, 25, 77, 0.08);
  --shadow-md: 0 4px 8px rgba(9, 25, 77, 0.12);
  --shadow-lg: 0 8px 16px rgba(9, 25, 77, 0.15);
  --radius-sm: 8px;
  --radius-md: 12px;
  --radius-lg: 16px;
}

* {
  box-sizing: border-box;
}

body {
  margin: 0;
  padding: 0;
  background: linear-gradient(135deg, var(--lighter) 0%, var(--light) 100%);
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  line-height: 1.5;
  color: var(--primary);
}

.mobile-container {
  min-height: 100vh;
  padding: 0;
  max-width: 100%;
  overflow-x: hidden;
}

/* Header Section */
.app-header {
  background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
  color: white;
  padding: 1rem 1rem 0.5rem;
  position: sticky;
  top: 0;
  z-index: 100;
  box-shadow: var(--shadow-lg);
}

.header-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1rem;
}

.header-title {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.header-title h1 {
  margin: 0;
  font-size: 1.4rem;
  font-weight: 700;
  color: white;
}

.header-title .icon {
  background: rgba(255, 255, 255, 0.15);
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
  color: var(--accent-light);
}

.user-info {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background: rgba(255, 255, 255, 0.12);
  padding: 0.5rem 0.75rem;
  border-radius: var(--radius-lg);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.user-avatar {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.9rem;
  color: white;
  font-weight: 600;
}

/* Quick Stats */
.quick-stats {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0.75rem;
  margin-bottom: 1rem;
}

.stat-card {
  background: rgba(255, 255, 255, 0.95);
  border-radius: var(--radius-md);
  padding: 0.75rem;
  text-align: center;
  box-shadow: var(--shadow-sm);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.stat-value {
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--primary);
  margin-bottom: 0.25rem;
}

.stat-label {
  font-size: 0.75rem;
  color: var(--neutral);
  font-weight: 500;
}

/* View Toggle */
.view-toggle {
  display: flex;
  background: rgba(255, 255, 255, 0.15);
  border-radius: var(--radius-lg);
  padding: 0.25rem;
  margin-bottom: 1rem;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.toggle-btn {
  flex: 1;
  padding: 0.75rem 0.5rem;
  border: none;
  background: transparent;
  border-radius: var(--radius-md);
  font-weight: 600;
  font-size: 0.85rem;
  color: rgba(255, 255, 255, 0.8);
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  transition: all 0.3s ease;
  cursor: pointer;
}

.toggle-btn.active {
  background: rgba(255, 255, 255, 0.95);
  color: var(--primary);
  box-shadow: var(--shadow-sm);
}

.toggle-btn:not(.active):hover {
  background: rgba(255, 255, 255, 0.1);
  color: white;
}

/* Week Navigation */
.week-navigation {
  background: white;
  border-radius: var(--radius-lg);
  padding: 1rem;
  margin: 0 1rem 1rem;
  box-shadow: var(--shadow-md);
  display: flex;
  align-items: center;
  justify-content: space-between;
  border: 1px solid var(--light);
}

.nav-btn {
  width: 44px;
  height: 44px;
  border: none;
  border-radius: 50%;
  background: var(--light);
  color: var(--primary);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
  transition: all 0.3s ease;
  cursor: pointer;
}

.nav-btn:active {
  transform: scale(0.95);
  background: var(--primary);
  color: white;
}

.week-display {
  text-align: center;
  flex: 1;
  margin: 0 1rem;
}

.week-range {
  font-weight: 700;
  color: var(--primary);
  font-size: 1rem;
  margin-bottom: 0.25rem;
}

.week-days {
  font-size: 0.8rem;
  color: var(--neutral);
}

/* Main Content */
.main-content {
  padding: 0 1rem 2rem;
}

/* Day Cards - Compact Weekly Overview with Responsive Grid */
.day-cards {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  margin-bottom: 1rem;
}

/* Compact day card - shows entire week at a glance */
.compact-day-card {
  background: white;
  border-radius: var(--radius-md);
  padding: 0.75rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  box-shadow: var(--shadow-sm);
  border: 1px solid var(--light);
  transition: all 0.2s ease;
  min-height: 60px;
}

.compact-day-card.has-shift {
  border-left: 3px solid var(--accent);
  background: linear-gradient(135deg, #fff 0%, #fffef8 100%);
}

.compact-day-card.no-shift {
  border-left: 3px solid var(--neutral-light);
  opacity: 0.7;
}

.compact-day-card.today {
  border-color: var(--accent);
  box-shadow: 0 0 0 2px var(--accent-light);
}

.day-indicator {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-width: 50px;
  padding: 0.25rem 0.5rem;
  border-radius: var(--radius-sm);
  background: var(--lighter);
}

.day-indicator.today-indicator {
  background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%);
  color: white;
}

.day-indicator .day-name {
  font-weight: 700;
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  color: var(--primary);
}

.day-indicator.today-indicator .day-name {
  color: white;
}

.day-indicator .day-date {
  font-size: 0.7rem;
  color: var(--neutral);
  margin-top: 2px;
}

.day-indicator.today-indicator .day-date {
  color: rgba(255, 255, 255, 0.9);
}

.shift-summary {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 0.35rem;
}

/* Individual shift rows for multiple shifts per day */
.mini-shift-row {
  display: flex;
  flex-direction: column;
  gap: 0.15rem;
  padding: 0.25rem 0;
}

.mini-shift-row:not(:last-child) {
  border-bottom: 1px solid var(--light);
  padding-bottom: 0.5rem;
}

.mini-shift-time {
  font-weight: 700;
  font-size: 0.9rem;
  color: var(--primary);
  display: flex;
  align-items: center;
  gap: 0.4rem;
}

.mini-shift-time i {
  color: var(--accent);
  font-size: 0.8rem;
}

.mini-shift-role {
  font-size: 0.8rem;
  color: var(--neutral);
  padding-left: 1.5rem;
}

.shift-time-range {
  font-weight: 700;
  font-size: 0.95rem;
  color: var(--primary);
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.shift-time-range i {
  color: var(--accent);
  font-size: 0.85rem;
}

.shift-role-text {
  font-size: 0.8rem;
  color: var(--neutral);
}

.shift-duration-badge {
  background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%);
  color: white;
  padding: 0.35rem 0.75rem;
  border-radius: var(--radius-lg);
  font-weight: 700;
  font-size: 0.85rem;
  white-space: nowrap;
}

.no-shift-text {
  flex: 1;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: var(--neutral);
  font-size: 0.85rem;
  font-style: italic;
}

.no-shift-text i {
  color: var(--neutral-light);
}

/* OLD styles kept for backward compatibility */
.day-card {
  background: white;
  border-radius: var(--radius-lg);
  padding: 1rem;
  box-shadow: var(--shadow-sm);
  border-left: 4px solid var(--accent-secondary);
  transition: all 0.3s ease;
  border: 1px solid var(--light);
}

.day-card:active {
  transform: translateY(-2px);
  box-shadow: var(--shadow-md);
}

.day-card.today {
  border-left-color: var(--accent);
  background: linear-gradient(135deg, #fff 0%, var(--lighter) 100%);
  border-color: var(--accent-light);
}

.day-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.75rem;
  padding-bottom: 0.5rem;
  border-bottom: 1px solid var(--light);
}

.day-name {
  font-weight: 700;
  color: var(--primary);
  font-size: 1rem;
}

.day-date {
  color: var(--neutral);
  font-size: 0.85rem;
}

.shifts-list {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.shift-item {
  background: linear-gradient(135deg, var(--accent-secondary) 0%, #9B8BB9 100%);
  color: white;
  border-radius: var(--radius-md);
  padding: 0.75rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: var(--shadow-sm);
  border-left: 3px solid var(--accent-tertiary);
}

.shift-item.my-shift {
  background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%);
  border-left: 3px solid var(--secondary);
}

.shift-time {
  font-weight: 700;
  font-size: 1rem;
}

.shift-details {
  text-align: right;
}

.shift-role {
  font-size: 0.85rem;
  opacity: 0.95;
  margin-bottom: 0.25rem;
}

.shift-hours {
  font-size: 0.75rem;
  opacity: 0.9;
  background: rgba(255, 255, 255, 0.2);
  padding: 0.1rem 0.5rem;
  border-radius: 1rem;
  display: inline-block;
}

.empty-shift {
  text-align: center;
  color: var(--neutral);
  font-size: 0.9rem;
  padding: 1rem;
  background: var(--lighter);
  border-radius: var(--radius-md);
  border: 1px dashed var(--neutral-light);
}

/* Team View */
.team-section {
  background: white;
  border-radius: var(--radius-lg);
  padding: 1rem;
  box-shadow: var(--shadow-md);
  border: 1px solid var(--light);
}

.section-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1rem;
}

.section-title {
  font-weight: 700;
  color: var(--primary);
  font-size: 1.1rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.team-members {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.member-card {
  background: var(--lighter);
  border-radius: var(--radius-md);
  padding: 1rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  transition: all 0.3s ease;
  border: 1px solid var(--light);
}

.member-card:active {
  background: var(--light);
  transform: translateX(4px);
}

.member-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 600;
  font-size: 0.9rem;
  flex-shrink: 0;
}

.member-info {
  flex: 1;
}

.member-name {
  font-weight: 600;
  color: var(--primary);
  margin-bottom: 0.25rem;
}

.member-shifts {
  font-size: 0.8rem;
  color: var(--neutral);
}

.member-hours {
  background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%);
  color: white;
  padding: 0.25rem 0.75rem;
  border-radius: 1rem;
  font-size: 0.8rem;
  font-weight: 600;
  flex-shrink: 0;
}


/* Loading States */
.loading-shift {
  background: var(--light);
  border-radius: var(--radius-md);
  padding: 1rem;
  animation: pulse 1.5s infinite;
}

@keyframes pulse {
  0% { opacity: 1; }
  50% { opacity: 0.5; }
  100% { opacity: 1; }
}

/* Swipe Indicators */
.swipe-hint {
  text-align: center;
  color: var(--neutral);
  font-size: 0.8rem;
  margin: 1rem 0;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
}

/* Status Indicators */
.status-indicator {
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  padding: 0.25rem 0.5rem;
  border-radius: 1rem;
  font-size: 0.7rem;
  font-weight: 600;
}

.status-on-shift {
  background: var(--secondary-light);
  color: var(--primary);
}

.status-available {
  background: var(--accent-light);
  color: var(--primary);
}

/* Responsive Design */
@media (min-width: 640px) {
}

@media (min-width: 768px) {
  .mobile-container {
    max-width: 768px;
    margin: 0 auto;
    padding: 1rem;
  }
  
  .quick-stats {
    grid-template-columns: repeat(4, 1fr);
  }
  
  .team-members {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
  }
}

@media (min-width: 1024px) {
  .mobile-container {
    max-width: 1024px;
  }
  
  .team-members {
    grid-template-columns: repeat(3, 1fr);
  }
}

/* Touch Improvements */
@media (max-width: 768px) {
  .shift-item, .member-card, .nav-btn, .toggle-btn {
    -webkit-tap-highlight-color: transparent;
  }
  
  .shift-item:active, .member-card:active {
    transform: scale(0.98);
  }
}

/* Color Harmony Enhancements */
.gradient-primary {
  background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
}

.gradient-accent {
  background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%);
}

.gradient-secondary {
  background: linear-gradient(135deg, var(--accent-secondary) 0%, #9B8BB9 100%);
}

.text-primary { color: var(--primary); }
.text-neutral { color: var(--neutral); }
.text-accent { color: var(--accent); }

.bg-light { background: var(--light); }
.bg-lighter { background: var(--lighter); }

/* Focus states for accessibility */
button:focus-visible,
.nav-item:focus-visible {
  outline: 2px solid var(--accent);
  outline-offset: 2px;
}

/* Month View Styles */
.month-header {
  background: white;
  border-radius: var(--radius-lg);
  padding: 1rem;
  margin: 0 1rem 1rem;
  box-shadow: var(--shadow-md);
  display: flex;
  align-items: center;
  justify-content: space-between;
  border: 1px solid var(--light);
}

.month-display h2 {
  margin: 0;
  font-size: 1.3rem;
  font-weight: 700;
  color: var(--primary);
  text-align: center;
}

.month-calendar {
  padding: 0 1rem;
}

.calendar-grid {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  gap: 0.25rem;
  background: white;
  padding: 0.5rem;
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-md);
}

.calendar-day-header {
  text-align: center;
  font-weight: 700;
  font-size: 0.7rem;
  color: var(--neutral);
  padding: 0.5rem 0.25rem;
  text-transform: uppercase;
}

.calendar-day {
  aspect-ratio: 1;
  background: white;
  border: 1px solid var(--light);
  border-radius: var(--radius-sm);
  padding: 0.25rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-start;
  cursor: pointer;
  transition: all 0.2s ease;
  min-height: 60px;
}

.calendar-day:hover {
  background: var(--lighter);
  transform: translateY(-2px);
  box-shadow: var(--shadow-sm);
}

.calendar-day.other-month {
  opacity: 0.3;
}

.calendar-day.today {
  border: 2px solid var(--accent);
  background: linear-gradient(135deg, #fff 0%, #fffef8 100%);
}

.calendar-day.has-shift {
  background: linear-gradient(135deg, var(--accent-light) 0%, var(--accent) 100%);
  color: white;
  border-color: var(--accent);
}

.calendar-day.has-shift .day-number {
  color: white;
}

.calendar-day .day-number {
  font-weight: 700;
  font-size: 0.85rem;
  color: var(--primary);
  margin-bottom: 0.15rem;
}

.calendar-day .shift-count {
  font-size: 0.65rem;
  color: rgba(255, 255, 255, 0.95);
  background: rgba(0, 0, 0, 0.2);
  padding: 0.1rem 0.4rem;
  border-radius: 0.75rem;
  font-weight: 600;
}

.calendar-day .shift-hours {
  font-size: 0.6rem;
  color: rgba(255, 255, 255, 0.9);
  margin-top: auto;
}

/* Team By Day View */
.team-day-card {
  background: white;
  border-radius: var(--radius-lg);
  padding: 1rem;
  margin-bottom: 1rem;
  box-shadow: var(--shadow-sm);
  border: 1px solid var(--light);
}

.team-day-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.75rem;
  padding-bottom: 0.75rem;
  border-bottom: 2px solid var(--light);
}

.team-day-title {
  font-weight: 700;
  color: var(--primary);
  font-size: 1.1rem;
}

.team-day-date {
  color: var(--neutral);
  font-size: 0.85rem;
}

.team-members-list {
  display: grid;
  grid-template-columns: 1fr;
  gap: 0.5rem;
}

@media (min-width: 640px) {
  .team-members-list {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (min-width: 1024px) {
  .team-members-list {
    grid-template-columns: repeat(3, 1fr);
  }
}
</style>

<div class="mobile-container">
  <!-- Header Section -->
  <header class="app-header">
    <div class="header-content">
      <div class="header-title">
        <div class="icon">
          <i class="fas fa-user-clock"></i>
        </div>
        <h1>My Shifts</h1>
      </div>
      <div class="user-info">
        <div class="user-avatar">
          <i class="fas fa-user"></i>
        </div>
        <span id="userName">Loading...</span>
      </div>
    </div>

    <!-- Quick Stats -->
    <div class="quick-stats">
      <div class="stat-card">
        <div class="stat-value" id="statHours">0h</div>
        <div class="stat-label">This Week</div>
      </div>
      <div class="stat-card">
        <div class="stat-value" id="statShifts">0</div>
        <div class="stat-label">Shifts</div>
      </div>
      <div class="stat-card">
        <div class="stat-value" id="statDays">0</div>
        <div class="stat-label">Days</div>
      </div>
      <div class="stat-card">
        <div class="stat-value" id="statTeammates">0</div>
        <div class="stat-label">Teammates</div>
      </div>
    </div>

    <!-- View Toggle -->
    <div class="view-toggle">
      <button class="toggle-btn active" id="viewMyShifts">
        <i class="fas fa-calendar-week"></i>
        Week
      </button>
      <button class="toggle-btn" id="viewTeam">
        <i class="fas fa-users"></i>
        Team
      </button>
      <button class="toggle-btn" id="viewMonth">
        <i class="fas fa-calendar-alt"></i>
        Month
      </button>
    </div>
  </header>

  <!-- Week Navigation -->
  <div class="week-navigation">
    <button class="nav-btn" id="prevWeek">
      <i class="fas fa-chevron-left"></i>
    </button>
    <div class="week-display">
      <div class="week-range" id="weekRange">This Week</div>
      <div class="week-days" id="weekDates">Loading...</div>
    </div>
    <button class="nav-btn" id="nextWeek">
      <i class="fas fa-chevron-right"></i>
    </button>
  </div>

  <!-- Main Content -->
  <main class="main-content">
    <!-- My Shifts View -->
    <div id="myShiftsView">
      <div class="day-cards" id="dayCards">
        <!-- Days will be populated by JavaScript -->
        <div class="loading-shift"></div>
        <div class="loading-shift"></div>
        <div class="loading-shift"></div>
      </div>
    </div>

    <!-- Team View - Who's working with you each day -->
    <div id="teamView" style="display: none;">
      <div id="teamByDay">
        <!-- Days with teammates will be populated by JavaScript -->
        <div class="loading-shift"></div>
        <div class="loading-shift"></div>
      </div>
    </div>

    <!-- Month View -->
    <div id="monthView" style="display: none;">
      <div class="month-header">
        <button class="nav-btn" id="prevMonth">
          <i class="fas fa-chevron-left"></i>
        </button>
        <div class="month-display">
          <h2 id="monthTitle">Loading...</h2>
        </div>
        <button class="nav-btn" id="nextMonth">
          <i class="fas fa-chevron-right"></i>
        </button>
      </div>
      <div class="month-calendar" id="monthCalendar">
        <!-- Calendar will be populated by JavaScript -->
        <div class="loading-shift"></div>
      </div>
    </div>
  </main>



<?php ?>

<script>
// Enhanced mobile-friendly functionality with cohesive colors
class MobileShifts {
  constructor() {
    this.me = { employee_id: null, name: '', avatar: '' };
    this.employees = [];
    this.weekStart = null;
    this.currentView = 'myShifts';
    this.allWeekShifts = [];
    this.touchStartX = 0;
    this.currentMonth = new Date();
    this.monthShifts = [];
    
    this.init();
  }

  async init() {
    await this.loadUserData();
    await this.setupWeekNavigation();
    this.bindEvents();
    await this.loadWeekData();
    this.setupSwipeNavigation();
  }

  async loadUserData() {
    try {
      const who = await this.fetchJSON('/schedule/api?a=me.employee');
      this.me.employee_id = who.employee_id;
      this.me.name = who.employee_name || 'You';
      this.me.avatar = who.avatar || this.generateAvatar(this.me.name);
      
      document.getElementById('userName').textContent = this.me.name;
      document.querySelector('.user-avatar').innerHTML = this.me.avatar;
    } catch (error) {
      console.error('Failed to load user data:', error);
    }
  }

  generateAvatar(name) {
    const initials = name.split(' ').map(n => n[0]).join('').toUpperCase();
    return `<span>${initials}</span>`;
  }

  async fetchJSON(url, options = {}) {
    Spinner?.show();
    try {
      const response = await fetch(url, {
        headers: { 'Content-Type': 'application/json' },
        ...options
      });
      const text = await response.text();
      if (!response.ok) {
        console.error(`[fetchJSON] HTTP ${response.status} from ${url}:`, text.slice(0, 200));
        throw new Error(`HTTP ${response.status}: ${text}`);
      }
      try {
        return JSON.parse(text);
      } catch (parseError) {
        console.error(`[fetchJSON] JSON parse error from ${url}:`, text.slice(0, 200));
        throw new Error(`Invalid JSON response: ${parseError.message}`);
      }
    } catch (error) {
      console.error(`[fetchJSON] Request failed for ${url}:`, error.message);
      throw error;
    } finally {
      Spinner?.hide();
    }
  }

  setupWeekNavigation() {
    const today = new Date();
    this.weekStart = this.getMonday(today);
    this.updateWeekDisplay();
  }

  getMonday(date) {
    const d = new Date(date);
    const day = d.getDay();
    const diff = d.getDate() - day + (day === 0 ? -6 : 1);
    return new Date(d.setDate(diff)).toISOString().slice(0, 10);
  }

  updateWeekDisplay() {
    const start = new Date(this.weekStart);
    const end = new Date(start);
    end.setDate(end.getDate() + 6);

    const options = { month: 'short', day: 'numeric' };
    const startStr = start.toLocaleDateString('en-US', options);
    const endStr = end.toLocaleDateString('en-US', options);

    document.getElementById('weekRange').textContent = 
      this.isThisWeek(start) ? 'This Week' : 'Week View';
    document.getElementById('weekDates').textContent = `${startStr} - ${endStr}`;
  }

  isThisWeek(date) {
    const today = new Date();
    const weekStart = this.getMonday(today);
    return date.toISOString().slice(0, 10) === weekStart;
  }

  bindEvents() {
    // Week navigation
    document.getElementById('prevWeek').addEventListener('click', () => this.changeWeek(-7));
    document.getElementById('nextWeek').addEventListener('click', () => this.changeWeek(7));

    // Month navigation
    document.getElementById('prevMonth').addEventListener('click', () => this.changeMonth(-1));
    document.getElementById('nextMonth').addEventListener('click', () => this.changeMonth(1));

    // View toggles
    document.getElementById('viewMyShifts').addEventListener('click', () => this.switchView('myShifts'));
    document.getElementById('viewTeam').addEventListener('click', () => this.switchView('team'));
    document.getElementById('viewMonth').addEventListener('click', () => this.switchView('month'));

    // Touch events for better mobile experience
    this.setupTouchEvents();
  }

  setupTouchEvents() {
    const mainContent = document.querySelector('.main-content');
    
    mainContent.addEventListener('touchstart', (e) => {
      this.touchStartX = e.touches[0].clientX;
    });

    mainContent.addEventListener('touchend', (e) => {
      if (!this.touchStartX) return;

      const touchEndX = e.changedTouches[0].clientX;
      const diff = this.touchStartX - touchEndX;

      // Minimum swipe distance
      if (Math.abs(diff) > 50) {
        if (diff > 0) {
          this.changeWeek(7); // Swipe left - next week
        } else {
          this.changeWeek(-7); // Swipe right - previous week
        }
      }

      this.touchStartX = 0;
    });
  }

  setupSwipeNavigation() {
    // Add visual feedback for swipe
    const weekNav = document.querySelector('.week-navigation');
    weekNav.style.transition = 'transform 0.2s ease';
  }

  async changeWeek(days) {
    // Add visual feedback
    const weekNav = document.querySelector('.week-navigation');
    weekNav.style.transform = `translateX(${days > 0 ? 10 : -10}px)`;
    
    setTimeout(() => {
      weekNav.style.transform = 'translateX(0)';
    }, 200);

    const newDate = new Date(this.weekStart);
    newDate.setDate(newDate.getDate() + days);
    this.weekStart = this.getMonday(newDate);
    
    this.updateWeekDisplay();
    await this.loadWeekData();
  }

  async loadWeekData() {
    try {
      const data = await this.fetchJSON(`/schedule/api?a=shifts.week&week=${this.weekStart}`);
      this.allWeekShifts = data.shifts || [];
      
      const employeesData = await this.fetchJSON('/schedule/api?a=employees.list');
      this.employees = employeesData.employees || [];
      
      this.updateStats();
      this.renderMyShifts();
      this.renderTeamView();
    } catch (error) {
      console.error('Failed to load week data:', error.message || error);
      this.showError(`Failed to load schedule data: ${error.message || 'Unknown error'}`);
    }
  }

  updateStats() {
    const myShifts = this.allWeekShifts.filter(s => s.employee_id === this.me.employee_id);
    const totalHours = this.calculateTotalHours(myShifts);
    const shiftDays = new Set(myShifts.map(s => s.start_dt.slice(0, 10))).size;
    const teammates = new Set(this.allWeekShifts.map(s => s.employee_id)).size;

    document.getElementById('statHours').textContent = `${totalHours.toFixed(1)}h`;
    document.getElementById('statShifts').textContent = myShifts.length;
    document.getElementById('statDays').textContent = shiftDays;
    document.getElementById('statTeammates').textContent = teammates;
  }

  calculateTotalHours(shifts) {
    return shifts.reduce((total, shift) => {
      const start = new Date(shift.start_dt);
      const end = new Date(shift.end_dt);
      return total + (end - start) / (1000 * 60 * 60);
    }, 0);
  }

  renderMyShifts() {
    const myShifts = this.allWeekShifts.filter(s => s.employee_id === this.me.employee_id);
    const daysContainer = document.getElementById('dayCards');
    const today = new Date().toISOString().slice(0, 10);

    // Compact weekly overview - all days visible at once
    let daysHTML = '';
    for (let i = 0; i < 7; i++) {
      const date = new Date(this.weekStart);
      date.setDate(date.getDate() + i);
      const dateStr = date.toISOString().slice(0, 10);
      const dayShifts = myShifts.filter(s => s.start_dt.slice(0, 10) === dateStr);
      
      const isToday = dateStr === today;
      const dayName = date.toLocaleDateString('en-US', { weekday: 'short' });
      const dateDisplay = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
      const hasShifts = dayShifts.length > 0;

      if (hasShifts) {
        // Compact card for days with shifts - show ALL shifts
        const totalHours = dayShifts.reduce((sum, s) => sum + parseFloat(this.calculateShiftDuration(s)), 0);
        
        // Build shift items HTML
        const shiftsHTML = dayShifts.map(shift => {
          const startTime = shift.start_dt.slice(11, 16);
          const endTime = shift.end_dt.slice(11, 16);
          const role = shift.notes || shift.employee_role || 'Shift';
          return `
            <div class="mini-shift-row">
              <span class="mini-shift-time"><i class="fas fa-clock"></i> ${startTime} - ${endTime}</span>
              <span class="mini-shift-role">${this.escapeHtml(role)}</span>
            </div>
          `;
        }).join('');

        daysHTML += `
          <div class="compact-day-card has-shift ${isToday ? 'today' : ''}">
            <div class="day-indicator ${isToday ? 'today-indicator' : ''}">
              <div class="day-name">${dayName}</div>
              <div class="day-date">${dateDisplay}</div>
            </div>
            <div class="shift-summary">
              ${shiftsHTML}
            </div>
            <div class="shift-duration-badge">${totalHours.toFixed(1)}h</div>
          </div>
        `;
      } else {
        // Minimal card for days without shifts
        daysHTML += `
          <div class="compact-day-card no-shift ${isToday ? 'today' : ''}">
            <div class="day-indicator ${isToday ? 'today-indicator' : ''}">
              <div class="day-name">${dayName}</div>
              <div class="day-date">${dateDisplay}</div>
            </div>
            <div class="no-shift-text">
              <i class="fas fa-moon"></i> Day off
            </div>
          </div>
        `;
      }
    }

    daysContainer.innerHTML = daysHTML;
  }

  renderShiftItem(shift) {
    const startTime = shift.start_dt.slice(11, 16);
    const endTime = shift.end_dt.slice(11, 16);
    const role = shift.notes || shift.employee_role || '';
    const duration = this.calculateShiftDuration(shift);

    return `
      <div class="shift-item my-shift">
        <div class="shift-time">${startTime} - ${endTime}</div>
        <div class="shift-details">
          <div class="shift-role">${this.escapeHtml(role)}</div>
          <div class="shift-hours">${duration}h</div>
        </div>
      </div>
    `;
  }

  calculateShiftDuration(shift) {
    const start = new Date(shift.start_dt);
    const end = new Date(shift.end_dt);
    return ((end - start) / (1000 * 60 * 60)).toFixed(1);
  }

  renderTeamView() {
    // Group shifts by day and show who's working with the user each day
    const container = document.getElementById('teamByDay');
    const myShifts = this.allWeekShifts.filter(s => s.employee_id === this.me.employee_id);
    const today = new Date().toISOString().slice(0, 10);
    
    let teamHTML = '';
    
    // For each day of the week
    for (let i = 0; i < 7; i++) {
      const date = new Date(this.weekStart);
      date.setDate(date.getDate() + i);
      const dateStr = date.toISOString().slice(0, 10);
      
      // Check if user has shifts this day
      const myDayShifts = myShifts.filter(s => s.start_dt.slice(0, 10) === dateStr);
      
      if (myDayShifts.length > 0) {
        // Get all shifts for this day
        const dayShifts = this.allWeekShifts.filter(s => s.start_dt.slice(0, 10) === dateStr);
        const teammates = dayShifts.filter(s => s.employee_id !== this.me.employee_id);
        
        const dayName = date.toLocaleDateString('en-US', { weekday: 'long' });
        const dateDisplay = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        const isToday = dateStr === today;
        
        teamHTML += `
          <div class="team-day-card ${isToday ? 'today' : ''}">
            <div class="team-day-header">
              <div class="team-day-title">${dayName}</div>
              <div class="team-day-date">${dateDisplay}</div>
            </div>
            <div class="team-members-list">
        `;
        
        if (teammates.length > 0) {
          teammates.forEach(shift => {
            const employee = this.employees.find(e => e.id == shift.employee_id) || { name: 'Unknown' };
            const avatar = this.generateAvatar(employee.name);
            const startTime = shift.start_dt.slice(11, 16);
            const endTime = shift.end_dt.slice(11, 16);
            const duration = this.calculateShiftDuration(shift);
            const role = shift.notes || shift.employee_role || 'Shift';
            
            teamHTML += `
              <div class="member-card">
                <div class="member-avatar">${avatar}</div>
                <div class="member-info">
                  <div class="member-name">${this.escapeHtml(employee.name)}</div>
                  <div class="member-shifts">${startTime} - ${endTime}</div>
                  <div class="member-shifts text-neutral" style="font-size: 0.75rem;">${this.escapeHtml(role)}</div>
                </div>
                <div class="member-hours">${duration}h</div>
              </div>
            `;
          });
        } else {
          teamHTML += `<div class="empty-shift">Working alone this day</div>`;
        }
        
        teamHTML += `
            </div>
          </div>
        `;
      }
    }
    
    container.innerHTML = teamHTML || '<div class="empty-shift" style="padding: 2rem; text-align: center;">No shifts scheduled this week</div>';
  }

  groupShiftsByEmployee() {
    const groups = {};
    this.allWeekShifts.forEach(shift => {
      if (!groups[shift.employee_id]) {
        groups[shift.employee_id] = [];
      }
      groups[shift.employee_id].push(shift);
    });
    return groups;
  }

  switchView(view) {
    this.currentView = view;
    
    // Update toggle buttons
    document.getElementById('viewMyShifts').classList.toggle('active', view === 'myShifts');
    document.getElementById('viewTeam').classList.toggle('active', view === 'team');
    document.getElementById('viewMonth').classList.toggle('active', view === 'month');
    
    // Show/hide views
    document.getElementById('myShiftsView').style.display = view === 'myShifts' ? 'block' : 'none';
    document.getElementById('teamView').style.display = view === 'team' ? 'block' : 'none';
    document.getElementById('monthView').style.display = view === 'month' ? 'block' : 'none';
    
    // Show/hide navigation
    document.querySelector('.week-navigation').style.display = (view === 'myShifts' || view === 'team') ? 'flex' : 'none';
    
    // Load month view if switching to it
    if (view === 'month') {
      this.loadMonthData();
    }
  }

  async changeMonth(delta) {
    this.currentMonth = new Date(this.currentMonth.getFullYear(), this.currentMonth.getMonth() + delta, 1);
    await this.loadMonthData();
  }

  async loadMonthData() {
    const year = this.currentMonth.getFullYear();
    const month = this.currentMonth.getMonth() + 1;
    const firstDay = `${year}-${String(month).padStart(2, '0')}-01`;
    const lastDay = new Date(year, month, 0);
    const lastDayStr = `${year}-${String(month).padStart(2, '0')}-${String(lastDay.getDate()).padStart(2, '0')}`;
    
    try {
      // Get all shifts for the month
      const promises = [];
      let currentWeek = this.getMonday(firstDay);
      const endDate = new Date(lastDayStr);
      
      while (new Date(currentWeek) <= endDate) {
        promises.push(this.fetchJSON(`/schedule/api?a=shifts.week&week=${currentWeek}`));
        const nextWeek = new Date(currentWeek);
        nextWeek.setDate(nextWeek.getDate() + 7);
        currentWeek = nextWeek.toISOString().slice(0, 10);
      }
      
      const results = await Promise.all(promises);
      this.monthShifts = results.flatMap(r => r.shifts || []).filter(s => 
        s.employee_id === this.me.employee_id
      );
      
      this.renderMonthView();
    } catch (error) {
      console.error('Failed to load month data:', error);
    }
  }

  renderMonthView() {
    // Update month title
    const monthName = this.currentMonth.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
    document.getElementById('monthTitle').textContent = monthName;
    
    // Get calendar days
    const year = this.currentMonth.getFullYear();
    const month = this.currentMonth.getMonth();
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const startDay = firstDay.getDay(); // 0 = Sunday
    const daysInMonth = lastDay.getDate();
    const today = new Date().toISOString().slice(0, 10);
    
    // Build calendar grid
    let calendarHTML = '<div class="calendar-grid">';
    
    // Day headers
    const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    dayNames.forEach(day => {
      calendarHTML += `<div class="calendar-day-header">${day}</div>`;
    });
    
    // Empty cells for days before month starts
    for (let i = 0; i < startDay; i++) {
      calendarHTML += '<div class="calendar-day other-month"></div>';
    }
    
    // Days of the month
    for (let day = 1; day <= daysInMonth; day++) {
      const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
      const dayShifts = this.monthShifts.filter(s => s.start_dt.slice(0, 10) === dateStr);
      const isToday = dateStr === today;
      const hasShift = dayShifts.length > 0;
      
      let dayClasses = 'calendar-day';
      if (isToday) dayClasses += ' today';
      if (hasShift) dayClasses += ' has-shift';
      
      let dayContent = `<div class="day-number">${day}</div>`;
      
      if (hasShift) {
        const totalHours = dayShifts.reduce((sum, s) => {
          const start = new Date(s.start_dt);
          const end = new Date(s.end_dt);
          return sum + (end - start) / (1000 * 60 * 60);
        }, 0);
        
        dayContent += `<div class="shift-count">${dayShifts.length} shift${dayShifts.length > 1 ? 's' : ''}</div>`;
        dayContent += `<div class="shift-hours">${totalHours.toFixed(1)}h</div>`;
      }
      
      calendarHTML += `<div class="${dayClasses}">${dayContent}</div>`;
    }
    
    // Fill remaining cells
    const totalCells = startDay + daysInMonth;
    const remainingCells = totalCells % 7 === 0 ? 0 : 7 - (totalCells % 7);
    for (let i = 0; i < remainingCells; i++) {
      calendarHTML += '<div class="calendar-day other-month"></div>';
    }
    
    calendarHTML += '</div>';
    
    document.getElementById('monthCalendar').innerHTML = calendarHTML;
  }

  showError(message) {
    // Simple error display - you might want to use a toast notification
    alert(message);
  }

  escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }
}

// Initialize the app when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
  new MobileShifts();
});
</script>