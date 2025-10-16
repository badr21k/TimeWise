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
  overflow-x: hidden;
}

body::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: 
    radial-gradient(circle at 20% 80%, rgba(181, 158, 95, 0.1) 0%, transparent 50%),
    radial-gradient(circle at 80% 20%, rgba(141, 119, 171, 0.1) 0%, transparent 50%),
    radial-gradient(circle at 40% 40%, rgba(9, 25, 77, 0.05) 0%, transparent 50%);
  pointer-events: none;
}

/* Auth Wrapper */
.auth-wrapper {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  position: relative;
}

/* Auth Card */
.auth-card {
  background: var(--card);
  border: 2px solid var(--border);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-xl);
  backdrop-filter: blur(20px);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
  width: 100%;
  max-width: 440px;
  animation: fadeInUp 0.8s ease-out;
}

.auth-card::before {
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

.auth-card:hover::before {
  opacity: 1;
}

.auth-card:hover {
  transform: translateY(-8px);
  box-shadow: var(--shadow-xl);
}

.card-body {
  padding: 48px 40px;
}

/* Brand Badge */
.brand-badge {
  width: 80px;
  height: 80px;
  background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 24px;
  position: relative;
  box-shadow: 0 8px 32px rgba(9, 25, 77, 0.2);
  transition: all 0.3s ease;
  animation: float 3s ease-in-out infinite;
}

.brand-badge::before {
  content: '';
  position: absolute;
  inset: -2px;
  background: linear-gradient(135deg, var(--accent), var(--accent-secondary));
  border-radius: 50%;
  z-index: -1;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.brand-badge:hover::before {
  opacity: 1;
}

.brand-badge:hover {
  transform: scale(1.05) rotate(5deg);
}

.brand-badge i {
  font-size: 2rem;
  color: white;
}

/* Typography */
h1 {
  font-size: 2rem;
  font-weight: 800;
  color: var(--primary);
  margin-bottom: 8px;
  letter-spacing: -0.02em;
  text-align: center;
}

.text-muted {
  font-size: 1.1rem;
  color: var(--muted);
  font-weight: 500;
  text-align: center;
  margin-bottom: 32px;
}

/* Form Controls */
.form-group {
  position: relative;
  margin-bottom: 24px;
}

.form-label {
  font-weight: 600;
  color: var(--primary);
  margin-bottom: 8px;
  font-size: 0.95rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  display: block;
}

.form-control {
  border: 2px solid var(--border);
  border-radius: var(--radius-sm);
  padding: 16px 20px 16px 48px;
  font-size: 1rem;
  font-weight: 500;
  transition: all 0.3s ease;
  background: var(--lighter);
  color: var(--primary);
  width: 100%;
}

.form-control:focus {
  border-color: var(--accent);
  box-shadow: 0 0 0 4px rgba(181, 158, 95, 0.15);
  background: white;
  outline: none;
}

.form-control::placeholder {
  color: var(--neutral-light);
  font-weight: 400;
}

/* Input Icons - Fixed Positioning */
.position-relative {
  position: relative;
}

.input-icon {
  position: absolute;
  left: 16px;
  top: calc(50% + 14px);
  transform: translateY(-50%);
  color: var(--accent);
  font-size: 1.1rem;
  z-index: 2;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}

.form-control:focus + .input-icon {
  color: var(--accent-dark);
  transform: translateY(-50%) scale(1.1);
}

/* Password Toggle */
.password-toggle {
  position: absolute;
  right: 16px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  color: var(--neutral);
  cursor: pointer;
  padding: 8px;
  border-radius: var(--radius-sm);
  transition: all 0.3s ease;
  z-index: 2;
}

.password-toggle:hover {
  color: var(--accent);
  background: var(--light);
}

/* Checkbox */
.form-check {
  display: flex;
  align-items: center;
}

.form-check-input {
  width: 18px;
  height: 18px;
  border: 2px solid var(--border);
  border-radius: 4px;
  margin-right: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
  appearance: none;
  position: relative;
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
  font-weight: 500;
  color: var(--primary);
  cursor: pointer;
  user-select: none;
}

/* Helper Links */
.helper-links {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin: 24px 0;
}

.helper-links a {
  color: var(--accent);
  text-decoration: none;
  font-weight: 600;
  font-size: 0.9rem;
  transition: all 0.3s ease;
  position: relative;
}

.helper-links a::after {
  content: '';
  position: absolute;
  bottom: -2px;
  left: 0;
  width: 0;
  height: 2px;
  background: var(--accent);
  transition: width 0.3s ease;
}

.helper-links a:hover {
  color: var(--accent-dark);
}

.helper-links a:hover::after {
  width: 100%;
}

/* Button */
.btn-gradient {
  background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
  border: none;
  color: white;
  padding: 16px 32px;
  font-size: 1.1rem;
  font-weight: 700;
  border-radius: var(--radius-lg);
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  box-shadow: 0 8px 32px rgba(9, 25, 77, 0.3);
  width: 100%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

.btn-gradient::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
  transition: left 0.6s;
}

.btn-gradient:hover::before {
  left: 100%;
}

.btn-gradient:hover {
  transform: translateY(-2px);
  box-shadow: 0 12px 40px rgba(9, 25, 77, 0.4);
}

.btn-gradient:active {
  transform: translateY(0);
}

/* Alert */
.alert {
  border: none;
  border-radius: var(--radius-sm);
  padding: 16px 20px;
  font-weight: 500;
  margin-bottom: 24px;
  border-left: 4px solid;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
  display: flex;
  align-items: center;
  gap: 12px;
}

.alert-danger {
  background: linear-gradient(135deg, #fef2f2 0%, #fef2f2 100%);
  color: var(--danger);
  border-left-color: var(--danger);
}

.alert i {
  font-size: 1.1rem;
}

/* Animations */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(40px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes float {
  0%, 100% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-10px);
  }
}

/* Responsive */
@media (max-width: 768px) {
  .card-body {
    padding: 32px 24px;
  }
  
  h1 {
    font-size: 1.75rem;
  }
  
  .brand-badge {
    width: 70px;
    height: 70px;
  }
  
  .brand-badge i {
    font-size: 1.75rem;
  }
  
  .btn-gradient {
    padding: 14px 24px;
    font-size: 1rem;
  }
}

@media (max-width: 480px) {
  .card-body {
    padding: 24px 20px;
  }
  
  h1 {
    font-size: 1.5rem;
  }
  
  .form-control {
    padding: 14px 16px 14px 44px;
  }
  
  .input-icon {
    left: 14px;
    top: calc(50% + 12px);
  }
  
  .helper-links {
    flex-direction: column;
    gap: 16px;
    align-items: flex-start;
  }
}

/* Utility Classes */
.text-center { text-align: center; }
.mb-0 { margin-bottom: 0; }
.mb-1 { margin-bottom: 0.25rem; }
.mb-3 { margin-bottom: 1rem; }
.mb-4 { margin-bottom: 1.5rem; }
.w-100 { width: 100%; }
</style>

<div class="auth-wrapper">
  <div class="auth-card">
    <div class="card-body">
      <!-- Header Section -->
      <div class="text-center mb-4">
        <div class="brand-badge">
          <i class="fas fa-clipboard-list"></i>
        </div>
        <h1>Welcome back</h1>
        <p class="text-muted">Sign in to continue to TimeWise</p>
      </div>

      <!-- Error Alert -->
      <?php if (isset($_SESSION['login_error'])): ?>
        <div class="alert alert-danger" role="alert">
          <i class="fas fa-exclamation-triangle"></i>
          <div><?= $_SESSION['login_error']; unset($_SESSION['login_error']); ?></div>
        </div>
      <?php endif; ?>

      <!-- Login Form -->
      <form action="/login/verify" method="post" novalidate>
        <!-- Username Field -->
        <div class="form-group position-relative">
          <i class="fas fa-user input-icon"></i>
          <label for="username" class="form-label">Username</label>
          <input type="text" 
                 class="form-control" 
                 id="username" 
                 name="username" 
                 autocomplete="username" 
                 required 
                 aria-required="true" 
                 placeholder="Your username" 
                 value="<?= isset($_COOKIE['remember_username']) ? htmlspecialchars($_COOKIE['remember_username']) : '' ?>">
        </div>

        <!-- Password Field -->
        <div class="form-group position-relative">
          <i class="fas fa-lock input-icon"></i>
          <label for="password" class="form-label">Password</label>
          <input type="password" 
                 class="form-control" 
                 id="password" 
                 name="password" 
                 autocomplete="current-password" 
                 required 
                 aria-required="true" 
                 placeholder="Your password">
          <button type="button" class="password-toggle" aria-label="Toggle password visibility" tabindex="-1">
            <i class="far fa-eye" id="passwordToggleIcon"></i>
          </button>
        </div>

        <!-- Helper Section -->
        <div class="helper-links">
          <div class="form-check">
            <input class="form-check-input" 
                   type="checkbox" 
                   value="1" 
                   id="remember" 
                   name="remember" 
                   <?= isset($_COOKIE['remember_username']) ? 'checked' : '' ?>>
            <label class="form-check-label" for="remember">
              Remember me
            </label>
          </div>
          <a href="#" class="forgot-password" onclick="showForgotPasswordMessage(); return false;">Forgot password?</a>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-gradient">
          <i class="fas fa-sign-in-alt"></i> Sign In
        </button>
      </form>

      <!-- Optional: Alternative Actions -->
      <!--
      <div class="divider my-4"><span>or</span></div>
      <div class="d-grid gap-2">
        <a href="/signup" class="btn btn-outline-secondary">
          <i class="fas fa-user-plus me-2"></i> Create an account
        </a>
      </div>
      -->
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const passwordToggle = document.querySelector('.password-toggle');
  const passwordInput = document.getElementById('password');
  const passwordToggleIcon = document.getElementById('passwordToggleIcon');
  
  // Password visibility toggle
  passwordToggle.addEventListener('click', function() {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    passwordToggleIcon.classList.toggle('fa-eye');
    passwordToggleIcon.classList.toggle('fa-eye-slash');
    
    // Add animation feedback
    this.style.transform = 'translateY(-50%) scale(1.1)';
    setTimeout(() => {
      this.style.transform = 'translateY(-50%) scale(1)';
    }, 200);
  });
  
  // Auto-focus username field
  const usernameField = document.getElementById('username');
  if (usernameField && !usernameField.value) {
    setTimeout(() => {
      usernameField.focus();
    }, 400);
  }
});

// Forgot password message
function showForgotPasswordMessage() {
  // Create a beautiful toast notification
  const toast = document.createElement('div');
  toast.className = 'position-fixed top-0 start-50 translate-middle-x mt-3';
  toast.style.zIndex = '9999';
  toast.style.minWidth = '300px';
  toast.innerHTML = `
    <div style="
      background: white;
      border-radius: var(--radius-sm);
      padding: 16px 20px;
      box-shadow: var(--shadow-xl);
      border-left: 4px solid var(--accent);
      display: flex;
      align-items: center;
      gap: 12px;
    ">
      <i class="fas fa-info-circle" style="color: var(--accent); font-size: 1.2rem;"></i>
      <div>
        <strong>Password Reset</strong><br>
        <small>Please contact your administrator to reset your password.</small>
      </div>
      <button type="button" class="btn-close" onclick="this.parentElement.parentElement.remove()" style="
        background: none;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        margin-left: auto;
      ">×</button>
    </div>
  `;
  
  document.body.appendChild(toast);
  
  // Auto-remove after 5 seconds
  setTimeout(() => {
    if (toast.parentElement) {
      toast.remove();
    }
  }, 5000);
}
</script>
