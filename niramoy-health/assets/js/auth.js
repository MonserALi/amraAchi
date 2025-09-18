// ====== Auth Page Functionality ======
document.addEventListener('DOMContentLoaded', function () {
  // Toggle password visibility
  const togglePasswordButtons = document.querySelectorAll('.toggle-password');

  togglePasswordButtons.forEach(button => {
    button.addEventListener('click', function () {
      const passwordInput = this.previousElementSibling;
      const icon = this.querySelector('i');

      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    });
  });

  // Password strength meter
  const passwordInput = document.getElementById('password');
  const passwordStrength = document.querySelector('.password-strength');

  if (passwordInput && passwordStrength) {
    passwordInput.addEventListener('input', function () {
      const password = this.value;
      const strength = calculatePasswordStrength(password);

      const progressBar = passwordStrength.querySelector('.progress-bar');
      progressBar.style.width = strength.percentage + '%';

      // Set color based on strength
      progressBar.classList.remove('bg-danger', 'bg-warning', 'bg-info', 'bg-success');

      if (strength.score < 2) {
        progressBar.classList.add('bg-danger');
      } else if (strength.score < 3) {
        progressBar.classList.add('bg-warning');
      } else if (strength.score < 4) {
        progressBar.classList.add('bg-info');
      } else {
        progressBar.classList.add('bg-success');
      }

      // Update text
      const strengthText = passwordStrength.querySelector('.form-text');
      if (strengthText) {
        strengthText.textContent = strength.text;
      }
    });
  }

  // Show/hide doctor fields based on user type
  const userTypeSelect = document.getElementById('userType');
  const doctorFields = document.querySelector('.doctor-fields');

  if (userTypeSelect && doctorFields) {
    userTypeSelect.addEventListener('change', function () {
      if (this.value === 'doctor') {
        doctorFields.style.display = 'block';
        document.getElementById('bmdcCode').setAttribute('required', 'required');
      } else {
        doctorFields.style.display = 'none';
        document.getElementById('bmdcCode').removeAttribute('required');
      }
    });
  }

  // Form validation
  const loginForm = document.getElementById('loginForm');
  const registerForm = document.getElementById('registerForm');

  if (loginForm) {
    loginForm.addEventListener('submit', function (e) {
      if (!validateLoginForm()) {
        e.preventDefault();
      }
    });
  }

  if (registerForm) {
    registerForm.addEventListener('submit', function (e) {
      if (!validateRegisterForm()) {
        e.preventDefault();
      }
    });
  }

  // Display form errors if any
  displayFormErrors();
});

