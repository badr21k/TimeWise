        <?php
        if (!isset($_SESSION['auth'])) {
            header('Location: /login');
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
            <style>
              :root {
                --primary: #09194D;
                --secondary: #D97F76;
                --light: #E4E4EF;
                --lighter: #F4F5F0;
                --neutral: #9B9498;
                --dark: #2D2926;
                --accent: #B59E5F;
                --accent-secondary: #8D77AB;
              }

              /* Modern navbar styling */
              .navbar {
                background: linear-gradient(135deg, var(--light) 0%, var(--lighter) 100%) !important;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                padding: 0.6rem 0;
                transition: all 0.3s ease;
                z-index: 1050;
                position: relative;
              }

              .navbar.scrolled {
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                padding: 0.4rem 0;
              }

              /* Brand styling */
              .navbar-brand {
                font-size: 1.6rem;
                font-weight: 700;
                color: var(--primary);
                display: flex;
                align-items: center;
                transition: all 0.3s ease;
              }

              .navbar-brand i {
                background: linear-gradient(135deg, var(--primary), var(--accent-secondary));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                transition: all 0.3s ease;
              }

              .navbar-brand:hover {
                transform: translateY(-1px);
              }

              .navbar-brand:hover i {
                transform: scale(1.1);
              }

              /* Centered navigation */
              .navbar-center {
                position: absolute;
                left: 50%;
                transform: translateX(-50%);
              }

              /* Toast container */
              .toast-container {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 1055;
              }

              /* Nav links */
              .navbar-nav .nav-link {
                font-weight: 500;
                padding: 0.6rem 1.1rem !important;
                transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
                color: var(--primary);
                position: relative;
                border-radius: 8px;
                margin: 0 0.1rem;
              }

              .navbar-nav .nav-link:hover {
                color: var(--primary);
                background-color: rgba(255, 255, 255, 0.7);
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
              }

              .navbar-nav .nav-link.active {
                background-color: rgba(255, 255, 255, 0.9);
                color: var(--primary);
                font-weight: 600;
              }

              .navbar-nav .nav-link::before {
                content: '';
                position: absolute;
                bottom: 0;
                left: 50%;
                width: 0;
                height: 3px;
                background: linear-gradient(90deg, var(--secondary), var(--accent));
                transition: all 0.3s ease;
                transform: translateX(-50%);
                border-radius: 3px;
              }

              .navbar-nav .nav-link:hover::before,
              .navbar-nav .nav-link.active::before {
                width: 70%;
              }

              /* Remove default caret */
              .profile-dropdown .dropdown-toggle::after {
                display: none;
              }

              /* Notification badge */
              .badge-notification {
                position: absolute;
                top: 2px;
                right: 2px;
                background: var(--secondary);
                color: white;
                border-radius: 50%;
                padding: 3px 7px;
                font-size: 0.7rem;
                font-weight: 600;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                animation: pulse 2s infinite;
              }

              /* Modern dropdown */
              .navbar-nav .dropdown-menu {
                background-color: white;
                border: none;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
                border-radius: 12px;
                padding: 0.8rem 0;
                margin-top: 0.8rem;
                min-width: 220px;
                opacity: 0;
                transform: translateY(10px) scale(0.95);
                visibility: hidden;
                transition: all 0.25s cubic-bezier(0.25, 0.8, 0.25, 1);
                z-index: 9999;
                position: absolute;
                overflow: hidden;
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
              }

              .navbar-nav .dropdown-menu.show {
                opacity: 1;
                transform: translateY(0) scale(1);
                visibility: visible;
              }

              /* Dropdown items */
              .navbar-nav .dropdown-item {
                color: var(--dark);
                padding: 0.8rem 1.5rem;
                font-weight: 500;
                transition: all 0.2s ease;
                background: transparent;
                border: none;
                position: relative;
                display: flex;
                align-items: center;
              }

              .navbar-nav .dropdown-item:hover,
              .navbar-nav .dropdown-item:focus,
              .navbar-nav .dropdown-item.active {
                background: linear-gradient(135deg, var(--light) 0%, var(--lighter) 100%);
                color: var(--primary);
                padding-left: 1.8rem;
              }

              .navbar-nav .dropdown-item i {
                width: 22px;
                text-align: center;
                margin-right: 0.8rem;
                color: var(--primary);
                transition: all 0.2s ease;
              }

              .navbar-nav .dropdown-item:hover i {
                transform: scale(1.1);
                color: var(--accent-secondary);
              }

              /* Headers & dividers */
              .navbar-nav .dropdown-header {
                color: var(--primary);
                font-size: 0.85rem;
                font-weight: 700;
                padding: 0.6rem 1.5rem 0.8rem;
                margin-bottom: 0.3rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                border-bottom: 1px solid rgba(0, 0, 0, 0.08);
              }

              .navbar-nav .dropdown-divider {
                border-color: rgba(0, 0, 0, 0.08);
                margin: 0.5rem 0;
              }

              /* Button groups */
              .btn-group .btn {
                border-radius: 8px;
                margin-left: 0.3rem;
                background-color: white;
                color: var(--primary);
                border: 1px solid rgba(0, 0, 0, 0.1);
                transition: all 0.2s ease;
              }

              .btn-group .btn:hover {
                background-color: var(--light);
                color: var(--primary);
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
              }

              .btn-group .btn:first-child {
                margin-left: 0;
              }

              /* Mobile view */
              @media (max-width: 991.98px) {
                .navbar-nav {
                  padding-top: 1rem;
                }

                .navbar-nav .nav-link {
                  padding: 0.8rem 1rem !important;
                  border-bottom: 1px solid rgba(0, 0, 0, 0.08);
                  margin: 0.1rem 0;
                }

                .navbar-nav .dropdown-menu {
                  background-color: rgba(255, 255, 255, 0.95);
                  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
                  margin-left: 1rem;
                  margin-top: 0.3rem;
                  border-radius: 8px;
                }

                .navbar-nav .dropdown-menu::before {
                  display: none;
                }

                .navbar-nav .dropdown-item {
                  padding: 0.7rem 1rem;
                }

                .navbar-nav .dropdown-item:hover {
                  padding-left: 1.2rem;
                  transform: none;
                }
              }

              /* Profile section */
              .profile-dropdown .dropdown-toggle {
                border-radius: 50px;
                padding: 0.4rem 0.8rem;
                transition: all 0.3s ease;
                border: 1px solid transparent;
              }

              .profile-dropdown .dropdown-toggle:hover {
                background: rgba(255, 255, 255, 0.7);
                border-color: rgba(0, 0, 0, 0.1);
              }

              .user-icon {
                background: linear-gradient(135deg, var(--primary), var(--accent-secondary));
                color: white;
                width: 32px;
                height: 32px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 0.5rem;
                font-size: 0.9rem;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
              }

              /* Admin badge */
              .admin-badge {
                background: linear-gradient(135deg, var(--secondary), var(--accent));
                color: white;
                font-size: 0.7rem;
                padding: 0.25rem 0.6rem;
                border-radius: 50px;
                margin-left: 0.5rem;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                animation: pulse 2s infinite;
              }

              @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.05); }
                100% { transform: scale(1); }
              }

              /* Toggler animation */
              .navbar-toggler {
                border: none;
                padding: 0.4rem 0.5rem;
                transition: all 0.3s ease;
              }

              .navbar-toggler:focus {
                box-shadow: 0 0 0 2px var(--light);
              }

              .navbar-toggler-icon {
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='30' height='30' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(9, 25, 77, 0.8)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
                transition: all 0.3s ease;
              }
            </style>
        </head>

        <body>
            <!-- Toast Container -->
            <div class="toast-container" id="toastContainer"></div>
            <?php
            $path    = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
            $isSched = (bool)preg_match('#^/schedule\b#', $path);
            $isDept  = (bool)preg_match('#^/departments\b#', $path);
            $isTeamActive = ($isSched || $isDept);
            ?>

            <nav class="navbar navbar-expand-lg navbar-light sticky-top">
                <div class="container">
                    <a class="navbar-brand" href="/home" style="padding:0; display:flex; align-items:center;">
                        <?php include __DIR__ . '/logo.php'; ?>
                    </a>


                    <!-- Center Navigation -->
                    <div class="navbar-center d-none d-lg-flex">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link <?= preg_match('#^/home\b#', $path) ? 'active' : '' ?>" href="/home">
                                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                                </a>
                            </li>
                          <li class="nav-item">
                            <a class="nav-link <?= preg_match('#^/chat\b#', $path) ? 'active' : '' ?>" href="/chat">
                              <i class="fas fa-comments me-1"></i>Chat
                            </a>
                          </li>


                            <!-- Team & Schedule (desktop) -->
                            <li class="nav-item dropdown <?= $isTeamActive ? 'active' : '' ?>">
                              <a class="nav-link dropdown-toggle <?= $isTeamActive ? 'active' : '' ?>"
                                 href="#" id="teamSchedDropdown" role="button"
                                 data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-users me-1"></i> Team &amp; Schedule
                              </a>
                              <ul class="dropdown-menu" aria-labelledby="teamSchedDropdown">
                                <li>
                                  <a class="dropdown-item <?= $isSched ? 'active' : '' ?>" href="/schedule">
                                    <i class="fas fa-calendar-alt me-2"></i> Schedule
                                  </a>
                                </li>
                                <li>
                                  <a class="dropdown-item <?= $isDept ? 'active' : '' ?>" href="/departments">
                                    <i class="fas fa-sitemap me-2"></i> Departments &amp; Roles
                                  </a>
                                </li>
                                  <li>
                                    <a class="dropdown-item <?= preg_match('#^/team\b#', $path) ? 'active' : '' ?>" href="/team">
                                      <i class="fas fa-users me-2"></i> Team roster
                                    </a>
                                  </li>
                              </ul>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle <?= preg_match('#^/notes\b#', $path) ? 'active' : '' ?>" 
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
                                        <i class="fas fa-clock me-2 text-warning"></i>Pending
                                    </a></li>
                                    <li><a class="dropdown-item" href="/notes?filter=completed">
                                        <i class="fas fa-check me-2 text-success"></i>Completed
                                    </a></li>
                                </ul>
                            </li>
                            <?php if (isset($_SESSION['is_admin']) && (int)$_SESSION['is_admin'] === 1): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle <?= preg_match('#^/reports\b#', $path) ? 'active' : '' ?>" 
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
                                </ul>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarNav">
                        <!-- Mobile Navigation -->
                        <ul class="navbar-nav me-auto d-lg-none">
                            <li class="nav-item">
                                <a class="nav-link <?= preg_match('#^/home\b#', $path) ? 'active' : '' ?>" href="/home">
                                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                                </a>
                            </li>
                          <li class="nav-item">
                            <a class="nav-link <?= preg_match('#^/chat\b#', $path) ? 'active' : '' ?>" href="/chat">
                              <i class="fas fa-comments me-1"></i>Chat
                            </a>
                          </li>


                            <!-- Team & Schedule (mobile) -->
                            <li class="nav-item">
                              <a class="nav-link <?= $isSched ? 'active' : '' ?>" href="/schedule">
                                <i class="fas fa-calendar-alt me-1"></i>Schedule
                              </a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link <?= $isDept ? 'active' : '' ?>" href="/departments">
                                <i class="fas fa-sitemap me-1"></i>Departments &amp; Roles
                              </a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link <?= preg_match('#^/team\b#', $path) ? 'active' : '' ?>" href="/team">
                                <i class="fas fa-users me-1"></i>Team Roster
                              </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link <?= $path === '/notes' ? 'active' : '' ?>" href="/notes">
                                    <i class="fas fa-tasks me-1"></i>My Reminders
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $path === '/notes/create' ? 'active' : '' ?>" href="/notes/create">
                                    <i class="fas fa-plus me-1"></i>Create New
                                </a>
                            </li>
                            <?php if (isset($_SESSION['is_admin']) && (int)$_SESSION['is_admin'] === 1): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle <?= preg_match('#^/reports\b#', $path) ? 'active' : '' ?>" 
                                   href="#" id="mobileAdminDropdown" role="button" 
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-chart-bar me-1"></i>Admin Reports
                                </a>
                                <ul class="dropdown-menu">
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
                                </ul>
                            </li>
                            <?php endif; ?>
                        </ul>

                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center profile-dropdown" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="d-flex align-items-center">
                                        <div class="user-icon">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <span class="d-none d-md-inline"><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></span>
                                        <?php if (isset($_SESSION['is_admin']) && (int)$_SESSION['is_admin'] === 1): ?>
                                            <span class="admin-badge">Admin</span>
                                        <?php endif; ?>
                                        <i class="fas fa-chevron-down ms-2 small"></i>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow">
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
              // Add scroll effect to navbar
              document.addEventListener('DOMContentLoaded', function() {
                const navbar = document.querySelector('.navbar');

                window.addEventListener('scroll', function() {
                  if (window.scrollY > 10) {
                    navbar.classList.add('scrolled');
                  } else {
                    navbar.classList.remove('scrolled');
                  }
                });
              });
            </script>

            <main>