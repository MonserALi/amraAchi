// Form Validation
document.addEventListener('DOMContentLoaded', function () {
  // Initialize all forms with validation
  const forms = document.querySelectorAll('.needs-validation');

  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }

      form.classList.add('was-validated');
    }, false);
  });

  // Custom validation for password confirmation
  const passwordInputs = document.querySelectorAll('input[data-match]');

  passwordInputs.forEach(input => {
    input.addEventListener('input', function () {
      const passwordField = document.getElementById(this.dataset.match);

      if (passwordField && passwordField.value !== this.value) {
        this.setCustomValidity('Passwords do not match');
      } else {
        this.setCustomValidity('');
      }
    });
  });

  // Phone number validation
  const phoneInputs = document.querySelectorAll('input[type="tel"]');

  phoneInputs.forEach(input => {
    input.addEventListener('input', function () {
      // Remove all non-digit characters
      let value = this.value.replace(/\D/g, '');

      // Format as BD phone number
      if (value.length > 0) {
        if (value.startsWith('880')) {
          value = '+' + value;
        } else if (value.startsWith('0')) {
          value = '+88' + value;
        } else {
          value = '+880' + value;
        }
      }

      this.value = value;

      // Validate length
      if (value.length < 13 || value.length > 14) {
        this.setCustomValidity('Please enter a valid Bangladeshi phone number');
      } else {
        this.setCustomValidity('');
      }
    });
  });

  // NID validation (Bangladeshi National ID)
  const nidInputs = document.querySelectorAll('input[data-validate="nid"]');

  nidInputs.forEach(input => {
    input.addEventListener('input', function () {
      // Remove all non-digit characters
      let value = this.value.replace(/\D/g, '');

      // Validate length (10 or 13 digits for old/new NID format)
      if (value.length !== 10 && value.length !== 13) {
        this.setCustomValidity('Please enter a valid NID (10 or 13 digits)');
      } else {
        this.setCustomValidity('');
      }

      this.value = value;
    });
  });

  // Birth Certificate validation (Bangladeshi)
  const birthCertInputs = document.querySelectorAll('input[data-validate="birth-cert"]');

  birthCertInputs.forEach(input => {
    input.addEventListener('input', function () {
      // Remove all non-digit and non-alphabet characters
      let value = this.value.replace(/[^a-zA-Z0-9]/g, '');

      // Validate format (17 digits/alphanumeric)
      if (value.length !== 17) {
        this.setCustomValidity('Please enter a valid birth certificate number (17 digits/alphanumeric)');
      } else {
        this.setCustomValidity('');
      }

      this.value = value;
    });
  });

  // BMDC validation (Bangladesh Medical & Dental Council)
  const bmdcInputs = document.querySelectorAll('input[data-validate="bmdc"]');

  bmdcInputs.forEach(input => {
    input.addEventListener('input', function () {
      // Remove all non-digit characters
      let value = this.value.replace(/\D/g, '');

      // Validate format (6 digits)
      if (value.length !== 6) {
        this.setCustomValidity('Please enter a valid BMDC registration number (6 digits)');
      } else {
        this.setCustomValidity('');
      }

      this.value = value;
    });
  });

  // License number validation (for nurses)
  const licenseInputs = document.querySelectorAll('input[data-validate="license"]');

  licenseInputs.forEach(input => {
    input.addEventListener('input', function () {
      // Remove all non-digit and non-alphabet characters
      let value = this.value.replace(/[^a-zA-Z0-9]/g, '');

      // Validate format (variable length, typically 6-10 alphanumeric characters)
      if (value.length < 6 || value.length > 10) {
        this.setCustomValidity('Please enter a valid license number (6-10 alphanumeric characters)');
      } else {
        this.setCustomValidity('');
      }

      this.value = value;
    });
  });
});

// Utility functions for validation
function validateEmail(email) {
  const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(String(email).toLowerCase());
}

function validatePhone(phone) {
  // Remove all non-digit characters
  const digits = phone.replace(/\D/g, '');

  // Check if it's a valid Bangladeshi phone number
  return digits.length === 13 && digits.startsWith('880');
}

function validateNID(nid) {
  // Remove all non-digit characters
  const digits = nid.replace(/\D/g, '');

  // Check if it's a valid NID (10 or 13 digits)
  return digits.length === 10 || digits.length === 13;
}

function validateBirthCert(cert) {
  // Remove all non-digit and non-alphabet characters
  const clean = cert.replace(/[^a-zA-Z0-9]/g, '');

  // Check if it's a valid birth certificate number (17 alphanumeric characters)
  return clean.length === 17;
}

function validateBMDC(bmdc) {
  // Remove all non-digit characters
  const digits = bmdc.replace(/\D/g, '');

  // Check if it's a valid BMDC registration number (6 digits)
  return digits.length === 6;
}

function validateLicense(license) {
  // Remove all non-digit and non-alphabet characters
  const clean = license.replace(/[^a-zA-Z0-9]/g, '');

  // Check if it's a valid license number (6-10 alphanumeric characters)
  return clean.length >= 6 && clean.length <= 10;
}

function validatePassword(password) {
  // Password should be at least 8 characters long
  // and contain at least one uppercase letter, one lowercase letter,
  // one digit, and one special character
  const re = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
  return re.test(password);
}

function showError(inputId, message) {
  const input = document.getElementById(inputId);
  const feedback = input.nextElementSibling;

  if (feedback && feedback.classList.contains('invalid-feedback')) {
    feedback.textContent = message;
  }

  input.classList.add('is-invalid');
}

function clearError(inputId) {
  const input = document.getElementById(inputId);
  input.classList.remove('is-invalid');
}

function showSuccess(message) {
  const alertDiv = document.createElement('div');
  alertDiv.className = 'alert alert-success alert-dismissible fade show';
  alertDiv.setAttribute('role', 'alert');
  alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

  const container = document.querySelector('.container');
  container.insertBefore(alertDiv, container.firstChild);

  // Auto-hide after 5 seconds
  setTimeout(() => {
    const bsAlert = new bootstrap.Alert(alertDiv);
    bsAlert.close();
  }, 5000);
}

function showDanger(message) {
  const alertDiv = document.createElement('div');
  alertDiv.className = 'alert alert-danger alert-dismissible fade show';
  alertDiv.setAttribute('role', 'alert');
  alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

  const container = document.querySelector('.container');
  container.insertBefore(alertDiv, container.firstChild);

  // Auto-hide after 5 seconds
  setTimeout(() => {
    const bsAlert = new bootstrap.Alert(alertDiv);
    bsAlert.close();
  }, 5000);
}