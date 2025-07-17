</main>

    <footer class="bg-dark text-light py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="d-flex align-items-center mb-3">
                        <i class="fas fa-clipboard-list me-2"></i>
                        ReminderApp
                    </h5>
                    <p class="text-muted">Stay organized and never miss important tasks with our intuitive reminder system. Simple, secure, and always available.</p>
                    <div class="d-flex">
                        <a href="#" class="text-muted me-3" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-muted me-3" title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-muted me-3" title="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="text-muted" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>

                <div class="col-md-2 mb-4">
                    <h6 class="text-uppercase fw-bold mb-3">Product</h6>
                    <ul class="list-unstyled">
                        <li><a href="#features" class="text-muted text-decoration-none">Features</a></li>
                        <li><a href="#pricing" class="text-muted text-decoration-none">Pricing</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Mobile App</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Integrations</a></li>
                    </ul>
                </div>

                <div class="col-md-2 mb-4">
                    <h6 class="text-uppercase fw-bold mb-3">Company</h6>
                    <ul class="list-unstyled">
                        <li><a href="#about" class="text-muted text-decoration-none">About Us</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Careers</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Blog</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Press</a></li>
                    </ul>
                </div>

                <div class="col-md-2 mb-4">
                    <h6 class="text-uppercase fw-bold mb-3">Support</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">Help Center</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Contact Us</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">API Docs</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Status</a></li>
                    </ul>
                </div>

                <div class="col-md-2 mb-4">
                    <h6 class="text-uppercase fw-bold mb-3">Legal</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">Privacy Policy</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Terms of Service</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Cookie Policy</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">GDPR</a></li>
                    </ul>
                </div>
            </div>

            <hr class="my-4">

            <div class="row align-items-center">
                <div class="col-md-8">
                    <p class="text-muted mb-0">
                        &copy; <?= date('Y'); ?> ReminderApp. All rights reserved. 
                        <span class="d-none d-md-inline">Built by Badr</span>
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <p class="text-muted mb-0">
                        <i class="fas fa-envelope me-1"></i>
                        <a href="mailto:support@reminderapp.com" class="text-muted text-decoration-none">Badar@reminderapp.com</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

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


