<?php require 'app/views/templates/headerPublic.php'; ?>

<div class="auth-wrapper d-flex align-items-center justify-content-center min-vh-100 position-relative">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="card auth-card shadow-lg border-0">
          <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4">
              <div class="brand-badge mb-3">
                <i class="fas fa-key fa-lg"></i>
              </div>
              <h1 class="h4 mb-1">Reset password</h1>
              <p class="text-muted mb-0">Choose a new password to access TimeWise</p>
            </div>

            <form action="/password/update" method="post" novalidate>
              <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">
              <div class="mb-3">
                <label for="password" class="form-label">New password</label>
                <input type="password" class="form-control" id="password" name="password" required placeholder="At least 6 characters">
              </div>
              <div class="mb-3">
                <label for="confirm" class="form-label">Confirm password</label>
                <input type="password" class="form-control" id="confirm" name="confirm" required>
              </div>

              <button type="submit" class="btn btn-gradient btn-lg w-100">
                <i class="fas fa-check me-2"></i> Update password
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
