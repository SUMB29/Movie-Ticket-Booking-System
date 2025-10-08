<?php 
require_once 'connect.php';

try {
    $pdo = new PDO($attr, $user, $pass, $opts);
    // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MoviesDekho-Theatre</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 min-h-screen">

<form action="movies.php" method="POST" enctype="multipart/form-data">
  <!-- Navbar -->
  <nav class="bg-gray-800 text-white shadow-lg fixed w-full">
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



<!-- Live Search Bar -->
<div class="max-w-6xl mx-auto py-24 px-6">
    <input type="text" id="liveSearch" placeholder="Search movies, category, or theatre..." 
           class="w-full p-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-400">
</div>
<h2 class="text-3xl font-bold text-white mb-3 ml-56 sm:max-w-screen">Available Theatres and movies</h2>
<!-- Movie container -->
<div id="moviesContainer" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 max-w-6xl mx-auto py-4 px-6">
    <!-- Movies will load here via AJAX -->
</div>




<?php include('footer.php') ?>

  <script>
const searchInput = document.getElementById('liveSearch');
const moviesContainer = document.getElementById('moviesContainer');

searchInput.addEventListener('keyup', function() {
    const query = this.value;

    fetch('search_theatre.php?q=' + encodeURIComponent(query))
        .then(response => response.text())
        .then(data => {
            moviesContainer.innerHTML = data;
        })
        .catch(error => {
            console.error('Error:', error);
        });
});

// Trigger initial load (all movies)
fetch('search_theatre.php')
    .then(response => response.text())
    .then(data => {
        moviesContainer.innerHTML = data;
    });
</script>


</body>
</html>

