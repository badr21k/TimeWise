<?php require 'app/views/templates/headerPublic.php'; ?>

<div class="auth-wrapper d-flex align-items-center justify-content-center min-vh-100 position-relative">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="card auth-card shadow-lg border-0">
          <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4">
              <div class="brand-badge mb-3">
                <i class="fas fa-unlock-alt fa-lg"></i>
              </div>
              <h1 class="h4 mb-1">Forgot password</h1>
              <p class="text-muted mb-0">Enter your username to get a reset link</p>
            </div>

            <?php if (!empty($_SESSION['reset_debug_link'])): ?>
              <div class="alert alert-warning" role="alert">
                <i class="fas fa-bug me-1"></i>
                Dev-only: <a class="alert-link" href="<?= htmlspecialchars($_SESSION['reset_debug_link']) ?>">Open reset link</a>
              </div>
              <?php unset($_SESSION['reset_debug_link']); ?>
            <?php endif; ?>

            <form action="/password/send" method="post" novalidate>
              <div class="mb-3 position-relative">
                <i class="fas fa-user input-icon"></i>
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control ps-40" id="username" name="username" required placeholder="Your username">
              </div>

              <button type="submit" class="btn btn-gradient btn-lg w-100">
                <i class="fas fa-paper-plane me-2"></i> Send reset link
              </button>
            </form>

            <div class="text-center mt-3">
              <a href="/login" class="text-decoration-none">Back to login</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require 'app/views/templates/footer.php'; ?>
