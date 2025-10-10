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
    }

    /* Login card */
    .login-card {
      backdrop-filter: blur(10px);
      background-color: rgba(0,0,0,0.6);
      border-radius: 1rem;
      padding: 1rem;
      width: 90%;
      max-width: 400px;
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
          <nav class="flex justify-between items-center px-6 py-4 relative z-10 mt-2">
        <span>
            <img src="assets/img/logo1.png" alt="Logo" width="53">
        </span>
    </nav>
        <h2 class="fw-bold mb-4">Registration</h2>
    
      <form action="register.php" method="POST">

             <div class="mb-2 text-start">
          <label for="name" class="form-label">Name</label>
          <input type="text" name="name" id="name" class="form-control" placeholder="Enter Your Name" required>
        </div>

        <div class="mb-2 text-start space-x-1">
          <label for="email" class="form-label">Email</label>
          <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
        </div>
 <div class="mb-1 text-start">
  <label for="role" class="form-label">Roletype</label>
  <select class="form-select" id="gender" name="role" required>
    <option value="" selected disabled></option>
    <option value="admin">Admin</option>
    <option value="user">User</option>
  </select>
</div>
        <div class="mb-2 text-start">
          <label for="password" class="form-label">Password</label>
          <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
        </div>
                <div class="mb-2 text-start">
          <label for="cpassword" class="form-label">Confirm Password</label>
          <input type="password" name="cpassword" id="cpassword" class="form-control" placeholder="Enter password again" required>
        </div>

        <button type="submit" class="mt-2 btn btn-login font-stretch-150%" name="register">Register</button>
      </form>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
require_once 'connect.php';

try {
    $pdo = new PDO($attr, $user, $pass, $opts);
    // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if (isset($_POST['register'])) {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $cpassword= $_POST['cpassword'];
    $role     = $_POST['role'];

    if ($password !== $cpassword) {
        echo "<script>
        alert('Passwords do not match'); 
        window.history.back();
        </script>";
        exit;
    }

    $hashed_pass = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("
            INSERT INTO `users` (`name`, `email`, `password`, `roletype`) 
            VALUES (:name, :email, :password, :role)
        ");
        $stmt->execute([
            ':name'     => $name,
            ':email'    => $email,
            ':password' => $hashed_pass,
            ':role'     => $role
        ]);

        // Show alert then redirect
        //header('login_process.php');
        echo "<script>
                alert('Registration successful!');
                window.location.href = 'login_process.php';
              </script>";
        exit;

    } catch (PDOException $e) {
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
    }
}
?>


