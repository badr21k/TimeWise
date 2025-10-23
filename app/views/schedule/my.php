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

/* Day Cards - Compact Weekly Overview */
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
@media (min-width: 768px) {
  .mobile-container {
    max-width: 768px;
    margin: 0 auto;
    padding: 1rem;
  }
  
  .quick-stats {
    grid-template-columns: repeat(4, 1fr);
  }
  
  .day-cards {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
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
  
  .day-cards {
    grid-template-columns: repeat(3, 1fr);
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
        <i class="fas fa-user"></i>
        My Shifts
      </button>
      <button class="toggle-btn" id="viewTeam">
        <i class="fas fa-users"></i>
        Team View
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

    <!-- Team View -->
    <div id="teamView" style="display: none;">
      <div class="team-section">
        <div class="section-header">
          <div class="section-title">
            <i class="fas fa-users text-accent"></i>
            Team Schedule
          </div>
        </div>
        <div class="team-members" id="teamMembers">
          <!-- Team members will be populated by JavaScript -->
          <div class="loading-shift"></div>
          <div class="loading-shift"></div>
        </div>
      </div>
    </div>
  </main>



<?php require 'app/views/templates/footer.php'; ?>

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

    // View toggles
    document.getElementById('viewMyShifts').addEventListener('click', () => this.switchView('myShifts'));
    document.getElementById('viewTeam').addEventListener('click', () => this.switchView('team'));

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
    const membersContainer = document.getElementById('teamMembers');
    const shiftsByEmployee = this.groupShiftsByEmployee();
    
    let membersHTML = '';
    
    Object.entries(shiftsByEmployee).forEach(([empId, shifts]) => {
      const employee = this.employees.find(e => e.id == empId) || { name: 'Unknown' };
      const totalHours = this.calculateTotalHours(shifts);
      const avatar = this.generateAvatar(employee.name);
      const isMe = empId == this.me.employee_id;
      
      membersHTML += `
        <div class="member-card ${isMe ? 'gradient-accent text-white' : ''}">
          <div class="member-avatar" style="${isMe ? 'background: rgba(255,255,255,0.2); color: white;' : ''}">
            ${avatar}
          </div>
          <div class="member-info">
            <div class="member-name ${isMe ? 'text-white' : ''}">${this.escapeHtml(employee.name)}${isMe ? ' (You)' : ''}</div>
            <div class="member-shifts ${isMe ? 'text-white' : 'text-neutral'}">${shifts.length} shift${shifts.length !== 1 ? 's' : ''}</div>
          </div>
          <div class="member-hours" style="${isMe ? 'background: rgba(255,255,255,0.3); color: white;' : ''}">
            ${totalHours.toFixed(1)}h
          </div>
        </div>
      `;
    });

    membersContainer.innerHTML = membersHTML || '<div class="empty-shift">No team members scheduled this week</div>';
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
    
    // Show/hide views
    document.getElementById('myShiftsView').style.display = view === 'myShifts' ? 'block' : 'none';
    document.getElementById('teamView').style.display = view === 'team' ? 'block' : 'none';
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