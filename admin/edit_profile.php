<?php
session_start();
require_once '../connect.php'; // PDO connection file

// Check if user is logged in
if (!isset($_SESSION['userid'])) {
    echo "<script>window.location.href='../index.php';</script>";
    exit;
}

try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Fetch user details
$userid = $_SESSION['userid'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE userid = :userid");
$stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found!";
    exit;
}

// Update logic should come before HTML output (to prevent header issues)
if (isset($_POST['update'])) {
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);

    try {
        $stmt = $pdo->prepare("
            UPDATE users 
            SET name = :name, email = :email 
            WHERE userid = :id
        ");
        $stmt->execute([
            ':name'  => $name,
            ':email' => $email,
            ':id'    => $userid
        ]);

        echo "<script>
                alert('Profile updated successfully!');
                window.location.href='profile.php';
              </script>";
        exit;
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile - <?= htmlspecialchars($user['name']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 min-h-screen flex flex-col">

<!-- Navbar -->
<nav class="bg-gray-800 text-white shadow-lg">
  <div class="max-w-6xl mx-auto px-4">
    <div class="flex justify-between items-center h-16">
      <!-- Logo + Website Name -->
      <div class="flex items-center space-x-3">
        <img src="assets/img/logo1.png" alt="Logo" class="w-10 h-10 rounded-full">
        <span class="text-xl font-bold">MoviesDekho</span>
      </div>
      <!-- Right side nav -->
      <div class="hidden md:flex space-x-6">
        <a href="user_dashboard.php" class="hover:text-red-600 p-2 rounded-2xl">Home</a>
        <a href="logout.php" class="hover:text-red-600 p-2 rounded-2xl">Log out</a>
      </div>
    </div>
  </div>
</nav>

<!-- Profile Form -->
<form action="" method="POST" class="flex-grow flex items-center justify-center">
    <div class="bg-black text-white rounded-lg p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold text-center mb-6">Edit Your Profile</h2>
        
        <div class="space-y-4">
            <div>
                <label for="nameid" class="block mb-1">Name:</label>
                <input type="text" name="name" id="nameid"
                    value="<?= htmlspecialchars($user['name']) ?>"
                    class="text-black w-full px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>
            
            <div>
                <label for="emailid" class="block mb-1">Email:</label>
                <input type="email" name="email" id="emailid"
                    value="<?= htmlspecialchars($user['email']) ?>"
                    class="text-black w-full px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>
        </div>
        
        <div class="mt-6 flex justify-center gap-4">
            <button type="submit" name="update"
                class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">
                Save
            </button>
            <a href="profile.php"
                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                Cancel
            </a>
        </div>
    </div>
</form>

<?php include('footer.php'); ?>
</body>
</html>
