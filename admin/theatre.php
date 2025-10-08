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
$stmt=$pdo->prepare("SELECT * FROM theatre where theatreid=:editid");
$stmt->execute([':editid'=>$editid]);
$editdata=$stmt->fetch();
}



// Fetch movies
$stmt = $pdo->query("SELECT movieid, title FROM movies ORDER BY movieid ASC");
$movies = $stmt->fetchAll();
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MoviesDekho-admin/Theatre</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 min-h-screen">

<form action="theatre.php" method="POST" enctype="multipart/form-data">
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
      <h1 class="text-white text-2xl font-bold mb-6 text-center">Add/Manage Theatres</h1>

      <!-- Input Field -->

      <div class="flex">
            <div class="mb-4">
          <!-- <label for="category">Select Category:</label> -->
          <select name="movieid" id="movie_category" class="border border-b-black rounded-xl p-2" required>
          <option value="">-- Select a Movie --</option>
          <?php foreach ($movies as $cat): ?>
            <option value="<?= htmlspecialchars($cat['movieid']) ?>">
                <?= htmlspecialchars($cat['title']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <p class="text-white mt-1">OR</p>
    <button class="btn bg-red-700 text-white rounded-xl gap-3 px-4 py-2 mt-2 hover:bg-red-600"><a href="movies.php">Add Movie</a></button>
    </div>
        </div>
          <p class="text-red-600 mb-2 mt-1">(NOTE: ADD THE MOVIE FIRST IF NOT PRESENT) *</p>
      <div class="mb-4">
        <label for="itemName1" class="block text-white font-medium mb-2">Timing</label>
        <input type="time" id="itemName1" name="timing" placeholder="Time of the movie" value="<?=$editdata['timing']?>"
               class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
      </div>
        <div class="mb-4">
        <label for="itemName5" class="block text-white font-medium mb-2">Theatre Name</label>
        <input type="text" id="itemName5" name="name" placeholder="Name of the theatre" value="<?=$editdata['name']?>"
               class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
      </div>
                              <div class="mb-4">
        <label for="itemName6" class="block text-white font-medium mb-2">Address</label>
        <input type="text" id="itemName6" name="location" placeholder="Location of the theatre" value="<?=$editdata['location']?>"
               class="w-full border border-gray-300  rounded-lg p-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
      </div>

<div class="mb-4">
  <select name="days[]" id="day" class="border border-b-black rounded-xl p-2" multiple required>
    <option value="sunday">Sunday</option>
    <option value="monday">Monday</option>
    <option value="tuesday">Tuesday</option>
    <option value="wednesday">Wednesday</option>
    <option value="thursday">Thursday</option>
    <option value="friday">Friday</option>
    <option value="saturday">Saturday</option>
  </select>
</div>


            <div class="mb-4">
        <label for="itemName3" class="block text-white font-medium mb-2">Start Date</label>
        <input type="date" id="itemName3" name="date" placeholder="Enter date" value="<?=$editdata['date']?>"
               class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
      </div>

            <div class="mb-4">
        <label for="itemName4" class="block text-white font-medium mb-2">Price</label>
        <input type="number" id="itemName4" name="price" placeholder="Price of ticket" value="<?=$editdata['price']?>"
               class="w-full border border-gray-300  rounded-lg p-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
      </div>


      <!-- Buttons -->
      <div class="flex gap-4">
        <input type="submit" value="Add" name="add" class="flex-1 bg-orange-500 hover:bg-orange-600 text-white py-2 rounded-lg font-semibold">
        </input>
         <input type="hidden" name="theatreid" value="<?= $editdata['theatreid'] ?>">
        <button type="submit" name="update" class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg font-semibold">Update</button>
  
      </div>
    </div>
  </div>
</form>



<!-- table -->
<div class="bg-white p-6 rounded-2xl shadow-lg w-full  m-auto">
  <h2 class="text-xl font-semibold mb-4">Theatres List</h2>
  <table class="w-full border-collapse border border-gray-300 text-center">
    <thead>
      <tr class="bg-gray-200">
        <th class="border p-2">Theatre Name</th>
        <th class="border p-2">Movie Name</th>
        <th class="border p-2">Genre</th>
        <th class="border p-2">Start Date</th>
        <th class="border p-2">Timing</th>
        <th class="border p-2">Days</th>
        <th class="border p-2">Price</th>
        <th class="border p-2">Actions</th>
      </tr>
    </thead>
    <tbody id="itemTable">

    <?php
$result = $pdo->query("SELECT 
    m.title AS moviename,
    c.catname AS moviegenre,
    t.*
FROM theatre t
INNER JOIN movies m ON t.movieid = m.movieid
INNER JOIN categories c ON c.catid = m.catid
ORDER BY t.theatreid ASC
");
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    echo '<tr>';
    echo '<td class="border p-2">' . $row['name'] . '</td>';

    // $stmtCat = $pdo->prepare("SELECT catname FROM categories WHERE catid = ?");
    // $stmtCat->execute([$row['catid']]);
    // $catRow = $stmtCat->fetch(PDO::FETCH_ASSOC);

    // echo '<td class="border p-2">' . htmlspecialchars($catRow['catname'] ?? 'Unknown') . '</td>';

    echo '<td class="border p-2">' . htmlspecialchars($row['moviename']) . '</td>';
    echo '<td class="border p-2">' . htmlspecialchars($row['moviegenre']) . '</td>';
    echo '<td class="border p-2">' . htmlspecialchars($row['date']) . '</td>';
    echo '<td class="border p-2">' . htmlspecialchars($row['timing']) . '</td>';
    echo '<td class="border p-2">' . htmlspecialchars($row['days']) . '</td>';
    echo '<td class="border p-2">' . htmlspecialchars($row['price']) . '</td>';
  

    echo '<td class="flex border p-2 space-x-2">
        <a href="theatre.php?edit=' . $row['theatreid'] . '" onclick="return confirm(\'Edit this theatre?\')" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</a>
        <a href="theatre.php?delete=' . $row['theatreid'] . '" onclick="return confirm(\'Delete this theatre?\')" class="bg-red-500 text-white px-2 py-1 rounded">Delete</a>
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
    $movieid= $_POST['movieid'];
    $name= $_POST['name'];
    $timing= $_POST['timing'];

    if (!empty($_POST['days'])) {
    $days = $_POST['days'];  //an array
    $days_string = implode(',', $days); // converted to string 
    }

    $date =$_POST['date'];
    $price= $_POST['price'];
    $location= $_POST['location'];

  


    try {
        $stmt = $pdo->prepare("
          INSERT INTO `theatre`(`movieid`, `name`, `timing`, `days`, `date`, `price`,`location`) 
          VALUES (:movieid,:name,:timing,:days,:date,:price,:location)
        ");
        $stmt->execute([
            ':movieid'=> $movieid,
            ':name' => $name,
            ':timing'=> $timing,
            ':days'=> $days_string,
            ':date'=> $date,
            ':price'=> $price,
            ':location'=> $location,
        ]);

        echo "<script>
                alert('Theatre added successfully!');
                window.location.href='theatre.php';
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
            DELETE FROM `theatre` WHERE movieid=:id
        ");
        $stmt->execute([
            ':id'     => $id,
        ]);

        echo "<script>
                alert('Theatre deleted successfully!');
                window.location.href='theatre.php';
              </script>";
        exit;

    } catch (PDOException $e) {
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
    }

}


//update
if (isset($_POST['update'])) {
    $theatreid = intval($_POST['theatreid']);   // hidden input
    $movieid   = $_POST['movieid'];
    $name      = $_POST['name'];
    $timing    = $_POST['timing'];

    $days_string = '';
    if (!empty($_POST['days'])) {
        $days_string = implode(',', $_POST['days']);
    }
    $date     = $_POST['date'];
    $price    = $_POST['price'];
    $location = $_POST['location'];

    try {
        $stmt = $pdo->prepare("
            UPDATE `theatre` 
            SET `movieid`=:movieid,
                `name`=:name,
                `timing`=:timing,
                `days`=:days,
                `date`=:date,
                `price`=:price,
                `location`=:location
            WHERE theatreid=:id
        ");
        $stmt->execute([
            ':movieid'=> $movieid,
            ':name'=> $name,
            ':timing'=> $timing,
            ':days'=> $days_string,
            ':date'=> $date,
            ':price'=> $price,
            ':location'=> $location,
            ':id'=> $theatreid,
        ]);

        echo "<script>
                alert('Theatre updated successfully!');
                window.location.href='theatre.php';
              </script>";
        exit;
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
    }
}

?>

