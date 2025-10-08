<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - MoviesDekho</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap CSS -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <style>
    html, body {
      height: 100%;
      margin: 0;
      font-family: 'Inter', sans-serif;
      background: url('assets/img/background.jpg') no-repeat bottom center;
      background-size: cover;
      position: relative;
    }

    /* Dark overlay */
    body::before {
      content: '';
      position: absolute;
      inset: 0;
      background-color: rgba(0,0,0,0.6);
      z-index: 0;
    }

    /* Center container */
    .login-container {
      position: relative;
      z-index: 10;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 85%;
    }

    /* Login card */
    .login-card {
      backdrop-filter: blur(10px);
      background-color: rgba(0,0,0,0.6);
      border-radius: 1rem;
      padding: 1rem;
      width: 60%;
      max-width: 350px;
      box-shadow: 0 8px 30px rgba(0,0,0,0.5);
      color: white;
    }

    .login-card input {
      background-color: rgba(255,255,255,0.05);
      color: white;
      border: 1px solid rgba(255,255,255,0.3);
    }

    .login-card input::placeholder {
      color: rgba(255,255,255,0.6);
    }

    .btn-login {
      background-color: #e50914;
      border: none;
      transition: background 0.3s;
      width: 100%;
    }

    .btn-login:hover {
      background-color: #f6121d;
    }

    a {
      color: #fff;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <!-- Centered Login -->
  <div class="login-container">
    <div class="login-card text-center">
                 <!-- Navbar -->
    <nav class="flex justify-between items-center px-6 py-4 relative z-10 mt-2">
        <span>
            <img src="assets/img/logo1.png" alt="Logo" width="53">
        </span>
    </nav>
      <h2 class="fw-bold mb-2">Login</h2>

      <form action="login_process.php" method="POST">
        <div class="mb-2 text-start">
          <label for="email" class="form-label">Email</label>
          <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
        </div>
        <div class="mb-2 text-start">
          <label for="password" class="form-label">Password</label>
          <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
        </div>
 <div class="mb-1 text-start">
  <label for="role" class="form-label-sm">Role</label>
  <select class="form-select" id="gender" name="role" required>
    <option value="" selected disabled></option>
    <option value="admin">Admin</option>
    <option value="user">User</option>
  </select>
</div>
        <div class="d-flex justify-content-between align-items-center mb-2">
          <a href="#!" class="mt-1 text-white-50 small">Forgot password?</a>
        </div>

        <button type="submit" class="mt-2 btn btn-login" name="login_process">Login</button>
        <div class="text-center mt-3">
    <p>
        Don't have an account? 
        <a href="register.php" class="text-decoration-underline text-blue-700">Register</a>
    </p>
</div>

      </form>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<?php
session_start();
require_once 'connect.php';

try {
    // Create PDO connection
    $pdo = new PDO($attr, $user, $pass, $opts);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if login form is submitted
    if (isset($_POST['login_process'])) {

        $email    = trim($_POST['email']);
        $password = trim($_POST['password']);
        $role     = trim($_POST['role']);

        // Fetch user by email and role
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND roletype = :role LIMIT 1");
        $stmt->execute([':email' => $email, ':role' => $role]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
          
            // Verify hashed password
            if (password_verify($password, $user['password'])) {
                // Login successful, save session
                $_SESSION['userid'] = $user['userid'];
                $_SESSION['name']   = $user['name'];
                $_SESSION['role']   = $user['roletype'];

                 if ($user['roletype'] === 'admin') {
                                  echo "<script>
                        alert('Login successful as {$user['roletype']}');
                        window.location.href='index.php';
                      </script>";
                      header("Location: admin/dashboard.php");
                      exit;
                }else if($user['roletype'] === 'user') {
                                  echo "<script>
                        alert('Login successful as {$user['roletype']}');
                        window.location.href='index.php';
                      </script>";
                  header("Location: user_dashboard.php");
                  exit;
                }
            } else {
                echo "<script>
                        alert('Wrong password');
                        window.history.back();
                      </script>";
                exit;
            }
        } else {
            echo "<script>
                    alert('No user found with this email and role');
                    window.history.back();
                  </script>";
            exit;
        }
    }

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
