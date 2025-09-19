<?php
session_start();
require_once __DIR__ . '/inc/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;
    $role = isset($_POST['role']) ? trim($_POST['role']) : null;

    if (!$username || !$password || !$role) {
        $error = "All fields are required including role selection";
    } else {
        $pdo = get_db();
        
        // First verify user exists and password is correct
        $stmt = $pdo->prepare('SELECT id, name, email, password FROM users WHERE name = :username OR email = :username LIMIT 1');
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Check if user has the selected role
            $roleStmt = $pdo->prepare('
                SELECT r.name 
                FROM user_roles ur 
                JOIN roles r ON ur.role_id = r.id 
                WHERE ur.user_id = :user_id AND r.name = :role_name
            ');
            $roleStmt->execute([
                ':user_id' => $user['id'],
                ':role_name' => $role
            ]);
            
            if ($roleStmt->rowCount() > 0) {
                // Get all roles for the user
                $allRolesStmt = $pdo->prepare('
                    SELECT r.name 
                    FROM user_roles ur 
                    JOIN roles r ON ur.role_id = r.id 
                    WHERE ur.user_id = :user_id
                ');
                $allRolesStmt->execute([':user_id' => $user['id']]);
                $roles = $allRolesStmt->fetchAll(PDO::FETCH_COLUMN);

                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'roles' => $roles,
                    'primary_role' => $role
                ];

                // Handle nurse/compounder special case
                if ($role === 'nurse' || $role === 'compounder') {
                    $nstmt = $pdo->prepare('SELECT verification_status FROM nurses WHERE user_id = :uid');
                    $nstmt->execute([':uid' => $user['id']]);
                    $nurse = $nstmt->fetch();
                    
                    if (!$nurse) {
                        $error = "Your nurse/compounder account is not properly set up";
                    } elseif ($nurse['verification_status'] !== 'verified') {
                        $error = "Your account is not verified yet";
                    } else {
                        header("Location: dashboard.php");
                        exit;
                    }
                } else {
                    // Redirect based on role
                    $redirect_page = match($role) {
                        'doctor' => 'doctor.php',
                        'driver' => 'driver.php',
                        'hospital_admin' => 'hospital_admin.php',
                        'patient' => 'patient.php',
                        default => 'dashboard.php'
                    };
                    header("Location: " . $redirect_page);
                    exit;
                }
            } else {
                $error = "You don't have permission to login as " . htmlspecialchars($role);
            }
        } else {
            $error = "Invalid username or password";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Niramoy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1a5276;
            --secondary-color: #2980b9;
            --accent-color: #27ae60;
        }
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        .login-container {
            max-width: 500px;
            margin: 50px auto;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .role-options {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }
        .role-option {
            flex: 1;
            min-width: 100px;
            text-align: center;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 10px;
            cursor: pointer;
        }
        .role-option.selected {
            border-color: var(--primary-color);
            background: rgba(26, 82, 118, 0.08);
        }
        .role-option i {
            font-size: 24px;
            margin-bottom: 5px;
            display: block;
        }
    </style>
</head>
<body>
    <div class="container login-container">
        <div class="card">
            <div class="card-body p-4">
                <h3 class="card-title text-center mb-4">Login to Niramoy</h3>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-4">
                        <label class="form-label">Login as:</label>
                        <div class="role-options">
                            <div class="role-option" data-role="patient">
                                <i class="fas fa-user-injured"></i>
                                <div>Patient</div>
                            </div>
                            <div class="role-option" data-role="doctor">
                                <i class="fas fa-user-md"></i>
                                <div>Doctor</div>
                            </div>
                            <div class="role-option" data-role="nurse">
                                <i class="fas fa-user-nurse"></i>
                                <div>Nurse</div>
                            </div>
                            <div class="role-option" data-role="driver">
                                <i class="fas fa-ambulance"></i>
                                <div>Driver</div>
                            </div>
                            <div class="role-option" data-role="hospital_admin">
                                <i class="fas fa-hospital"></i>
                                <div>Hospital Admin</div>
                            </div>
                        </div>
                        <input type="hidden" name="role" id="selectedRole" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Username or Email</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">Login</button>
                    </div>
                </form>
                
                <div class="text-center mt-3">
                    <a href="register.php" class="text-decoration-none">Don't have an account? Register here</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Role selection handling
        document.querySelectorAll('.role-option').forEach(option => {
            option.addEventListener('click', function() {
                // Remove selected class from all options
                document.querySelectorAll('.role-option').forEach(opt => 
                    opt.classList.remove('selected'));
                
                // Add selected class to clicked option
                this.classList.add('selected');
                
                // Update hidden input
                document.getElementById('selectedRole').value = this.dataset.role;
            });
        });
    </script>
</body>
</html>