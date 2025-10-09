<?php
session_start();
require_once 'connect.php';

if(!isset($_SESSION['userid'])){
     echo "<script>
                window.location.href = 'index.php';
              </script>";
}

try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Get movie id
$movieId = isset($_GET['movie']) ? intval($_GET['movie']) : 0;
// if ($movieId <= 0) die("Invalid movie selection.");

// Fetch movie details
$sql = "SELECT m.*, c.catname 
        FROM movies m
        LEFT JOIN categories c ON m.catid = c.catid
        WHERE m.movieid = :movieid";
$stmt = $pdo->prepare($sql);
$stmt->execute([':movieid' => $movieId]);
$movie = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$movie) die("Movie not found.");

// Fetch theatres for this movie
$sqlTheatre = "SELECT * FROM theatre WHERE movieid = :movieid";
$stmtTheatre = $pdo->prepare($sqlTheatre);
$stmtTheatre->execute([':movieid' => $movieId]);
$theatres = $stmtTheatre->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book - <?= htmlspecialchars($movie['title']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // JS: show seat selection only after theatre is selected
        function showSeats() {
            const theatreSelect = document.getElementById("theatreSelect");
            const seatContainer = document.querySelectorAll(".seat-box");
            seatContainer.forEach(box => box.classList.add("hidden"));

            if (theatreSelect.value) {
                const target = document.getElementById("seats-" + theatreSelect.value);
                if (target) target.classList.remove("hidden");
            }
        }
    </script>
</head>
<body class="bg-gray-900 text-white flex flex-row">
      <!-- Navbar -->
  <nav class="bg-gray-800 text-white shadow-lg fixed w-full m-0">
    <div class="max-w-6xl mx-auto px-4">
      <div class="flex justify-between items-center h-16">
        
        <!-- Logo + Website Name -->
        <div class="flex items-center space-x-3">
          <img src="assets/img/logo1.png" alt="Logo" class="w-10 h-10 rounded-full">
          <span class="text-xl font-bold text-white">MoviesDekho</span>
        </div>

        <!-- Right side (optional nav links) -->
        <div class="hidden md:flex space-x-6">
          <a href="user_dashboard.php" class="hover:text-red-600 p-2 ml-1 rounded-2xl text-white">Home</a>
          <a href="logout.php" class="hover:text-red-600 text-white p-2 ml-1 rounded-2xl">Log out</a>
        </div>
      </div>
    </div>
  </nav>

    <div class="max-w-3xl ml-96 mt-20 bg-gray-800 p-6 rounded-xl shadow-lg mb-5 m-auto">
        <h1 class="text-3xl font-bold mb-4">Book Tickets for <?= htmlspecialchars($movie['title']) ?></h1>
        <p class="text-gray-300 mb-2">Genre: <?= htmlspecialchars($movie['catname']) ?></p>
        <img src="admin/uploads/<?= htmlspecialchars($movie['image']) ?>" 
             alt="<?= htmlspecialchars($movie['title']) ?>" 
             class="w-full h-64 object-cover rounded-lg mb-4">

        <form action="book.php?movie=<?= $movieId ?>" method="POST">

            <!-- Theatre selection -->
             <input type="hidden" name="bookingid">
            <div class="mb-4">
                <label class="block mb-2">Choose Theatre:</label>
                <select name="theatre" id="theatreSelect" class="w-full p-2 rounded text-black" onchange="showSeats()" required>
                    <option value="">-- Select Theatre --</option>
                    <?php foreach ($theatres as $t): ?>
                        <option value="<?= $t['theatreid'] ?>">
                            <?= htmlspecialchars($t['name']) ?> 
                            (<?= htmlspecialchars($t['Location']) ?>) 
                            - ₹<?= htmlspecialchars($t['price']) ?> 
                            | Time: <?= htmlspecialchars($t['timing']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
<input type="hidden" name="movieid" value="<?= $movieId ?>">

 <!-- Date selection -->
            <div class="mb-4">
                <label class="block mb-2">Select Date:</label>
                <input type="date" name="date" class="w-full p-2 rounded text-black" required>
            </div>

            <!-- Dynamic Seat selection -->
            <?php foreach ($theatres as $t): ?>
                <div id="seats-<?= $t['theatreid'] ?>" class="seat-box hidden mb-4">
                    <label class="block mb-2">Select Seats (<?= $t['totalseats'] ?> available):</label>
                    <div class="grid grid-cols-10 gap-2">
                        <?php for ($i = 1; $i <= $t['totalseats']; $i++): ?>
                            <label class="flex items-center space-x-1">
                                <input type="checkbox" name="seats[]" value="<?= $i ?>" class="w-4 h-4">
                                <span><?= $i ?></span>
                            </label>
                        <?php endfor; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <input type="submit" name="add" value="Confirm Booking"
                    class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded text-white font-semibold mt-2">
                        </input>
        </form>
        <?php require_once "footer.php" ?>
    </div>
</body>
</html>



<!-- // Handle booking submission -->
<?php
if (isset($_POST['add'])) {
    $theatreId   = intval($_POST['theatre']);
    $bookingDate = trim($_POST['date']);
    $seats       = isset($_POST['seats']) ? $_POST['seats'] : [];
    $userId      = $_SESSION['userid'] ?? 0; // current logged-in user
    $movieId = isset($_POST['movieid']) ? intval($_POST['movieid']) : ( $_GET['movie'] ?? 0 );


    if ($userId <= 0) {
        die("<script>alert('Please log in to book tickets.'); window.location='login.php';</script>");
    }

    if ($theatreId > 0 && $bookingDate !== '' && !empty($seats) && $movieId > 0) {
        $selectedSeats = implode(",", $seats); // save as CSV

        $insert = "INSERT INTO booking (movieid, theatreid, bookingdate, userid, seats) 
                   VALUES (:movieid, :theatreid, :bdate, :userid, :seats)";
        $stmtInsert = $pdo->prepare($insert);
        $stmtInsert->execute([
            ':movieid'   => $movieId,      // from the GET parameter above
            ':theatreid' => $theatreId,
            ':bdate'     => $bookingDate,
            ':userid'    => $userId,
            ':seats'     => $selectedSeats
        ]);

        echo "<script>
                alert('Booking confirmed for seats: $selectedSeats'); 
                window.location='movies.php';
              </script>";
        exit;
    } else {
        echo "<script>alert('Please select theatre, date, and at least one seat.');</script>";
    }
}


?>




