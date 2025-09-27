</main>

<footer class="bg-light py-5 mt-5">
  <div class="container">
    <div class="row gy-4 justify-content-between align-items-start">
      <!-- Brand & Description -->
      <div class="col-12 col-md-4">
        <h5 class="d-flex align-items-center mb-3">
          <i class="fas fa-clipboard-list me-2"></i>
          TimeWise
        </h5>
        <p class="text-muted">
          Stay organized and never miss important tasks. Simple, secure, and always available.
        </p>
        <div class="d-flex">
          <a href="#" class="me-3 social-icons" title="Facebook">
            <i class="fab fa-facebook-f"></i>
          </a>
          <a href="#" class="me-3 social-icons" title="Twitter">
            <i class="fab fa-twitter"></i>
          </a>
          <a href="#" class="me-3 social-icons" title="LinkedIn">
            <i class="fab fa-linkedin-in"></i>
          </a>
          <a href="#" class="social-icons" title="Instagram">
            <i class="fab fa-instagram"></i>
          </a>
        </div>
      </div>

      <!-- Support Links -->
      <div class="col-6 col-md-2">
        <h6 class="text-uppercase fw-bold mb-3">Support</h6>
        <ul class="list-unstyled">
          <li><a href="#" class="footer-link">Help Center</a></li>
          <li><a href="#" class="footer-link">Contact Us</a></li>
        </ul>
      </div>

      <!-- Legal Links -->
      <div class="col-6 col-md-2">
        <h6 class="text-uppercase fw-bold mb-3">Legal</h6>
        <ul class="list-unstyled">
          <li><a href="#" class="footer-link">Privacy Policy</a></li>
          <li><a href="#" class="footer-link">Terms of Service</a></li>
        </ul>
      </div>

      <!-- Contact Email -->
      <div class="col-12 col-md-4 text-md-end">
        <p class="text-muted mb-0">
          <i class="fas fa-envelope me-1"></i>
          <a href="mailto:moussa.badr@ausu82.ca" class="footer-link">moussa.badr@ausu82.ca</a>
        </p>
      </div>
    </div>

    <hr class="my-4">

    <div class="row">
      <div class="col text-center">
        <p class="text-muted mb-0">
          © <?= date('Y') ?> TimeWise. All rights reserved.
          <span class="d-none d-md-inline">Built by Badr</span>
        </p>
      </div>
    </div>
  </div>
</footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="/assets/js/site.js"></script>
    <?php
      // Load page-specific script for login
      $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
      if (strpos($currentPath, '/login') === 0) {
          echo "\n    <script src=\"/assets/js/auth.js\"></script>\n";
      }
    ?>
    <?php if (isset($_SESSION['toast'])): ?>
      <script>
        document.addEventListener('DOMContentLoaded', function() {
          showToast(
            '<?= $_SESSION['toast']['type'] ?>',
            '<?= addslashes($_SESSION['toast']['title']) ?>',
            '<?= addslashes($_SESSION['toast']['message']) ?>'
          );
        });
      </script>
      <?php unset($_SESSION['toast']); ?>
    <?php endif; ?>
  </body>
  </html>
