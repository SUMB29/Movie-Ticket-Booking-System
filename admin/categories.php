<?php
require_once '../connect.php';

try {
    $pdo = new PDO($attr, $user, $pass, $opts);
    // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}


//edit
error_reporting(0);
if(isset($_GET['edit'])){
$editid=$_GET['edit'];
$stmt=$pdo->prepare("SELECT * FROM categories where catid=:editid");
$stmt->execute([':editid'=>$editid]);
$editdata=$stmt->fetch();
// $stmt=$pdo->prepare("Delete FROM categories where catid=:editid");
// $stmt->execute([':editid'=>$editid]);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MoviesDekho-admin/categories</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 min-h-screen">
<form action="categories.php" method="POST" >
  <!-- Navbar -->
  <nav class="bg-gray-800 text-white shadow-lg">
    <div class="max-w-6xl mx-auto px-4">
      <div class="flex justify-between items-center h-16">
        
        <!-- Logo + Website Name -->
        <div class="flex items-center space-x-3">
          <img src="../assets/img/logo1.png" alt="Logo" class="w-10 h-10 rounded-full">
          <span class="text-xl font-bold">MoviesDekho</span>
        </div>

        <!-- Right side (optional nav links) -->
        <div class="hidden md:flex space-x-6">
          <a href="dashboard.php" class="hover:text-red-600 p-2 ml-1 rounded-2xl">Home</a>
          <a href="logout.php" class="hover:text-red-600 p-2 ml-1 rounded-2xl">Log out</a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="flex items-center justify-center py-12 px-4">
    <div class="bg-black p-8 rounded-2xl shadow-lg w-full max-w-md">
      <h1 class="text-white text-2xl font-bold mb-6 text-center">Manage Categories</h1>

      <!-- Input Field -->
       <input type="hidden" name="catid" value="<?= $editdata['catid'] ?? '' ?>">
      <div class="mb-4">
        <label for="itemName" class="block text-white font-medium mb-2">Category Name</label>
        <input type="text" id="itemName" name="catname" placeholder="Enter category name" value="<?=$editdata['catname']?>"
               class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
      </div>

      <!-- Buttons -->
      <div class="flex gap-x-0.5">
        <input type="submit" value="Add" name="add" class="flex-1 bg-orange-500 hover:bg-orange-600 text-white py-2 rounded-lg font-semibold">
          Add
        </input>
        <input type="submit" value="Update" name="update" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white py-2 rounded-lg font-semibold">
          Update
</input>
      </div>
    </div>
  </div>
</form>

<!-- table -->
<div class="bg-white p-6 rounded-2xl shadow-lg w-full max-w-3xl m-auto">
  <h2 class="text-xl font-semibold mb-4">Categories List</h2>
  <table class="w-full border-collapse border border-gray-300 text-center">
    <thead>
      <tr class="bg-gray-200">
        <th class="border p-2">ID</th>
        <th class="border p-2">Category Name</th>
        <th class="border p-2">Action</th>
      </tr>
    </thead>
    <tbody id="itemTable">
      <?php
        $result = $pdo->query("SELECT * FROM categories ORDER BY catid ASC");
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td class="border p-2">' . $row['catid'] . '</td>';
            echo '<td class="border p-2">' . htmlspecialchars($row['catname']) . '</td>';
            echo '<td class="border p-2 space-x-2">
                    <a href="categories.php?edit=' . $row['catid'] . '" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</a>
                    <a href="categories.php?delete=' . $row['catid'] . '" onclick="return confirm(\'Delete this category?\')" class="bg-red-500 text-white px-2 py-1 rounded">Delete</a>
                  </td>';
            echo '</tr>';
        }
      ?>
    </tbody>
  </table>
</div>


<?php include('footer.php') ?>
</body>
</html>


<?php
//add
if(isset($_POST['add'])){
    $name=$_POST['catname'];

        try {
        $stmt = $pdo->prepare("
            INSERT INTO `categories` (`catname`) 
            VALUES (:name)
        ");
        $stmt->execute([
            ':name'     => $name,
        ]);

        echo "<script>
                alert('Category added successfully!');
                  window.location.href='categories.php';
              </script>";
        exit;

    } catch (PDOException $e) {
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
    }

}

//delete
if(isset($_GET['delete'])){
    $id=$_GET['delete'];

        try {
        $stmt = $pdo->prepare("
            DELETE FROM `categories` WHERE catid=:id
        ");
        $stmt->execute([
            ':id'     => $id,
        ]);

        echo "<script>
                alert('Category deleted successfully!');
                window.location.href='categories.php';
              </script>";
        exit;

    } catch (PDOException $e) {
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
    }

}

//update
if (isset($_POST['update'])) {
    $catid   = intval($_POST['catid']);   // Hidden input field
    $catname = trim($_POST['catname']);

    if ($catid > 0 && !empty($catname)) {
        $stmt = $pdo->prepare("UPDATE categories SET catname = :catname WHERE catid = :catid");
        $updated = $stmt->execute([
            ':catname' => $catname,
            ':catid'   => $catid
        ]);

        if ($updated) {
            echo "<script>
            alert('Category updated successfully!');
            window.location.href='categories.php';
            </script>";
            exit;
        } else {
            echo "<script>
            alert('Failed to update category.');
            </script>";
        }
    } else {
        echo "<script>
        alert('Invalid input.');
        </script>";
    }
}
?>
