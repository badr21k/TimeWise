<?php require 'app/views/templates/headerPublic.php'; ?>

<div class="container py-5">
		<div class="row justify-content-center">
				<div class="col-md-6 col-lg-4">
						<div class="card shadow">
								<div class="card-body p-4">
										<h1 class="h4 text-center mb-4">
												<i class="fas fa-sign-in-alt text-primary me-2"></i>
												Login
										</h1>

										<?php if (isset($_SESSION['login_error'])): ?>
												<div class="alert alert-danger"><?= $_SESSION['login_error']; unset($_SESSION['login_error']); ?></div>
										<?php endif; ?>

										<form action="/login/verify" method="post">
												<div class="mb-3">
														<label for="username" class="form-label">Username:</label>
														<input type="text" class="form-control" id="username" name="username" required>
												</div>

												<div class="mb-3">
														<label for="password" class="form-label">Password:</label>
														<input type="password" class="form-control" id="password" name="password" required>
												</div>

												<button type="submit" class="btn btn-primary w-100">
														<i class="fas fa-sign-in-alt me-1"></i>Sign In
												</button>
										</form>

										<div class="text-center mt-3">
												<p class="mb-0">
														Don't have an account? 
														<a href="/signup" class="text-decoration-none">Create one here</a>
												</p>
										</div>
								</div>
						</div>
				</div>
		</div>
</div>

<?php require 'app/views/templates/footer.php'; ?>
