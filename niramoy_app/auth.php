<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login/Register - AmraAchi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* (styles condensed for brevity — reuse your provided CSS in production) */
    :root {
      --primary-color: #1a5276;
      --secondary-color: #2980b9;
      --accent-color: #27ae60;
      --light-bg: #ecf0f1;
      --dark-text: #2c3e50
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      min-height: 100vh
    }

    .auth-container {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px
    }

    .auth-card {
      background: #fff;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, .1);
      width: 100%;
      max-width: 900px;
      display: flex;
      min-height: 500px
    }

    .auth-left {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: #fff;
      padding: 40px;
      flex: 1
    }

    .auth-right {
      padding: 40px;
      flex: 1
    }

    .form-control {
      border-radius: 10px;
      border: 1px solid #ddd;
      padding: 12px 15px;
      margin-bottom: 20px
    }

    .btn-auth {
      background: var(--primary-color);
      color: #fff;
      border-radius: 30px;
      padding: 12px 30px;
      width: 100%
    }

    .role-option {
      flex: 1;
      text-align: center;
      padding: 10px;
      border: 2px solid #ddd;
      border-radius: 10px;
      cursor: pointer
    }

    .role-option.selected {
      border-color: var(--primary-color);
      background: rgba(26, 82, 118, .08)
    }
  </style>
</head>

