<?php require 'app/views/templates/headerPublic.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow">
                <div class="card-body p-4">
                    <h1 class="h4 text-center mb-4">
                        <i class="fas fa-user-plus text-primary me-2"></i>
                        Create Account
                    </h1>

                    <?php
                    if (isset($_SESSION['signup_error'])) {
                        echo "<div class='alert alert-danger'>" . $_SESSION['signup_error'] . "</div>";
                        unset($_SESSION['signup_error']);
                    }

                    if (isset($_SESSION['message'])) {
                        echo "<div class='alert alert-success'>" . $_SESSION['message'] . "</div>";
                        unset($_SESSION['message']);
                    }
                    ?>

                    <form action="/signup/register" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username:</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="mb-3">
                            <label for="confirm" class="form-label">Confirm Password:</label>
                            <input type="password" class="form-control" id="confirm" name="confirm" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-user-plus me-1"></i>Create Account
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <p class="mb-0">
                            Already have an account? 
                            <a href="/login" class="text-decoration-none">Sign in here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'app/views/templates/footer.php'; ?>
