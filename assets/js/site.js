// Global site scripts extracted from footer.php
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
window.addEventListener('DOMContentLoaded', function() {
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
window.addEventListener('DOMContentLoaded', function() {
  const mobileLinks = document.querySelectorAll('.navbar-nav .nav-link:not(.dropdown-toggle)');
  const navbarCollapse = document.querySelector('.navbar-collapse');

  if (navbarCollapse) {
    mobileLinks.forEach(link => {
      link.addEventListener('click', function() {
        if (window.innerWidth < 992) {
          const bsCollapse = new bootstrap.Collapse(navbarCollapse, { toggle: false });
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
          const bsCollapse = new bootstrap.Collapse(navbarCollapse, { toggle: false });
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

// Show session-based toast if defined (PHP injects this into page)
// This is left to PHP to generate a DOMContentLoaded handler with showToast
