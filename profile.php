<?php
session_start();
require_once 'connect.php'; // PDO connection file

// Check if user is logged in
if (!isset($_SESSION['userid'])) {
    echo "<script>window.location.href='index.php';</script>";
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile - <?= htmlspecialchars($user['name']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 min-h-screen items-center justify-center">

<!-- Navbar -->
<nav class="bg-gray-800 text-white shadow-lg">
  <div class="max-w-6xl mx-auto px-4">
    <div class="flex justify-between items-center h-16">
      <!-- Logo + Website Name -->
      <div class="flex items-center space-x-3">
        <img src="assets/img/logo1.png" alt="Logo" class="w-10 h-10 rounded-full">
        <span class="text-xl font-bold text-white">MoviesDekho</span>
      </div>
      <!-- Right side nav -->
      <div class="hidden md:flex space-x-6">
        <a href="user_dashboard.php" class="hover:text-red-600 p-2 ml-1 rounded-2xl text-white">Home</a>
        <a href="logout.php" class="hover:text-red-600 text-white p-2 ml-1 rounded-2xl">Log out</a>
      </div>
    </div>
  </div>
</nav>
    <div class="bg-black text-white rounded-lg p-8 w-full max-w-md m-auto mt-10">
        <h2 class="text-2xl font-bold text-center mb-6">Your Profile</h2>
        
        <div class="space-y-4">
            <div>
                <label class="block font-semibold">Name:</label>
                <p><?= htmlspecialchars($user['name']) ?></p>
            </div>
            
            <div>
                <label class="block font-semibold">Email:</label>
                <p><?= htmlspecialchars($user['email']) ?></p>
            </div>
            
        </div>
        
        <div class="mt-6 flex justify-center gap-4">
            <a href="edit_profile.php?userid=<?= $user['userid'] ?>" 
               class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">Edit Profile</a>

        </div>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>
