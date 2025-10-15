      <?php
      // Always start the session before touching $_SESSION
      if (session_status() !== PHP_SESSION_ACTIVE) session_start();

      // Current path
      $__path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

      // Public pages that should NOT force login
      $__PUBLIC = [
        '/', '/login', '/register', '/forgot', '/password/reset', '/password/forgot'
      ];

      // Gate only non-public pages
      if (!isset($_SESSION['auth']) && !in_array($__path, $__PUBLIC, true)) {
          header('Location: /login');
          exit;
      }
      ?>

      <!DOCTYPE html>
      <html lang="en">
      <head>
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
          <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
          <link rel="icon" href="/favicon.png">
          <title>TimeWise</title>
          <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1">
          <meta name="apple-mobile-web-app-capable" content="yes">
          <meta name="mobile-web-app-capable" content="yes">
          <?php
            // Load page-specific stylesheet for Time Clock
            $___path_for_css = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
            if (strpos($___path_for_css, '/timeclock') === 0) {
              echo "\n    <link href=\"/assets/css/timeclock.css\" rel=\"stylesheet\">\n";
            }
          ?>
          <style>
            :root {
              --primary: #09194D;
              --primary-light: #1A2A6C;
              --primary-dark: #060F2E;
              --secondary: #D97F76;
              --secondary-light: #E8A8A2;
              --secondary-dark: #C46A61;
              --light: #E4E4EF;
              --lighter: #F4F5F0;
              --neutral: #9B9498;
              --neutral-light: #B8B3B6;
              --neutral-dark: #7A7478;
              --accent: #B59E5F;
              --accent-light: #D4C191;
              --accent-dark: #8F7D4C;
              --accent-secondary: #8D77AB;
              --accent-tertiary: #DA70D6;

              --shadow-sm: 0 2px 8px rgba(9, 25, 77, 0.08);
              --shadow-md: 0 4px 16px rgba(9, 25, 77, 0.12);
              --shadow-lg: 0 8px 32px rgba(9, 25, 77, 0.15);
              --radius-sm: 8px;
              --radius-md: 12px;
              --radius-lg: 16px;
            }

            /* Enhanced Navbar Styling */
            .navbar {
              background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%) !important;
              box-shadow: var(--shadow-lg);
              padding: 0.5rem 0;
              transition: all 0.3s ease;
              z-index: 1050;
              position: relative;
              backdrop-filter: blur(10px);
            }

            .navbar.scrolled {
              box-shadow: var(--shadow-md);
              padding: 0.3rem 0;
            }

            /* Enhanced Brand Styling */
            .navbar-brand {
              font-size: 1.8rem;
              font-weight: 800;
              color: white !important;
              display: flex;
              align-items: center;
              transition: all 0.3s ease;
              text-decoration: none;
              padding: 0.5rem 0;
            }

            .brand-logo {
              background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
              -webkit-background-clip: text;
              -webkit-text-fill-color: transparent;
              font-weight: 800;
              margin-right: 0.5rem;
              transition: all 0.3s ease;
            }

            .navbar-brand:hover { 
              transform: translateY(-2px); 
            }
            .navbar-brand:hover .brand-logo {
              transform: scale(1.1);
            }

            /* Centered Navigation */
            .navbar-center {
              position: absolute;
              left: 50%;
              transform: translateX(-50%);
            }

            /* Toast Container */
            .toast-container {
              position: fixed;
              top: 20px;
              right: 20px;
              z-index: 1055;
            }

            /* Enhanced Nav Links */
            .navbar-nav .nav-link {
              font-weight: 600;
              padding: 0.75rem 1.25rem !important;
              transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
              color: rgba(255, 255, 255, 0.9) !important;
              position: relative;
              border-radius: var(--radius-md);
              margin: 0 0.2rem;
              backdrop-filter: blur(5px);
            }

            .navbar-nav .nav-link:hover {
              color: white !important;
              background: rgba(255, 255, 255, 0.15);
              transform: translateY(-2px);
              box-shadow: var(--shadow-sm);
            }

            .navbar-nav .nav-link.active {
              background: rgba(255, 255, 255, 0.2);
              color: white !important;
              font-weight: 700;
            }

            .navbar-nav .nav-link::before {
              content: '';
              position: absolute;
              bottom: 0;
              left: 50%;
              width: 0;
              height: 3px;
              background: linear-gradient(90deg, var(--accent), var(--accent-light));
              transition: all 0.3s ease;
              transform: translateX(-50%);
              border-radius: 3px;
            }

            .navbar-nav .nav-link:hover::before,
            .navbar-nav .nav-link.active::before { 
              width: 80%; 
            }

            /* Enhanced Dropdown Styling */
            .navbar-nav .dropdown-menu {
              background: linear-gradient(135deg, var(--lighter) 0%, white 100%);
              border: none;
              box-shadow: var(--shadow-lg);
              border-radius: var(--radius-lg);
              padding: 0.5rem 0;
              margin-top: 0.5rem;
              min-width: 240px;
              opacity: 0;
              transform: translateY(10px) scale(0.95);
              visibility: hidden;
              transition: all 0.25s cubic-bezier(0.25, 0.8, 0.25, 1);
              z-index: 9999;
              position: absolute;
              overflow: hidden;
              border: 1px solid var(--light);
            }

            .navbar-nav .dropdown-menu::before {
              content: '';
              position: absolute;
              top: -8px;
              left: 20px;
              width: 16px;
              height: 16px;
              background: white;
              transform: rotate(45deg);
              border-radius: 4px;
              border-left: 1px solid var(--light);
              border-top: 1px solid var(--light);
            }

            .navbar-nav .dropdown-menu.show {
              opacity: 1;
              transform: translateY(0) scale(1);
              visibility: visible;
            }

            /* Enhanced Dropdown Items */
            .navbar-nav .dropdown-item {
              color: var(--primary);
              padding: 0.75rem 1.5rem;
              font-weight: 500;
              transition: all 0.2s ease;
              background: transparent;
              border: none;
              position: relative;
              display: flex;
              align-items: center;
              margin: 0.1rem 0.5rem;
              border-radius: var(--radius-sm);
            }

            .navbar-nav .dropdown-item:hover,
            .navbar-nav .dropdown-item:focus,
            .navbar-nav .dropdown-item.active {
              background: linear-gradient(135deg, var(--light) 0%, var(--lighter) 100%);
              color: var(--primary);
              padding-left: 1.8rem;
              transform: translateX(4px);
            }

            .navbar-nav .dropdown-item i {
              width: 20px;
              text-align: center;
              margin-right: 0.75rem;
              color: var(--accent);
              transition: all 0.2s ease;
            }

            .navbar-nav .dropdown-item:hover i {
              transform: scale(1.1);
              color: var(--accent-secondary);
            }

            /* Enhanced Headers & Dividers */
            .navbar-nav .dropdown-header {
              color: var(--primary);
              font-size: 0.8rem;
              font-weight: 700;
              padding: 0.75rem 1.5rem 0.5rem;
              margin-bottom: 0.3rem;
              text-transform: uppercase;
              letter-spacing: 0.5px;
              border-bottom: 1px solid var(--light);
            }

            .navbar-nav .dropdown-divider { 
              border-color: var(--light); 
              margin: 0.5rem 1rem; 
            }

            /* Enhanced Profile Section */
            .profile-dropdown .dropdown-toggle {
              border-radius: 50px;
              padding: 0.5rem 1rem;
              transition: all 0.3s ease;
              border: 1px solid rgba(255, 255, 255, 0.2);
              background: rgba(255, 255, 255, 0.1);
              backdrop-filter: blur(10px);
            }

            .profile-dropdown .dropdown-toggle:hover {
              background: rgba(255, 255, 255, 0.2);
              border-color: rgba(255, 255, 255, 0.3);
              transform: translateY(-1px);
            }

            .user-icon {
              background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
              color: white;
              width: 36px;
              height: 36px;
              border-radius: 50%;
              display: flex;
              align-items: center;
              justify-content: center;
              margin-right: 0.75rem;
              font-size: 1rem;
              box-shadow: var(--shadow-sm);
              transition: all 0.3s ease;
            }

            .profile-dropdown .dropdown-toggle:hover .user-icon {
              transform: scale(1.05);
            }

            /* Enhanced Admin Badge */
            .admin-badge {
              background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-light) 100%);
              color: white;
              font-size: 0.7rem;
              padding: 0.3rem 0.7rem;
              border-radius: 50px;
              margin-left: 0.5rem;
              box-shadow: var(--shadow-sm);
              animation: pulse 2s infinite;
              font-weight: 600;
            }

            @keyframes pulse { 
              0% { transform: scale(1); }
              50% { transform: scale(1.05); }
              100% { transform: scale(1); }
            }

            /* Enhanced Toggler */
            .navbar-toggler { 
              border: 1px solid rgba(255, 255, 255, 0.3); 
              padding: 0.5rem 0.6rem; 
              transition: all 0.3s ease; 
              background: rgba(255, 255, 255, 0.1);
              backdrop-filter: blur(10px);
            }

            .navbar-toggler:focus { 
              box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.3); 
            }

            .navbar-toggler-icon {
              background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='30' height='30' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 0.9)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
              transition: all 0.3s ease;
            }

            /* Mobile Dropdown Enhancements */
            @media (max-width: 991.98px) {
              .navbar-nav { 
                padding-top: 1rem; 
                background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
                border-radius: var(--radius-lg);
                margin: 1rem 0;
                padding: 1rem;
              }

              .navbar-nav .nav-link {
                padding: 0.8rem 1rem !important;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                margin: 0.2rem 0;
                color: rgba(255, 255, 255, 0.9) !important;
              }

              .navbar-nav .nav-link:hover {
                background: rgba(255, 255, 255, 0.15);
                color: white !important;
              }

              /* Mobile Dropdown Styling */
              .navbar-nav .dropdown-menu {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(20px);
                box-shadow: var(--shadow-md);
                margin: 0.5rem 0 0.5rem 1rem;
                border-radius: var(--radius-md);
                border: 1px solid rgba(255, 255, 255, 0.2);
                position: static !important;
                transform: none !important;
                opacity: 1 !important;
                visibility: visible !important;
                display: none;
              }

              .navbar-nav .dropdown-menu.show {
                display: block;
                animation: slideDown 0.3s ease;
              }

              .navbar-nav .dropdown-menu::before {
                display: none;
              }

              .navbar-nav .dropdown-item {
                color: var(--primary);
                padding: 0.7rem 1rem;
                margin: 0.1rem 0.25rem;
              }

              .navbar-nav .dropdown-item:hover {
                background: var(--light);
                transform: translateX(4px);
              }

              @keyframes slideDown {
                from {
                  opacity: 0;
                  transform: translateY(-10px);
                }
                to {
                  opacity: 1;
                  transform: translateY(0);
                }
              }

              /* Close button for mobile menu */
              .mobile-menu-close {
                position: absolute;
                top: 1rem;
                right: 1rem;
                background: rgba(255, 255, 255, 0.2);
                border: none;
                color: white;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.2rem;
                z-index: 1000;
              }
            }

            /* Desktop specific enhancements */
            @media (min-width: 992px) {
              .navbar-nav .dropdown:hover .dropdown-menu {
                opacity: 1;
                transform: translateY(0) scale(1);
                visibility: visible;
              }

              .mobile-menu-close {
                display: none;
              }
            }

            /* Enhanced scroll effect */
            .navbar.scrolled {
              background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%) !important;
            }
          </style>
      </head>

      <body>
          <!-- Toast Container -->
          <div class="toast-container" id="toastContainer"></div>

          <?php
            $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
            $isSched = (bool)preg_match('#^/schedule\b(?!/my)#', $path); // schedule index but not /schedule/my
            $isMy    = (bool)preg_match('#^/schedule/my\b#', $path);
            $isDept  = (bool)preg_match('#^/departments\b#', $path);
            $isTimeClock = (bool)preg_match('#^/timeclock\b#', $path);
            $isTeamActive = ($isSched || $isDept || $isMy || $isTimeClock);
          ?>

          <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
            <div class="container">
              <a class="navbar-brand" href="/home">
                <span class="brand-logo">
                  <i class="fas fa-clock"></i>
                </span>
                TimeWise
              </a>

              <!-- Toggler -->
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>

              <!-- Center Navigation (desktop) -->
              <div class="navbar-center d-none d-lg-flex">
                <ul class="navbar-nav">
                  <li class="nav-item">
                    <a class="nav-link <?= preg_match('#^/home\b#', $path) ? 'active' : '' ?>" href="/home">
                      <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                    </a>
                  </li>
                  <?php if (class_exists('AccessControl') && AccessControl::hasAccess('chat', 'navigation')): ?>
                  <li class="nav-item">
                    <a class="nav-link <?= preg_match('#^/chat\b#', $path) ? 'active' : '' ?>" href="/chat">
                      <i class="fas fa-comments me-1"></i>Chat
                    </a>
                  </li>
                  <?php endif; ?>

                  <?php
                    $showSchedule    = class_exists('AccessControl') && AccessControl::hasAccess('schedule', 'navigation');
                    $showMyShifts    = class_exists('AccessControl') && AccessControl::hasAccess('my_shifts', 'navigation');
                    $showDepartments = class_exists('AccessControl') && AccessControl::hasAccess('departments_roles', 'navigation');
                    $showTeamRoster  = class_exists('AccessControl') && AccessControl::hasAccess('team_roster', 'navigation');
                    $showTimeClock   = class_exists('AccessControl') && AccessControl::hasAccess('time_clock', 'navigation');
                    $showTeamMenu    = $showSchedule || $showMyShifts || $showDepartments || $showTeamRoster || $showTimeClock;
                  ?>
                  <?php if ($showTeamMenu): ?>
                  <!-- Team & Schedule (desktop dropdown) -->
                  <li class="nav-item dropdown <?= $isTeamActive ? 'active' : '' ?>">
                    <a class="nav-link dropdown-toggle <?= $isTeamActive ? 'active' : '' ?>"
                       href="#" id="teamSchedDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="fas fa-users me-1"></i> Team &amp; Schedule
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="teamSchedDropdown">
                      <?php if ($showTimeClock): ?>
                      <li>
                        <a class="dropdown-item <?= preg_match('#^/timeclock\b#', $path) ? 'active' : '' ?>" href="/timeclock">
                          <i class="fas fa-user-check me-2"></i> Time Clock
                        </a>
                      </li>
                      <?php endif; ?>
                      <?php if ($showSchedule): ?>
                      <li>
                        <a class="dropdown-item <?= $isSched ? 'active' : '' ?>" href="/schedule">
                          <i class="fas fa-calendar-alt me-2"></i> Schedule
                        </a>
                      </li>
                      <?php endif; ?>
                      <?php if ($showMyShifts): ?>
                      <li>
                        <a class="dropdown-item <?= $isMy ? 'active' : '' ?>" href="/schedule/my">
                          <i class="fas fa-user-clock me-2"></i> My Shifts
                        </a>
                      </li>
                      <?php endif; ?>
                      <?php if ($showDepartments): ?>
                      <li>
                        <a class="dropdown-item <?= $isDept ? 'active' : '' ?>" href="/departments">
                          <i class="fas fa-sitemap me-2"></i> Departments &amp; Roles
                        </a>
                      </li>
                      <?php endif; ?>
                      <?php if ($showTeamRoster): ?>
                      <li>
                        <a class="dropdown-item <?= preg_match('#^/team\\b#', $path) ? 'active' : '' ?>" href="/team">
                          <i class="fas fa-users me-2"></i> Team roster
                        </a>
                      </li>
                      <?php endif; ?>
                    </ul>
                  </li>
                  <?php endif; ?>

                  <?php if (class_exists('AccessControl') && AccessControl::hasAccess('reminders', 'navigation')): ?>
                  <!-- Reminders (desktop dropdown) -->
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= preg_match('#^/notes\\b#', $path) ? 'active' : '' ?>"
                       href="#" id="remindersDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="fas fa-tasks me-1"></i>Reminders
                    </a>
                    <ul class="dropdown-menu shadow">
                      <li><h6 class="dropdown-header">
                        <i class="fas fa-list me-1"></i>Manage Reminders
                      </h6></li>
                      <li><a class="dropdown-item <?= $path === '/notes' ? 'active' : '' ?>" href="/notes">
                        <i class="fas fa-eye me-2"></i>View All
                      </a></li>
                      <li><a class="dropdown-item <?= $path === '/notes/create' ? 'active' : '' ?>" href="/notes/create">
                        <i class="fas fa-plus me-2"></i>Create New
                      </a></li>
                      <li><hr class="dropdown-divider"></li>
                      <li><h6 class="dropdown-header">
                        <i class="fas fa-filter me-1"></i>Filter By Status
                      </h6></li>
                      <li><a class="dropdown-item" href="/notes?filter=pending">
                        <i class="fas fa-clock me-2" style="color: var(--accent);"></i>Pending
                      </a></li>
                      <li><a class="dropdown-item" href="/notes?filter=completed">
                        <i class="fas fa-check me-2" style="color: var(--accent-secondary);"></i>Completed
                      </a></li>
                    </ul>
                  </li>
                  <?php endif; ?>

                  <?php if (class_exists('AccessControl') && AccessControl::hasAccess('reports', 'navigation')): ?>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= preg_match('#^/reports\\b#', $path) ? 'active' : '' ?>"
                       href="#" id="adminDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="fas fa-chart-bar me-1"></i>Reports
                    </a>
                    <ul class="dropdown-menu shadow">
                      <li><h6 class="dropdown-header">
                        <i class="fas fa-chart-line me-1"></i>Admin Reports
                      </h6></li>
                      <li><a class="dropdown-item <?= $path === '/reports' ? 'active' : '' ?>" href="/reports">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                      </a></li>
                      <li><a class="dropdown-item <?= $path === '/reports/allReminders' ? 'active' : '' ?>" href="/reports/allReminders">
                        <i class="fas fa-list me-2"></i>All Reminders
                      </a></li>
                      <li><a class="dropdown-item <?= $path === '/reports/userStats' ? 'active' : '' ?>" href="/reports/userStats">
                        <i class="fas fa-users me-2"></i>User Statistics
                      </a></li>
                      <li><a class="dropdown-item <?= $path === '/reports/loginReport' ? 'active' : '' ?>" href="/reports/loginReport">
                        <i class="fas fa-sign-in-alt me-2"></i>Login Report
                      </a></li>
                      <li><a class="dropdown-item <?= $path === '/reports/hours' ? 'active' : '' ?>" href="/reports/hours">
                        <i class="fas fa-hourglass-half me-2"></i>Weekly Hours
                      </a></li>
                    </ul>
                  </li>
                  <?php endif; ?>
                </ul>
              </div>

              <!-- Right / Mobile -->
              <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Close button for mobile menu -->
                <button class="mobile-menu-close d-lg-none" type="button" aria-label="Close menu">
                  <i class="fas fa-times"></i>
                </button>

                <!-- Mobile Nav (left) -->
                <ul class="navbar-nav me-auto d-lg-none">
                  <li class="nav-item">
                    <a class="nav-link <?= preg_match('#^/home\b#', $path) ? 'active' : '' ?>" href="/home">
                      <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                    </a>
                  </li>
                  <?php if (class_exists('AccessControl') && AccessControl::hasAccess('chat', 'navigation')): ?>
                  <li class="nav-item">
                    <a class="nav-link <?= preg_match('#^/chat\\b#', $path) ? 'active' : '' ?>" href="/chat">
                      <i class="fas fa-comments me-1"></i>Chat
                    </a>
                  </li>
                  <?php endif; ?>

                  <!-- Team & Schedule (mobile dropdowns) -->
                  <?php if ($showTeamMenu): ?>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= $isTeamActive ? 'active' : '' ?>"
                       href="#" id="mobileTeamDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="fas fa-users me-1"></i>Team & Schedule
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="mobileTeamDropdown">
                      <?php if ($showTimeClock): ?>
                      <li>
                        <a class="dropdown-item <?= preg_match('#^/timeclock\\b#', $path) ? 'active' : '' ?>" href="/timeclock">
                          <i class="fas fa-user-check me-2"></i>Time Clock
                        </a>
                      </li>
                      <?php endif; ?>
                      <?php if ($showSchedule): ?>
                      <li>
                        <a class="dropdown-item <?= $isSched ? 'active' : '' ?>" href="/schedule">
                          <i class="fas fa-calendar-alt me-2"></i>Schedule
                        </a>
                      </li>
                      <?php endif; ?>
                      <?php if ($showMyShifts): ?>
                      <li>
                        <a class="dropdown-item <?= $isMy ? 'active' : '' ?>" href="/schedule/my">
                          <i class="fas fa-user-clock me-2"></i>My Shifts
                        </a>
                      </li>
                      <?php endif; ?>
                      <?php if ($showDepartments): ?>
                      <li>
                        <a class="dropdown-item <?= $isDept ? 'active' : '' ?>" href="/departments">
                          <i class="fas fa-sitemap me-2"></i>Departments & Roles
                        </a>
                      </li>
                      <?php endif; ?>
                      <?php if ($showTeamRoster): ?>
                      <li>
                        <a class="dropdown-item <?= preg_match('#^/team\\b#', $path) ? 'active' : '' ?>" href="/team">
                          <i class="fas fa-users me-2"></i>Team Roster
                        </a>
                      </li>
                      <?php endif; ?>
                    </ul>
                  </li>
                  <?php endif; ?>

                  <?php if (class_exists('AccessControl') && AccessControl::hasAccess('reminders', 'navigation')): ?>
                  <!-- Reminders mobile dropdown -->
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= preg_match('#^/notes\\b#', $path) ? 'active' : '' ?>"
                       href="#" id="mobileRemindersDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="fas fa-tasks me-1"></i>Reminders
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="mobileRemindersDropdown">
                      <li><h6 class="dropdown-header">
                        <i class="fas fa-list me-1"></i>Manage Reminders
                      </h6></li>
                      <li><a class="dropdown-item <?= $path === '/notes' ? 'active' : '' ?>" href="/notes">
                        <i class="fas fa-eye me-2"></i>View All
                      </a></li>
                      <li><a class="dropdown-item <?= $path === '/notes/create' ? 'active' : '' ?>" href="/notes/create">
                        <i class="fas fa-plus me-2"></i>Create New
                      </a></li>
                      <li><hr class="dropdown-divider"></li>
                      <li><h6 class="dropdown-header">
                        <i class="fas fa-filter me-1"></i>Filter By Status
                      </h6></li>
                      <li><a class="dropdown-item" href="/notes?filter=pending">
                        <i class="fas fa-clock me-2" style="color: var(--accent);"></i>Pending
                      </a></li>
                      <li><a class="dropdown-item" href="/notes?filter=completed">
                        <i class="fas fa-check me-2" style="color: var(--accent-secondary);"></i>Completed
                      </a></li>
                    </ul>
                  </li>
                  <?php endif; ?>

                  <?php if (class_exists('AccessControl') && AccessControl::hasAccess('reports', 'navigation')): ?>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= preg_match('#^/reports\\b#', $path) ? 'active' : '' ?>"
                       href="#" id="mobileAdminDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="fas fa-chart-bar me-1"></i>Admin Reports
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="mobileAdminDropdown">
                      <li><h6 class="dropdown-header">
                        <i class="fas fa-chart-line me-1"></i>Admin Reports
                      </h6></li>
                      <li><a class="dropdown-item <?= $path === '/reports' ? 'active' : '' ?>" href="/reports">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                      </a></li>
                      <li><a class="dropdown-item <?= $path === '/reports/allReminders' ? 'active' : '' ?>" href="/reports/allReminders">
                        <i class="fas fa-list me-2"></i>All Reminders
                      </a></li>
                      <li><a class="dropdown-item <?= $path === '/reports/userStats' ? 'active' : '' ?>" href="/reports/userStats">
                        <i class="fas fa-users me-2"></i>User Statistics
                      </a></li>
                      <li><a class="dropdown-item <?= $path === '/reports/loginReport' ? 'active' : '' ?>" href="/reports/loginReport">
                        <i class="fas fa-sign-in-alt me-2"></i>Login Report
                      </a></li>
                      <li><a class="dropdown-item <?= $path === '/reports/hours' ? 'active' : '' ?>" href="/reports/hours">
                        <i class="fas fa-hourglass-half me-2"></i>Weekly Hours
                      </a></li>
                    </ul>
                  </li>
                  <?php endif; ?>
                </ul>

                <!-- Profile (right) -->
                <ul class="navbar-nav ms-auto">
                  <li class="nav-item dropdown profile-dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                      <div class="d-flex align-items-center">
                        <div class="user-icon">
                          <i class="fas fa-user"></i>
                        </div>
                        <span class="d-none d-md-inline text-white"><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></span>
                        <?php if (isset($_SESSION['is_admin']) && (int)$_SESSION['is_admin'] === 1): ?>
                          <span class="admin-badge">Admin</span>
                        <?php endif; ?>
                        <i class="fas fa-chevron-down ms-2 small text-white"></i>
                      </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="profileDropdown">
                      <li><h6 class="dropdown-header">
                        <i class="fas fa-user me-1"></i>Account
                      </h6></li>
                      <li><a class="dropdown-item <?= $path === '/profile' ? 'active' : '' ?>" href="/profile">
                        <i class="fas fa-user-circle me-2"></i>Profile
                      </a></li>
                      <li><a class="dropdown-item <?= $path === '/settings' ? 'active' : '' ?>" href="/settings">
                        <i class="fas fa-cog me-2"></i>Settings
                      </a></li>
                      <li><hr class="dropdown-divider"></li>
                      <li><a class="dropdown-item text-danger" href="/logout">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                      </a></li>
                    </ul>
                  </li>
                </ul>
              </div>
            </div>
          </nav>

          <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"></script>

          <script>
            document.addEventListener('DOMContentLoaded', function() {
              const navbar = document.querySelector('.navbar');
              const navbarCollapse = document.getElementById('navbarNav');
              const mobileCloseBtn = document.querySelector('.mobile-menu-close');

              // Enhanced scroll effect
              window.addEventListener('scroll', function() {
                if (window.scrollY > 10) {
                  navbar.classList.add('scrolled');
                } else {
                  navbar.classList.remove('scrolled');
                }
              });

              // Mobile menu close functionality
              if (mobileCloseBtn && navbarCollapse) {
                mobileCloseBtn.addEventListener('click', function() {
                  const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse) || new bootstrap.Collapse(navbarCollapse, {
                    toggle: false
                  });
                  bsCollapse.hide();
                });
              }

              // Close mobile menu when clicking on nav links
              const navLinks = document.querySelectorAll('.navbar-nav .nav-link:not(.dropdown-toggle)');
              navLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                  if (window.innerWidth < 992 && navbarCollapse.classList.contains('show')) {
                    const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse) || new bootstrap.Collapse(navbarCollapse, {
                      toggle: false
                    });
                    bsCollapse.hide();
                  }
                });
              });

              // Enhanced dropdown hover for desktop
              if (window.innerWidth >= 992) {
                const dropdowns = document.querySelectorAll('.navbar-center .dropdown');
                dropdowns.forEach(function(dropdown) {
                  dropdown.addEventListener('mouseenter', function() {
                    const menu = this.querySelector('.dropdown-menu');
                    if (menu) {
                      menu.classList.add('show');
                    }
                  });

                  dropdown.addEventListener('mouseleave', function() {
                    const menu = this.querySelector('.dropdown-menu');
                    if (menu) {
                      menu.classList.remove('show');
                    }
                  });
                });
              }

              // Prevent body scroll when mobile menu is open
              if (navbarCollapse) {
                navbarCollapse.addEventListener('shown.bs.collapse', function() {
                  if (window.innerWidth < 992) {
                    document.body.style.overflow = 'hidden';
                  }
                });

                navbarCollapse.addEventListener('hidden.bs.collapse', function() {
                  document.body.style.overflow = '';
                });
              }
            });
          </script>

          <main>