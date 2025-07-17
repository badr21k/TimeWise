<?php
if (isset($_SESSION['auth']) == 1) {
    header('Location: /home');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="/favicon.png">
    <title>ReminderApp - Stay Organized</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <style>
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
        }
        .navbar-center {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1055;
        }
        .navbar-nav .nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }
        .navbar-nav .nav-link:hover {
            transform: translateY(-2px);
        }
        .btn-gradient {
            background: linear-gradient(45deg, #007bff, #6610f2);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <nav class="navbar navbar-expand-lg navbar-dark bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <i class="fas fa-clipboard-list me-2 fa-lg"></i>
                <span>ReminderApp</span>
            </a>

            <!-- Center Navigation -->
            <div class="navbar-center d-none d-lg-flex">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/">
                            <i class="fas fa-home me-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="featuresDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-star me-1"></i>Features
                        </a>
                        <ul class="dropdown-menu shadow">
                            <li><a class="dropdown-item" href="#features">
                                <i class="fas fa-tasks me-2"></i>Task Management
                            </a></li>
                            <li><a class="dropdown-item" href="#features">
                                <i class="fas fa-bell me-2"></i>Smart Reminders
                            </a></li>
                            <li><a class="dropdown-item" href="#features">
                                <i class="fas fa-sync me-2"></i>Cloud Sync
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#features">
                                <i class="fas fa-mobile-alt me-2"></i>Mobile App
                            </a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">
                            <i class="fas fa-info-circle me-1"></i>About
                        </a>
                    </li>
                </ul>
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Mobile Navigation -->
                <ul class="navbar-nav me-auto d-lg-none">
                    <li class="nav-item">
                        <a class="nav-link" href="/">
                            <i class="fas fa-home me-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">
                            <i class="fas fa-star me-1"></i>Features
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">
                            <i class="fas fa-info-circle me-1"></i>About
                        </a>
                    </li>
                </ul>

                <!-- Right Side Navigation -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/login">
                            <i class="fas fa-sign-in-alt me-1"></i>Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-gradient ms-2 px-3 rounded-pill" href="/signup">
                            <i class="fas fa-user-plus me-1"></i>Sign Up Free
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <main>