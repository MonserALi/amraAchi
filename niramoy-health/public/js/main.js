// Document Ready
document.addEventListener('DOMContentLoaded', function () {
  // Initialize tooltips
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Initialize popovers
  var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
  var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl);
  });

  // Auto-hide alerts after 5 seconds
  setTimeout(function () {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function (alert) {
      const bsAlert = new bootstrap.Alert(alert);
      bsAlert.close();
    });
  }, 5000);

  // Smooth scrolling for navigation links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();

      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        window.scrollTo({
          top: target.offsetTop - 150,
          behavior: 'smooth'
        });
      }
    });
  });

  // Add active class to navigation items on scroll
  window.addEventListener('scroll', function () {
    let current = '';
    const sections = document.querySelectorAll('section');

    sections.forEach(section => {
      const sectionTop = section.offsetTop;
      const sectionHeight = section.clientHeight;
      if (scrollY >= (sectionTop - 200)) {
        current = section.getAttribute('id');
      }
    });

    document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
      link.classList.remove('active');
      if (link.getAttribute('href').substring(1) === current) {
        link.classList.add('active');
      }
    });
  });

  // Department Scroller
  const departmentsScroller = document.getElementById('departmentsScroller');
  const scrollLeftBtn = document.getElementById('scrollLeft');
  const scrollRightBtn = document.getElementById('scrollRight');

  if (scrollLeftBtn && scrollRightBtn && departmentsScroller) {
    scrollLeftBtn.addEventListener('click', function () {
      departmentsScroller.scrollBy({
        left: -300,
        behavior: 'smooth'
      });
    });

    scrollRightBtn.addEventListener('click', function () {
      departmentsScroller.scrollBy({
        left: 300,
        behavior: 'smooth'
      });
    });
  }

  // Language Toggle
  const langToggle = document.getElementById('langToggle');
  if (langToggle) {
    langToggle.addEventListener('click', function () {
      const isBangla = this.textContent === 'বাংলা';
      const enTexts = document.querySelectorAll('.en-text');
      const bnTexts = document.querySelectorAll('.bn-text');

      if (isBangla) {
        enTexts.forEach(text => text.style.display = 'inline');
        bnTexts.forEach(text => text.style.display = 'none');
        this.textContent = 'বাংলা';
      } else {
        enTexts.forEach(text => text.style.display = 'none');
        bnTexts.forEach(text => text.style.display = 'inline');
        this.textContent = 'English';
      }

      // Save language preference in cookie
      setCookie('language', isBangla ? 'en' : 'bn', 30);
    });
  }

  // Epidemic Alert
  const epidemicButton = document.querySelector('.epidemic-button');
  const epidemicAlert = document.getElementById('epidemic-alert');
  const closeEpidemic = document.getElementById('closeEpidemic');
  const epidemicBanner = document.getElementById('epidemicBanner');
  const closeBanner = document.getElementById('closeBanner');

  if (epidemicButton && epidemicAlert) {
    epidemicButton.addEventListener('click', function () {
      epidemicAlert.classList.add('active');
      window.scrollTo({
        top: epidemicAlert.offsetTop - 100,
        behavior: 'smooth'
      });
    });
  }

  if (closeEpidemic && epidemicAlert) {
    closeEpidemic.addEventListener('click', function () {
      epidemicAlert.classList.remove('active');
    });
  }

  if (closeBanner && epidemicBanner) {
    closeBanner.addEventListener('click', function () {
      epidemicBanner.style.display = 'none';
      setCookie('epidemic_banner_closed', 'true', 1);
    });

    // Check if banner was previously closed
    if (getCookie('epidemic_banner_closed') === 'true') {
      epidemicBanner.style.display = 'none';
    }
  }

  // Newsletter Form
  const newsletterForm = document.getElementById('newsletter-form');
  if (newsletterForm) {
    newsletterForm.addEventListener('submit', function (e) {
      e.preventDefault();

      const email = this.querySelector('input[type="email"]').value;

      // Simple email validation
      if (!validateEmail(email)) {
        showAlert('Please enter a valid email address', 'danger');
        return;
      }

      // Here you would normally send the email to your server
      // For demo purposes, we'll just show a success message
      showAlert('Thank you for subscribing to our newsletter!', 'success');
      this.reset();
    });
  }

  // Search functionality
  const searchInput = document.querySelector('.search-input');
  const searchBtn = document.querySelector('.search-btn');

  if (searchInput && searchBtn) {
    searchBtn.addEventListener('click', function () {
      const searchTerm = searchInput.value.trim();
      if (searchTerm) {
        // Redirect to search results page
        window.location.href = BASE_URL + 'search?q=' + encodeURIComponent(searchTerm);
      }
    });

    searchInput.addEventListener('keypress', function (e) {
      if (e.key === 'Enter') {
        searchBtn.click();
      }
    });
  }

  // Initialize form validation
  initFormValidation();
});

// Utility Functions
function setCookie(name, value, days) {
  const expires = new Date();
  expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
  document.cookie = name + '=' + value + ';expires=' + expires.toUTCString() + ';path=/';
}

function getCookie(name) {
  const nameEQ = name + "=";
  const ca = document.cookie.split(';');
  for (let i = 0; i < ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) === ' ') c = c.substring(1, c.length);
    if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
  }
  return null;
}

function deleteCookie(name) {
  document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/';
}

function validateEmail(email) {
  const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(String(email).toLowerCase());
}

function showAlert(message, type = 'info') {
  const alertDiv = document.createElement('div');
  alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
  alertDiv.setAttribute('role', 'alert');
  alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

  // Find a good place to insert the alert
  const container = document.querySelector('.main-content') || document.body;
  container.insertBefore(alertDiv, container.firstChild);

  // Auto-hide after 5 seconds
  setTimeout(() => {
    const bsAlert = new bootstrap.Alert(alertDiv);
    bsAlert.close();
  }, 5000);
}

function initFormValidation() {
  const forms = document.querySelectorAll('.needs-validation');

  forms.forEach(form => {
    form.addEventListener('submit', function (event) {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }

      form.classList.add('was-validated');
    }, false);
  });
}

function showLoading(element) {
  const originalContent = element.innerHTML;
  element.disabled = true;
  element.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';

  return {
    restore: function () {
      element.disabled = false;
      element.innerHTML = originalContent;
    }
  };
}

function formatDateTime(date) {
  const options = {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  };
  return new Date(date).toLocaleDateString(undefined, options);
}

function formatDate(date) {
  const options = {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  };
  return new Date(date).toLocaleDateString(undefined, options);
}

function formatTime(time) {
  const options = {
    hour: '2-digit',
    minute: '2-digit'
  };
  return new Date(`2000-01-01T${time}`).toLocaleTimeString(undefined, options);
}

function truncateText(text, maxLength) {
  if (text.length <= maxLength) return text;
  return text.substr(0, maxLength) + '...';
}