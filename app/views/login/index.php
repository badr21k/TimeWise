<?php require 'app/views/templates/headerPublic.php'; ?>

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

<?php require 'app/views/templates/footer.php'; ?>
