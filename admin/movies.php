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
$stmt=$pdo->prepare("SELECT * FROM movies where movieid=:editid");
$stmt->execute([':editid'=>$editid]);
$editdata=$stmt->fetch();
// $stmt=$pdo->prepare("Delete FROM categories where catid=:editid");
// $stmt->execute([':editid'=>$editid]);
}



// Fetch categories
$stmt = $pdo->query("SELECT catid, catname FROM categories ORDER BY catname ASC");
$categories = $stmt->fetchAll();
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MoviesDekho-admin/Movies</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 min-h-screen">

<form action="movies.php" method="POST" enctype="multipart/form-data">
  <!-- Navbar -->
  <nav class="bg-gray-800 text-white shadow-lg">
    <div class="max-w-6xl mx-auto px-4">
      <div class="flex justify-between items-center h-16">
        
        <!-- Logo + Website Name -->
        <div class="flex items-center space-x-3">
          <img src="../assets/img/logo1.png" alt="Logo" class="w-10 h-10 rounded-full">
          <span class="text-xl font-bold text-white">MoviesDekho</span>
        </div>

        <!-- Right side (optional nav links) -->
        <div class="hidden md:flex space-x-6">
          <a href="dashboard.php" class="hover:text-red-600 p-2 ml-1 rounded-2xl text-white">Home</a>
          <a href="logout.php" class="hover:text-red-600 text-white p-2 ml-1 rounded-2xl">Log out</a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="flex items-center justify-center py-12 px-4">
    <div class="bg-black p-8 rounded-2xl shadow-lg w-full max-w-md">
      <h1 class="text-white text-2xl font-bold mb-6 text-center">Add/Manage Movies</h1>

      <!-- Input Field -->
            <div class="mb-4">
          <!-- <label for="category">Select Category:</label> -->
          <select name="category" id="category" class="border border-b-black p-2 rounded-xl" required>
          <option value="">-- Choose a Category --</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= htmlspecialchars($cat['catid']) ?>">
                <?= htmlspecialchars($cat['catname']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <!-- <button type="submit">Submit</button> -->
        </div>
      <div class="mb-4">
        <label for="itemName1" class="block text-white font-medium mb-2">Title</label>
        <input type="text" id="itemName1" name="title" placeholder="Movie Title" value="<?=$editdata['title']?>"
               class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
      </div>

      <div class="mb-4">
        <label for="itemName2" class="block text-white font-medium mb-2">Description</label>
        <input type="text" id="itemName2" name="description" placeholder="Movie Description" value="<?=$editdata['description']?>"
               class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
      </div>

            <div class="mb-4">
        <label for="itemName3" class="block text-white font-medium mb-2">Release date</label>
        <input type="date" id="itemName3" name="releasedate" placeholder="Enter Release date" value="<?=$editdata['releasedate']?>"
               class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
      </div>

            <div class="mb-4">
        <label for="itemName4" class="block text-white font-medium mb-2">Poster</label>
        <input type="file" id="itemName4" name="image" placeholder="Upload poster" value="<?=$editdata['image']?>"
               class="w-full border border-gray-300 text-white rounded-lg p-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
      </div>

            <div class="mb-4">
        <label for="itemName5" class="block text-white font-medium mb-2">Trailer</label>
        <input type="file" id="itemName5" name="trailer" placeholder="Trailer of movie" value="<?=$editdata['trailer']?>"
               class="w-full border border-gray-300 text-white rounded-lg p-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
      </div>

            <div class="mb-4">
        <label for="itemName6" class="block text-white font-medium mb-2">Video</label>
        <input type="file" id="itemName6" name="movie" placeholder="Movie" value="<?=$editdata['movie']?>"
               class="w-full border border-gray-300 text-white rounded-lg p-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
      </div>

            <div class="mb-4">
        <label for="itemName7" class="block text-white font-medium mb-2">Rating (Optional)</label>
        <input type="text" id="itemName7" name="rating" placeholder="" value="<?=$editdata['rating']?>"
               class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-400 focus:outline-none" optional>
      </div>
 
      <!-- Buttons -->
      <div class="flex gap-4">
        <input type="submit" value="Add" name="add" class="flex-1 bg-orange-500 hover:bg-orange-600 text-white py-2 rounded-lg font-semibold">
        </input>
        <input type="hidden" name="movieid" value="<?= $editdata['movieid'] ?>">
        <button type="submit" name="update" class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg font-semibold">Update</button>


      </div>
    </div>
  </div>
</form>



<!-- table -->
<div class="bg-white p-6 rounded-2xl shadow-lg w-full max-w-3xl m-auto">
  <h2 class="text-xl font-semibold mb-4">List of Movies</h2>
  <table class="w-full border-collapse border border-gray-300 text-center">
    <thead>
      <tr class="bg-gray-200">
        <th class="border p-2">ID</th>
        <th class="border p-2">Title</th>
        <th class="border p-2">Release date</th>
        <th class="border p-2">Poster</th>
        <th class="border p-2">category</th>
        <th class="border p-2">Actions</th>
      </tr>
    </thead>
    <tbody id="itemTable">

    <?php
$result = $pdo->query("SELECT * FROM movies ORDER BY catid ASC");
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    echo '<tr>';
    echo '<td class="border p-2">' . $row['movieid'] . '</td>';
    echo '<td class="border p-2">' . htmlspecialchars($row['title']) . '</td>';
    echo '<td class="border p-2">' . htmlspecialchars($row['releasedate']) . '</td>';
    echo '<td class="border p-2"><img src="uploads/' . htmlspecialchars($row['image']) . '" width="50" height="50" alt="Poster"></td>';

    $stmtCat = $pdo->prepare("SELECT catname FROM categories WHERE catid = ?");
    $stmtCat->execute([$row['catid']]);
    $catRow = $stmtCat->fetch(PDO::FETCH_ASSOC);

    echo '<td class="border p-2">' . htmlspecialchars($catRow['catname'] ?? 'Unknown') . '</td>';

    echo '<td class="border p-2 space-x-2">
        <a href="movies.php?edit=' . $row['movieid'] . '" onclick="return confirm(\'Edit this Movie?\')" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</a>
        <a href="movies.php?delete=' . $row['movieid'] . '" onclick="return confirm(\'Delete this Movie?\')" class="bg-red-500 text-white px-2 py-1 rounded">Delete</a>
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
if (isset($_POST['add'])) {
    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);
    $releasedate = $_POST['releasedate'];
    $catid       = $_POST['category'];
    $rating      = $_POST['rating'];

    // Handle uploads
    $poster   = uniqid() . "_" . $_FILES['image']['name'];
    $trailer  = uniqid() . "_" . $_FILES['trailer']['name'];
    $movie    = uniqid() . "_" . $_FILES['movie']['name'];

    $tmp_poster  = $_FILES['image']['tmp_name'];
    $tmp_trailer = $_FILES['trailer']['tmp_name'];
    $tmp_movie   = $_FILES['movie']['tmp_name'];

    move_uploaded_file($tmp_poster,  "uploads/$poster");
    move_uploaded_file($tmp_trailer, "uploads/$trailer");
    move_uploaded_file($tmp_movie,   "uploads/$movie");

    try {
        $stmt = $pdo->prepare("
            INSERT INTO movies 
            (title, description, releasedate, image, trailer, movie, rating, catid) 
            VALUES (:title, :description, :releasedate, :image, :trailer, :movie, :rating, :category)
        ");
        $stmt->execute([
            ':title'       => $title,
            ':description' => $description,
            ':releasedate' => $releasedate,
            ':image'       => $poster,
            ':trailer'     => $trailer,
            ':movie'       => $movie,
            ':rating'      => $rating,
            ':category'    => $catid,
        ]);

        echo "<script>
                alert('Movie added successfully!');
                window.location.href='movies.php';
              </script>";
        exit;
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}


//delete
if(isset($_GET['delete'])){
    $id=$_GET['delete'];

        try {
        $stmt = $pdo->prepare("
            DELETE FROM `movies` WHERE movieid=:id
        ");
        $stmt->execute([
            ':id'     => $id,
        ]);

        echo "<script>
                alert('Movie deleted successfully!');
                window.location.href='movies.php';
              </script>";
        exit;

    } catch (PDOException $e) {
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
    }

}



// update
if (isset($_POST['update'])) {
    $movieid    = intval($_POST['movieid']); // hidden input in the form
    $title      = trim($_POST['title']);
    $description= trim($_POST['description']);
    $releasedate= $_POST['releasedate'];
    $catid      = $_POST['category'];
    $rating     = $_POST['rating'];

    // Fetch old data first (to keep old files if no new upload)
    $stmtOld = $pdo->prepare("SELECT m.image as image1, m.trailer as trailer, m.movie as movie FROM movies m WHERE movieid = :id");
    $stmtOld->execute([':id' => $movieid]);
    $oldData = $stmtOld->fetch(PDO::FETCH_ASSOC);

    $poster  = $oldData['image1'];
    $trailer = $oldData['trailer'];
    $movie   = $oldData['movie'];

    // Check if new files are uploaded
    if (!empty($_FILES['image']['name'])) {
        $poster = uniqid() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/$poster");
    }
    if (!empty($_FILES['trailer']['name'])) {
        $trailer = uniqid() . "_" . $_FILES['trailer']['name'];
        move_uploaded_file($_FILES['trailer']['tmp_name'], "uploads/$trailer");
    }
    if (!empty($_FILES['movie']['name'])) {
        $movie = uniqid() . "_" . $_FILES['movie']['name'];
        move_uploaded_file($_FILES['movie']['tmp_name'], "uploads/$movie");
    }

    try {
        $stmt = $pdo->prepare("
            UPDATE movies 
            SET title = :title, description = :description, releasedate = :releasedate, 
                image = :image, trailer = :trailer, movie = :movie, rating = :rating, catid = :category
            WHERE movieid = :id
        ");
        $stmt->execute([
            ':title'       => $title,
            ':description' => $description,
            ':releasedate' => $releasedate,
            ':image'       => $poster,
            ':trailer'     => $trailer,
            ':movie'       => $movie,
            ':rating'      => $rating,
            ':category'    => $catid,
            ':id'          => $movieid,
        ]);

        echo "<script>
                alert('Movie updated successfully!');
                window.location.href='movies.php';
              </script>";
        exit;
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
    }
}

?>

