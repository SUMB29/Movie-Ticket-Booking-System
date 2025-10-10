<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['userid'])) {
    echo "<script>
            window.location.href = 'index.php';
          </script>";
    exit;
}

try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin- View All users</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 min-h-screen">

<!-- Navbar -->
<nav class="bg-gray-800 text-white shadow-lg">
  <div class="max-w-6xl mx-auto px-4">
    <div class="flex justify-between items-center h-16">
      <!-- Logo + Website Name -->
      <div class="flex items-center space-x-3">
        <img src="../assets/img/logo1.png" alt="Logo" class="w-10 h-10 rounded-full">
        <span class="text-xl font-bold text-white">MoviesDekho</span>
      </div>
      <!-- Right side nav -->
      <div class="hidden md:flex space-x-6">
        <a href="dashboard.php" class="hover:text-red-600 p-2 ml-1 rounded-2xl text-white">Home</a>
        <a href="logout.php" class="hover:text-red-600 text-white p-2 ml-1 rounded-2xl">Log out</a>
      </div>
    </div>
  </div>
</nav>

<!-- Table -->
<div class="bg-white p-6 rounded-2xl shadow-lg w-11/12 lg:w-4/5 m-auto mt-10 mb-60">
  <h2 class="text-xl font-semibold mb-4">All Users</h2>
  <table class="w-full border-collapse border border-gray-300 text-center">
    <thead>
      <tr class="bg-gray-200">
        <th class="border p-2">UserID</th>
        <th class="border p-2">UserName</th>
        <th class="border p-2">EmailID</th>
        <th class="border p-2">Actions</th>
      </tr>
    </thead>
    <tbody id="itemTable">
<?php

$sql = "
SELECT userid,name,email FROM `users` where roletype='user';
";

$result = $pdo->query($sql);


while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    echo '<tr>';
    echo '<td class="border p-2">' . htmlspecialchars($row['userid']) . '</td>';
    echo '<td class="border p-2">' . htmlspecialchars($row['name']) . '</td>';
    echo '<td class="border p-2">' . htmlspecialchars($row['email']) . '</td>';
    echo '<td class="flex justify-center border p-2 space-x-2">';
echo '<a href="viewusers.php?delete=' . $row['userid'] . '" onclick="return confirm(\'Delete this user?\')" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded">Delete</a>';
    echo '</tr>';
}
?>
    </tbody>
  </table>
</div>


<?php include('footer.php');  ?>
</body>
</html>



<?php
//delete
if(isset($_GET['delete'])){
    $id=$_GET['delete'];

        try {
        $stmt = $pdo->prepare("
            DELETE FROM `users` WHERE userid=:id
        ");
        $stmt->execute([
            ':id'     => $id,
        ]);

        echo "<script>
                alert('User deleted successfully!');
                window.location.href='viewusers.php';
              </script>";
        exit;

    } catch (PDOException $e) {
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
    }

}
?>
