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
      /* Base navbar text */
      .navbar-brand {
        font-size: 1.5rem;
        font-weight: 700;
        color: #09194D;
      }
      .navbar-brand span {
        color: #09194D;
      }

      /* Centered title */
      .navbar-center {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
      }

      /* Toasts */
      .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1055;
      }

      /* Nav links */
      .navbar-nav .nav-link {
        font-weight: 500;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
        color: #09194D;
        position: relative;
        overflow: hidden;
      }
      .navbar-nav .nav-link:hover {
        transform: translateY(-2px);
        color: #09194D;
      }
      .navbar-nav .nav-link::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 2px;
        background: #09194D;
        transition: all 0.3s ease;
        transform: translateX(-50%);
      }
      .navbar-nav .nav-link:hover::before {
        width: 80%;
      }

      /* Remove default caret */
      .profile-dropdown .dropdown-toggle::after {
        display: none;
      }

      /* Notification badge */
      .badge-notification {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #dc3545;
        color: #fff;
        border-radius: 50%;
        padding: 2px 6px;
        font-size: 0.75rem;
      }

      /* Light dropdown */
      .navbar-nav .dropdown-menu {
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        border-radius: 0.5rem;
        padding: 0.5rem 0;
        margin-top: 0.5rem;
        min-width: 200px;
        opacity: 0;
        transform: translateY(-10px);
        visibility: hidden;
        transition: all 0.3s ease;
      }
      .navbar-nav .dropdown-menu.show {
        opacity: 1;
        transform: translateY(0);
        visibility: visible;
      }

      /* Dropdown items */
      .navbar-nav .dropdown-item {
        color: #09194D;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
        background: transparent;
        border: none;
      }
      .navbar-nav .dropdown-item:hover,
      .navbar-nav .dropdown-item:focus {
        background-color: #f1f3f5;
        color: #09194D;
        transform: translateX(4px);
      }
      .navbar-nav .dropdown-item i {
        width: 20px;
        text-align: center;
        margin-right: 0.75rem;
        opacity: 0.8;
        color: #09194D;
      }
      .navbar-nav .dropdown-item:hover i {
        opacity: 1;
        color: #09194D;
      }

      /* Headers & dividers */
      .navbar-nav .dropdown-header {
        color: #09194D;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.5rem 1.5rem 0.75rem;
        border-bottom: 1px solid #dee2e6;
        margin-bottom: 0.5rem;
      }
      .navbar-nav .dropdown-divider {
        border-color: #dee2e6;
        margin: 0.5rem 0;
      }

      /* Button groups */
      .btn-group .btn {
        border-radius: 0.375rem;
        margin-left: 0.25rem;
        background-color: #ffffff;
        color: #09194D;
        border: 1px solid #dee2e6;
      }
      .btn-group .btn:hover {
        background-color: #f1f3f5;
        color: #09194D;
      }
      .btn-group .btn:first-child {
        margin-left: 0;
      }

      /* Mobile */
      @media (max-width: 991.98px) {
        .navbar-nav {
          padding-top: 1rem;
        }
        .navbar-nav .nav-link {
          padding: 0.7rem 1rem;
          border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }
        .navbar-nav .dropdown-menu {
          background-color: #ffffff;
          border: 1px solid #dee2e6;
          box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
          margin-left: 1rem;
          margin-top: 0.25rem;
        }
        .navbar-nav .dropdown-item {
          padding: 0.6rem 1rem;
          color: #09194D;
        }
        .navbar-nav .dropdown-item:hover {
          background-color: #f1f3f5;
          color: #09194D;
          transform: none;
        }
      }

      /* Admin badge */
      .admin-badge {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        color: #ffffff;
        font-size: 0.7rem;
        padding: 0.2rem 0.5rem;
        border-radius: 1rem;
        margin-left: 0.5rem;
        animation: pulse 2s infinite;
      }
      @keyframes pulse {
        0%   { opacity: 1; }
        50%  { opacity: 0.7; }
        100% { opacity: 1; }
      }
    </style>
</head>
<body>
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <nav class="navbar navbar-expand-lg navbar-light bg-gradient" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/home">
                <i class="fas fa-clipboard-list me-2 fa-lg"></i>
                <span>TimeWise</span>
            </a>

            <!-- Center Navigation -->
            <div class="navbar-center d-none d-lg-flex">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/home">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>

                    <!-- INSERTED: Team & Schedule (desktop) -->
                    <li class="nav-item">
                        <a class="nav-link" href="/schedule">
                            <i class="fas fa-users me-1"></i>Team &amp; Schedule
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="remindersDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-tasks me-1"></i>Reminders
                            <span class="badge-notification d-none" id="reminderBadge">3</span>
                        </a>
                        <ul class="dropdown-menu shadow">
                            <li><h6 class="dropdown-header">
                                <i class="fas fa-list me-1"></i>Manage Reminders
                            </h6></li>
                            <li><a class="dropdown-item" href="/notes">
                                <i class="fas fa-eye me-2"></i>View All
                            </a></li>
                            <li><a class="dropdown-item" href="/notes/create">
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
                    <?php if (isset($_SESSION['username']) && strtolower($_SESSION['username']) === 'admin'): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-chart-bar me-1"></i>Reports
                        </a>
                        <ul class="dropdown-menu shadow">
                            <li><h6 class="dropdown-header">
                                <i class="fas fa-chart-line me-1"></i>Admin Reports
                            </h6></li>
                            <li><a class="dropdown-item" href="/reports">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a></li>
                            <li><a class="dropdown-item" href="/reports/allReminders">
                                <i class="fas fa-list me-2"></i>All Reminders
                            </a></li>
                            <li><a class="dropdown-item" href="/reports/userStats">
                                <i class="fas fa-users me-2"></i>User Statistics
                            </a></li>
                            <li><a class="dropdown-item" href="/reports/loginReport">
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
                        <a class="nav-link" href="/home">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>

                    <!-- INSERTED: Team & Schedule (mobile) -->
                    <li class="nav-item">
                        <a class="nav-link" href="/schedule">
                            <i class="fas fa-users me-1"></i>Team &amp; Schedule
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/notes">
                            <i class="fas fa-tasks me-1"></i>My Reminders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/notes/create">
                            <i class="fas fa-plus me-1"></i>Create New
                        </a>
                    </li>
                    <?php if (isset($_SESSION['username']) && strtolower($_SESSION['username']) === 'admin'): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="mobileAdminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-chart-bar me-1"></i>Admin Reports
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/reports">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a></li>
                            <li><a class="dropdown-item" href="/reports/allReminders">
                                <i class="fas fa-list me-2"></i>All Reminders
                            </a></li>
                            <li><a class="dropdown-item" href="/reports/userStats">
                                <i class="fas fa-users me-2"></i>User Statistics
                            </a></li>
                            <li><a class="dropdown-item" href="/reports/loginReport">
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
                                <div class="bg-white rounded-circle p-1 me-2">
                                    <i class="fas fa-user text-success"></i>
                                </div>
                                <span class="d-none d-md-inline"><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></span>
                                <i class="fas fa-chevron-down ms-1"></i>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><h6 class="dropdown-header">
                                <i class="fas fa-user me-1"></i>Account
                            </h6></li>
                            <li><a class="dropdown-item" href="/profile">
                                <i class="fas fa-user-circle me-2"></i>Profile
                            </a></li>
                            <li><a class="dropdown-item" href="/settings">
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
    <main>
