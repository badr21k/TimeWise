    <?php require_once 'app/views/templates/header.php'; ?>

    <section class="py-5 text-center bg-white mb-4">
      <div class="container">
        <h1 class="display-4 fw-bold">Hey, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
        <p class="lead text-muted">Today is <?= date("F jS, Y") ?>.</p>
        <a href="/logout" class="btn btn-outline-danger btn-lg mt-3">
          Logout
        </a>
      </div>
    </section>

    <section class="container text-center">
      <p class="text-secondary">
        Welcome to your dashboard. Use the menu above to navigate.
      </p>
    </section>

    <?php require_once 'app/views/templates/footer.php'; ?>
