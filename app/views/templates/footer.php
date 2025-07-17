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

<style>

    html, body {
      height: 100%;
      margin: 0;
    }
    body {
      display: flex;
      flex-direction: column;
    }
    main {
      flex: 1 0 auto;
    }
    footer {
      flex-shrink: 0; 
    }

  footer {
    background: #f8f9fa;
    color: #09194D;
  }
  footer h5, footer h6 {
    color: #09194D;
  }
  .footer-link {
    color: #6c757d;
    text-decoration: none;
    transition: color .3s ease, transform .3s ease;
  }
  .footer-link:hover {
    color: #09194D;
    transform: translateX(4px);
  }
  .social-icons {
    font-size: 1.2rem;
    color: #6c757d;
    transition: color .3s ease, transform .3s ease;
  }
  .social-icons:hover {
    color: #09194D;
    transform: translateY(-2px);
  }
  footer hr {
    border-color: #dee2e6;
  }
</style>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


    <script>
        // Toast Functionality
        function showToast(type, title, message) {
            const toastContainer = document.getElementById('toastContainer');
            const toastId = 'toast-' + Date.now();

            const toastHtml = `
                <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'warning'} border-0" 
                     role="alert" aria-live="assertive" aria-atomic="true" id="${toastId}">
                    <div class="d-flex">
                        <div class="toast-body">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
                                <div>
                                    <strong>${title}</strong><br>
                                    <small>${message}</small>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;

            toastContainer.insertAdjacentHTML('beforeend', toastHtml);

            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement, {
                autohide: true,
                delay: 5000
            });

            toast.show();

            toastElement.addEventListener('hidden.bs.toast', function() {
                toastElement.remove();
            });
        }

        // Check for PHP session toast and display it
        <?php if (isset($_SESSION['toast'])): ?>
            document.addEventListener('DOMContentLoaded', function() {
                showToast(
                    '<?= $_SESSION['toast']['type'] ?>',
                    '<?= addslashes($_SESSION['toast']['title']) ?>',
                    '<?= addslashes($_SESSION['toast']['message']) ?>'
                );
            });
            <?php unset($_SESSION['toast']); ?>
        <?php endif; ?>

        // Enhanced Alert Functionality
        function showAlert(type, message, container = 'body') {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;

            const targetContainer = typeof container === 'string' ? document.querySelector(container) : container;
            if (targetContainer) {
                targetContainer.insertAdjacentHTML('afterbegin', alertHtml);
            }
        }

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    if (alert.classList.contains('show')) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                }, 5000);
            });
        });

        // Enhanced Navigation and Dropdown Improvements
        document.addEventListener('DOMContentLoaded', function() {
            const mobileLinks = document.querySelectorAll('.navbar-nav .nav-link:not(.dropdown-toggle)');
            const navbarCollapse = document.querySelector('.navbar-collapse');

            if (navbarCollapse) {
                mobileLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        if (window.innerWidth < 992) {
                            const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
                                toggle: false
                            });
                            bsCollapse.hide();
                        }
                    });
                });

                // Close mobile menu when clicking outside
                document.addEventListener('click', function(e) {
                    if (window.innerWidth < 992) {
                        const navbar = document.querySelector('.navbar');
                        const isClickInsideNavbar = navbar && navbar.contains(e.target);
                        const isMenuOpen = navbarCollapse.classList.contains('show');

                        if (!isClickInsideNavbar && isMenuOpen) {
                            const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
                                toggle: false
                            });
                            bsCollapse.hide();
                        }
                    }
                });
            }

            // Enhanced Dropdown Behavior
            const dropdownToggles = document.querySelectorAll('.navbar-nav .dropdown-toggle');

            dropdownToggles.forEach(toggle => {
                const dropdown = toggle.nextElementSibling;
                let hoverTimeout;

                // Desktop: Show on hover, hide on mouse leave
                if (window.innerWidth >= 992) {
                    toggle.addEventListener('mouseenter', function() {
                        clearTimeout(hoverTimeout);
                        dropdown.classList.add('show');
                        toggle.setAttribute('aria-expanded', 'true');
                    });

                    toggle.parentElement.addEventListener('mouseleave', function() {
                        hoverTimeout = setTimeout(() => {
                            dropdown.classList.remove('show');
                            toggle.setAttribute('aria-expanded', 'false');
                        }, 300);
                    });

                    // Keep dropdown open when hovering over it
                    dropdown.addEventListener('mouseenter', function() {
                        clearTimeout(hoverTimeout);
                    });

                    dropdown.addEventListener('mouseleave', function() {
                        hoverTimeout = setTimeout(() => {
                            dropdown.classList.remove('show');
                            toggle.setAttribute('aria-expanded', 'false');
                        }, 300);
                    });
                }

                // Click behavior for all screen sizes
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const isOpen = dropdown.classList.contains('show');

                    // Close all other dropdowns first
                    document.querySelectorAll('.navbar-nav .dropdown-menu.show').forEach(menu => {
                        if (menu !== dropdown) {
                            menu.classList.remove('show');
                            menu.previousElementSibling.setAttribute('aria-expanded', 'false');
                        }
                    });

                    // Toggle current dropdown
                    if (isOpen) {
                        dropdown.classList.remove('show');
                        toggle.setAttribute('aria-expanded', 'false');
                    } else {
                        dropdown.classList.add('show');
                        toggle.setAttribute('aria-expanded', 'true');
                    }
                });
            });

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.navbar-nav .dropdown')) {
                    document.querySelectorAll('.navbar-nav .dropdown-menu.show').forEach(menu => {
                        menu.classList.remove('show');
                        menu.previousElementSibling.setAttribute('aria-expanded', 'false');
                    });
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth < 992) {
                    // Remove hover effects on mobile
                    document.querySelectorAll('.navbar-nav .dropdown-menu.show').forEach(menu => {
                        menu.classList.remove('show');
                        menu.previousElementSibling.setAttribute('aria-expanded', 'false');
                    });
                }
            });
        });
    </script>
</body>
</html>