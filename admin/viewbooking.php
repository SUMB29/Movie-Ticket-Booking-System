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
  <title>Admin View Bookings</title>
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
  <h2 class="text-xl font-semibold mb-4">All Bookings</h2>
  <table class="w-full border-collapse border border-gray-300 text-center">
    <thead>
      <tr class="bg-gray-200">
        <th class="border p-2">BookingID</th>
        <th class="border p-2">BookingDate</th>
        <th class="border p-2">Movie Title</th>
        <th class="border p-2">Time</th>
        <th class="border p-2">Category</th>
        <th class="border p-2">Theatre</th>
        <th class="border p-2">Location</th>
        <th class="border p-2">Status</th>
        <th class="border p-2">Actions</th>
      </tr>
    </thead>
    <tbody id="itemTable">
<?php
$sql = "
SELECT 
    booking.bookingid,
    booking.bookingdate,
    movies.title,
    theatre.timing,
    categories.catname,
    theatre.name AS theatre_name,
    theatre.location,
    booking.status 
FROM booking
INNER JOIN movies ON booking.movieid = movies.movieid
INNER JOIN users ON booking.userid = users.userid
INNER JOIN theatre ON theatre.theatreid = booking.theatreid
INNER JOIN categories ON movies.catid = categories.catid
WHERE users.userid = :id ORDER BY booking.bookingid DESC;
";

$result = $pdo->prepare($sql);
$result->execute([':id' => $_SESSION['userid']]);

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    echo '<tr>';
    echo '<td class="border p-2">' . htmlspecialchars($row['bookingid']) . '</td>';
    echo '<td class="border p-2">' . htmlspecialchars($row['bookingdate']) . '</td>';
    echo '<td class="border p-2">' . htmlspecialchars($row['title']) . '</td>';
    echo '<td class="border p-2">' . htmlspecialchars($row['timing']) . '</td>';
    echo '<td class="border p-2">' . htmlspecialchars($row['catname']) . '</td>';
    echo '<td class="border p-2">' . htmlspecialchars($row['theatre_name']) . '</td>';
    echo '<td class="border p-2">' . htmlspecialchars($row['location']) . '</td>';
    echo '<td class="border p-2">' . htmlspecialchars(($row['status']==0)?"pending":"Approved") . '</td>';
    echo '<td class="flex justify-center border p-2 space-x-2">';
echo '<a href="viewbooking.php?delete=' . $row['bookingid'] . '" onclick="return confirm(\'Delete this booking?\')" class="bg-red-500 text-white px-2 py-1 rounded">Delete</a>';

if ($row['status'] == 0) {
echo '<a href="viewbooking.php?approve=' . $row['bookingid'] . '&theatre=' . $row['theatre_name'] . '&movie=' . $row['title'] . '" 
         onclick="return confirm(\'Approve Booking?\')" 
         class="bg-green-500 text-white px-2 py-1 rounded ml-2">
         Approve
      </a>';

}
}
?>
    </tbody>
  </table>
</div>


<?php include('footer.php');  ?>
</body>
</html>



<?php

// approve
if (isset($_GET['approve'])) {
    $id       = $_GET['approve'];
    $theatre  = $_GET['theatre'] ?? null;
    $movie    = $_GET['movie'] ?? null;

    try {
        // First verify the booking exists with user, theatre, and movie
        $check = $pdo->prepare("
            SELECT * FROM booking 
            INNER JOIN theatre ON theatre.theatreid=booking.theatreid
            INNER JOIN movies ON booking.movieid=movies.movieid
            WHERE booking.bookingid = :id AND booking.userid = :user AND theatre.name = :theatre AND movies.title = :movie
        ");
        $check->execute([
            ':id'      => $id,
            ':user'    => $_SESSION['userid'],
            ':theatre' => $theatre,
            ':movie'   => $movie
        ]);
        $booking = $check->fetch(PDO::FETCH_ASSOC);

        if (!$booking) {
            echo "<script>alert('Invalid booking or access denied!'); window.location.href='viewbooking.php';</script>";
            exit;
        }

        // If valid, then approve
        $stmt = $pdo->prepare("
            UPDATE booking INNER JOIN  theatre ON theatre.theatreid=booking.theatreid
            INNER JOIN movies ON booking.movieid=movies.movieid
            SET booking.status = 1 
            WHERE booking.bookingid = :id AND booking.userid = :user AND theatre.name = :theatre AND movies.title = :movie
        ");
        $stmt->execute([
            ':id'      => $id,
            ':user'    => $_SESSION['userid'],
            ':theatre' => $theatre,
            ':movie'   => $movie
        ]);

        echo "<script>
                alert('Booking approved successfully!');
                window.location.href='viewbooking.php';
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
            DELETE FROM `booking` WHERE bookingid=:id
        ");
        $stmt->execute([
            ':id'     => $id,
        ]);

        echo "<script>
                alert('Booking deleted successfully!');
                window.location.href='viewbooking.php';
              </script>";
        exit;

    } catch (PDOException $e) {
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
    }

}
?>