// Calculate password strength
function calculatePasswordStrength(password) {
  let score = 0;
  let percentage = 0;
  let text = '';

  if (password.length === 0) {
    return { score: 0, percentage: 0, text: '' };
  }

  // Length check
  if (password.length < 8) {
    score += 1;
  } else if (password.length >= 8 && password.length < 12) {
    score += 2;
  } else {
    score += 3;
  }

  // Complexity checks
  if (password.match(/[a-z]+/)) {
    score += 1;
  }

  if (password.match(/[A-Z]+/)) {
    score += 1;
  }

  if (password.match(/[0-9]+/)) {
    score += 1;
  }

  if (password.match(/[$@#&!]+/)) {
    score += 1;
  }

  // Calculate percentage
  percentage = Math.min(100, (score / 7) * 100);

  // Set text based on score
  if (score < 2) {
    text = 'Very Weak';
  } else if (score < 3) {
    text = 'Weak';
  } else if (score < 5) {
    text = 'Medium';
  } else if (score < 6) {
    text = 'Strong';
  } else {
    text = 'Very Strong';
  }

  return { score, percentage, text };
}

// Validate login form
function validateLoginForm() {
  const email = document.getElementById('email').value;
  const password = document.getElementById('password').value;
  let isValid = true;

  // Clear previous errors
  clearFormErrors();

  // Validate email
  if (!email) {
    showFieldError('email', 'Email is required');
    isValid = false;
  } else if (!isValidEmail(email)) {
    showFieldError('email', 'Please enter a valid email address');
    isValid = false;
  }

  // Validate password
  if (!password) {
    showFieldError('password', 'Password is required');
    isValid = false;
  }

  return isValid;
}

// Validate register form
function validateRegisterForm() {
  const name = document.getElementById('name').value;
  const email = document.getElementById('email').value;
  const phone = document.getElementById('phone').value;
  const userType = document.getElementById('userType').value;
  const password = document.getElementById('password').value;
  const confirmPassword = document.getElementById('confirmPassword').value;
  const agreeTerms = document.getElementById('agreeTerms').checked;
  let isValid = true;

  // Clear previous errors
  clearFormErrors();

  // Validate name
  if (!name) {
    showFieldError('name', 'Name is required');
    isValid = false;
  } else if (name.length < 3) {
    showFieldError('name', 'Name must be at least 3 characters');
    isValid = false;
  }

  // Validate email
  if (!email) {
    showFieldError('email', 'Email is required');
    isValid = false;
  } else if (!isValidEmail(email)) {
    showFieldError('email', 'Please enter a valid email address');
    isValid = false;
  }

  // Validate phone
  if (!phone) {
    showFieldError('phone', 'Phone number is required');
    isValid = false;
  }

  // Validate user type
  if (!userType) {
    showFieldError('userType', 'Please select a user type');
    isValid = false;
  }

  // Validate BM&DC code for doctors
  if (userType === 'doctor') {
    const bmdcCode = document.getElementById('bmdcCode').value;
    if (!bmdcCode) {
      showFieldError('bmdcCode', 'BM&DC code is required for doctors');
      isValid = false;
    }
  }

  // Validate password
  if (!password) {
    showFieldError('password', 'Password is required');
    isValid = false;
  } else if (password.length < 8) {
    showFieldError('password', 'Password must be at least 8 characters');
    isValid = false;
  } else if (!isStrongPassword(password)) {
    showFieldError('password', 'Password must contain uppercase, lowercase, number, and special character');
    isValid = false;
  }

  // Validate confirm password
  if (!confirmPassword) {
    showFieldError('confirmPassword', 'Please confirm your password');
    isValid = false;
  } else if (password !== confirmPassword) {
    showFieldError('confirmPassword', 'Passwords do not match');
    isValid = false;
  }

  // Validate terms agreement
  if (!agreeTerms) {
    showFieldError('agreeTerms', 'You must agree to the terms and conditions');
    isValid = false;
  }

  return isValid;
}

// Helper functions
function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

function isStrongPassword(password) {
  const strongRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
  return strongRegex.test(password);
}

function showFieldError(fieldId, message) {
  const field = document.getElementById(fieldId);
  const formGroup = field.closest('.form-group');

  // Add error class
  formGroup.classList.add('has-error');

  // Create error message element if it doesn't exist
  let errorElement = formGroup.querySelector('.field-error');
  if (!errorElement) {
    errorElement = document.createElement('div');
    errorElement.className = 'field-error text-danger';
    formGroup.appendChild(errorElement);
  }

  // Set error message
  errorElement.textContent = message;
}

function clearFormErrors() {
  const errorElements = document.querySelectorAll('.field-error');
  errorElements.forEach(element => element.remove());

  const formGroups = document.querySelectorAll('.form-group');
  formGroups.forEach(group => group.classList.remove('has-error'));
}

function displayFormErrors() {
  // Display login errors if any
  if (typeof loginErrors !== 'undefined' && loginErrors) {
    Object.keys(loginErrors).forEach(fieldId => {
      showFieldError(fieldId, loginErrors[fieldId]);
    });
  }

  // Display register errors if any
  if (typeof registerErrors !== 'undefined' && registerErrors) {
    Object.keys(registerErrors).forEach(fieldId => {
      showFieldError(fieldId, registerErrors[fieldId]);
    });
  }

  // Populate login form data if any
  if (typeof loginData !== 'undefined' && loginData) {
    if (loginData.email) document.getElementById('email').value = loginData.email;
    if (loginData.remember_me) document.getElementById('rememberMe').checked = true;
  }

  // Populate register form data if any
  if (typeof registerData !== 'undefined' && registerData) {
    if (registerData.name) document.getElementById('name').value = registerData.name;
    if (registerData.email) document.getElementById('email').value = registerData.email;
    if (registerData.phone) document.getElementById('phone').value = registerData.phone;
    if (registerData.user_type) {
      document.getElementById('userType').value = registerData.user_type;
      document.getElementById('userType').dispatchEvent(new Event('change'));
    }
    if (registerData.bmdc_code) document.getElementById('bmdcCode').value = registerData.bmdc_code;
    if (registerData.date_of_birth) document.getElementById('dateOfBirth').value = registerData.date_of_birth;
    if (registerData.gender) document.getElementById('gender').value = registerData.gender;
    if (registerData.blood_group) document.getElementById('bloodGroup').value = registerData.blood_group;
    if (registerData.address) document.getElementById('address').value = registerData.address;
  }
}