<body>
  <header class="main-header">
    <div class="container">
      <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="index.php">AmraAchi</a>
      </nav>
    </div>
  </header>
  <div class="auth-container">
    <div class="auth-card">
      <div class="auth-left">
        <div class="auth-logo">AmraAchi</div>
        <div class="auth-tagline">Your Complete Digital Healthcare Solution</div>
        <ul class="auth-features">
          <li><i class="fas fa-check-circle"></i> Book appointments with top doctors</li>
          <li><i class="fas fa-check-circle"></i> Access your health records anytime</li>
        </ul>
      </div>
      <div class="auth-right">
        <ul class="nav nav-tabs" id="authTabs" role="tablist">
          <li class="nav-item" role="presentation"><button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button">Login</button></li>
          <li class="nav-item" role="presentation"><button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button">Register</button></li>
        </ul>

        <div class="tab-content">
          <div class="tab-pane fade show active" id="login">
            <form id="loginForm">
              <div class="role-selector">
                <label>Login as:</label>
                <div class="role-options d-flex gap-2">
                  <div class="role-option selected" data-role="patient"><i class="fas fa-user-injured"></i>
                    <div>Patient</div>
                  </div>
                  <div class="role-option" data-role="doctor"><i class="fas fa-user-md"></i>
                    <div>Doctor</div>
                  </div>
                  <div class="role-option" data-role="nurse"><i class="fas fa-user-nurse"></i>
                    <div>Nurse</div>
                  </div>
                  <div class="role-option" data-role="driver"><i class="fas fa-ambulance"></i>
                    <div>Driver</div>
                  </div>
                  <div class="role-option" data-role="hospital_admin"><i class="fas fa-hospital"></i>
                    <div>Hospital Admin</div>
                  </div>
                </div>
              </div>
              <input class="form-control" id="loginUsername" placeholder="Username or email" required>
              <input class="form-control" id="loginPassword" type="password" placeholder="Password" required>
              <button class="btn btn-auth" type="submit">Login</button>
            </form>
          </div>

          <div class="tab-pane fade" id="register">
            <form id="registerForm">
              <div class="role-selector">
                <label>Register as:</label>
                <div class="role-options d-flex gap-2">
                  <div class="role-option selected" data-role="patient"><i class="fas fa-user-injured"></i>
                    <div>Patient</div>
                  </div>
                  <div class="role-option" data-role="doctor"><i class="fas fa-user-md"></i>
                    <div>Doctor</div>
                  </div>
                  <div class="role-option" data-role="nurse"><i class="fas fa-user-nurse"></i>
                    <div>Nurse</div>
                  </div>
                  <div class="role-option" data-role="driver"><i class="fas fa-ambulance"></i>
                    <div>Driver</div>
                  </div>
                  <div class="role-option" data-role="hospital_admin"><i class="fas fa-hospital"></i>
                    <div>Hospital Admin</div>
                  </div>
                </div>
              </div>
              <input class="form-control" id="registerName" placeholder="Full Name" required>
              <input class="form-control" id="registerUsername" placeholder="Username" required>
              <input class="form-control" id="registerEmail" placeholder="Email">
              <input class="form-control" id="registerPassword" type="password" placeholder="Password" required>
              <input class="form-control" id="confirmPassword" type="password" placeholder="Confirm Password" required>
              <input class="form-control" id="registerPhone" placeholder="Phone">

              <!-- Doctor specific fields (visible when doctor selected) -->
              <div id="doctorFields" style="display:none;">
                <input class="form-control" id="bmdcCode" placeholder="BM&DC Code">
                <select class="form-control" id="departmentSelect">
                  <option>Loading departments...</option>
                </select>
                <input class="form-control" id="specialization" placeholder="Specialization">
              </div>

              <div class="form-check mb-3"><input class="form-check-input" id="agreeTerms" type="checkbox" required><label class="form-check-label">I agree to Terms</label></div>
              <button class="btn btn-auth" type="submit">Register</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer class="text-center py-3">&copy; 2025 AmraAchi</footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Role selection
    document.querySelectorAll('.role-option').forEach(opt => opt.addEventListener('click', function() {
      const parent = this.parentElement;
      parent.querySelectorAll('.role-option').forEach(o => o.classList.remove('selected'));
      this.classList.add('selected');
      // show doctor fields when doctor selected in register tab
      const role = this.dataset.role;
      const doctorFields = document.getElementById('doctorFields');
      if (role === 'doctor') doctorFields.style.display = 'block';
      else doctorFields.style.display = 'none';
    }));

    // Load departments into doctor registration select
    async function loadDepartments() {
      try {
        const res = await fetch('api.php?q=departments/list');
        const data = await res.json();
        const sel = document.getElementById('departmentSelect');
        sel.innerHTML = '<option value="">Select department (optional)</option>';
        (data.departments || []).forEach(d => {
          const opt = document.createElement('option');
          opt.value = d.id;
          opt.textContent = d.name;
          sel.appendChild(opt);
        });
      } catch (e) {
        console.error(e);
      }
    }
    loadDepartments();

    function showAlert(msg, type = 'success') {
      alert(msg);
    }

    // Login submit (calls API auth/login)
    document.getElementById('loginForm').addEventListener('submit', async function(e) {
      e.preventDefault();
      const role = document.querySelector('#login .role-option.selected')?.dataset.role || 'patient';
      const username = document.getElementById('loginUsername').value;
      const password = document.getElementById('loginPassword').value;
      try {
        const res = await fetch('api.php?q=auth/login/' + role, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            username,
            password
          })
        });
        const js = await res.json();
        if (!res.ok) {
          showAlert(js.error || 'Login failed', 'danger');
          return;
        }
        showAlert('Login successful! Redirecting...', 'success');
        // Use the redirect URL from the API response
        setTimeout(() => {
          window.location.href = js.redirect || 'patient.php';
        }, 1500);
      } catch (e) {
        console.error(e);
        showAlert('Login error', 'danger');
      }
    });

    // Login submit (calls API auth/login)
    document.getElementById('loginForm').addEventListener('submit', async function(e) {
      e.preventDefault();
      const role = document.querySelector('#login .role-option.selected')?.dataset.role || 'patient';
      const username = document.getElementById('loginUsername').value;
      const password = document.getElementById('loginPassword').value;
      try {
        const res = await fetch('api.php?q=auth/login/' + role, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            username,
            password
          })
        });
        const js = await res.json();
        if (!res.ok) {
          showAlert(js.error || 'Login failed', 'danger');
          return;
        }
        showAlert('Login successful! Redirecting...', 'success');
        // Use the redirect URL from the API response
        setTimeout(() => {
          window.location.href = js.redirect || 'patient.php';
        }, 1500);
      } catch (e) {
        console.error(e);
        showAlert('Login error', 'danger');
      }
    });
  </script>
</body>

</html>