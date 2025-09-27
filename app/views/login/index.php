<?php require 'app/views/templates/headerPublic.php'; ?>

<style>
  /* Page background and card styling */
  .auth-wrapper {
    background: radial-gradient(1200px 600px at -10% -10%, rgba(118, 75, 162, 0.25) 0%, rgba(118, 75, 162, 0) 60%),
                radial-gradient(1200px 600px at 110% 110%, rgba(102, 126, 234, 0.25) 0%, rgba(102, 126, 234, 0) 60%),
                linear-gradient(135deg, #f3f6ff 0%, #ffffff 100%);
  }
  .auth-card {
    border-radius: 1rem;
    backdrop-filter: saturate(160%) blur(6px);
  }
  .brand-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 56px;
    height: 56px;
    border-radius: 14px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    box-shadow: 0 10px 20px rgba(102, 126, 234, 0.35);
  }
  .btn-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border: 0;
    transition: transform .2s ease, box-shadow .2s ease, filter .2s ease;
  }
  .btn-gradient:hover { 
    color: #fff; 
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(118, 75, 162, 0.35);
    filter: brightness(1.02);
  }
  .form-control {
    border-radius: .6rem;
  }
  .form-control:focus {
    box-shadow: 0 0 0 .25rem rgba(102, 126, 234, 0.25);
    border-color: #667eea;
  }
  .input-icon {
    position: absolute; 
    left: 12px; 
    top: 50%; 
    transform: translateY(-50%);
    color: #6c757d;
  }
  .ps-40 { padding-left: 40px !important; }
  .password-toggle {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    border: 0;
    background: transparent;
    color: #6c757d;
  }
  .helper-links a { color: #6c757d; text-decoration: none; }
  .helper-links a:hover { color: #4b5bdc; }
  .divider { position: relative; text-align: center; }
  .divider::before, .divider::after {
    content: "";
    position: absolute;
    top: 50%;
    width: 40%;
    height: 1px;
    background: #e9ecef;
  }
  .divider::before { left: 0; }
  .divider::after { right: 0; }
  .divider span { color: #adb5bd; font-size: .875rem; }
  @media (max-width: 576px) {
    .card-body { padding: 1.25rem !important; }
  }
</style>

<div class="auth-wrapper d-flex align-items-center justify-content-center min-vh-100 position-relative">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="card auth-card shadow-lg border-0">
          <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4">
              <div class="brand-badge mb-3">
                <i class="fas fa-clipboard-list fa-lg"></i>
              </div>
              <h1 class="h4 mb-1">Welcome back</h1>
              <p class="text-muted mb-0">Sign in to continue to TimeWise</p>
            </div>

            <?php if (isset($_SESSION['login_error'])): ?>
              <div class="alert alert-danger d-flex align-items-center" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <div><?= $_SESSION['login_error']; unset($_SESSION['login_error']); ?></div>
              </div>
            <?php endif; ?>

            <form action="/login/verify" method="post" novalidate>
              <div class="mb-3 position-relative">
                <i class="fas fa-user input-icon"></i>
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control ps-40" id="username" name="username" autocomplete="username" required aria-required="true" placeholder="Your username">
              </div>

              <div class="mb-3 position-relative">
                <i class="fas fa-lock input-icon"></i>
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control ps-40" id="password" name="password" autocomplete="current-password" required aria-required="true" placeholder="Your password">
                <button type="button" class="password-toggle" aria-label="Toggle password visibility" tabindex="-1">
                  <i class="far fa-eye" id="passwordToggleIcon"></i>
                </button>
              </div>

              <div class="d-flex justify-content-between align-items-center mb-4 helper-links">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
                  <label class="form-check-label" for="remember">
                    Remember me
                  </label>
                </div>
                <a href="#" class="small">Forgot password?</a>
              </div>

              <button type="submit" class="btn btn-gradient btn-lg w-100">
                <i class="fas fa-sign-in-alt me-2"></i> Sign In
              </button>
            </form>

            <!-- Optional: alternative actions
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
    </div>
  </div>
</div>

<script>
  // Password visibility toggle
  (function() {
    const passwordInput = document.getElementById('password');
    const toggleBtn = document.querySelector('.password-toggle');
    const toggleIcon = document.getElementById('passwordToggleIcon');
    if (passwordInput && toggleBtn && toggleIcon) {
      toggleBtn.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        toggleIcon.classList.toggle('fa-eye');
        toggleIcon.classList.toggle('fa-eye-slash');
      });
    }
  })();
</script>

<?php require 'app/views/templates/footer.php'; ?>
