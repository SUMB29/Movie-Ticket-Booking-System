<?php 
require_once '../connect.php';

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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MoviesDekho - Home</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">

  <!-- Navbar -->
  <nav class="bg-gray-800 fixed w-full">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        <div class="flex items-center">
          <img class="h-10 w-10" src="../assets/img/logo1.png" alt="Logo">
          <span class="font-bold text-xl ml-2">MoviesDekho</span>
        </div>
        <div class="hidden md:block">
          <div class="ml-7 flex items-baseline space-x-1">
            <a href="#" class="hover:text-red-500 px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
            <a href="categories.php" class="hover:text-red-500 px-3 py-2 rounded-md text-sm font-medium">Categories</a>
            <a href="movies.php" class="hover:text-red-500 px-3 py-2 rounded-md text-sm font-medium">Movies</a>
            <a href="theatre.php" class="hover:text-red-500 px-3 py-2 rounded-md text-sm font-medium">Theatres</a>
            <a href="viewusers.php" class="hover:text-red-500 px-3 py-2 rounded-md text-sm font-medium">Users</a>
            <a href="viewbooking.php" class="hover:text-red-500 px-3 py-2 rounded-md text-sm font-medium">Bookings</a>
            <a href="profile.php" class="hover:text-red-500 px-3 py-2 rounded-md text-sm font-medium">Profile</a>
            <a href="logout.php" class="text-red-500 px-3 py-2 rounded-md text-sm font-medium">Logout</a>
          </div>
        </div>
        <!-- Mobile menu button -->
        <div class="md:hidden">
          <button id="mobile-menu-button" class="text-gray-300 hover:text-white focus:outline-none">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile menu -->
    <div class="md:hidden hidden" id="mobile-menu">
      <div class="px-2 pt-2 pb-3 space-y-1">
        <a href="#" class="block px-3 py-2 rounded-md hover:bg-gray-700">Dashboard</a>
        <a href="categories.php" class="block px-3 py-2 rounded-md hover:bg-gray-700">Categories</a>
        <a href="movies.php" class="block px-3 py-2 rounded-md hover:bg-gray-700">Movies</a>
        <a href="theatre.php" class="block px-3 py-2 rounded-md hover:bg-gray-700">Theatres</a>
        <a href="viewusers.php" class="block px-3 py-2 rounded-md hover:bg-gray-700">Users</a>
        <a href="viewbooking.php" class="block px-3 py-2 rounded-md hover:bg-gray-700">Bookings</a>
        <a href="profile.php" class="block px-3 py-2 rounded-md hover:bg-gray-700">Profile</a>
        <a href="logout.php" class="block px-3 py-2 rounded-md text-red-500 hover:bg-gray-700">Logout</a>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="bg-gray-900 py-20">
    <div class="max-w-7xl mx-auto text-center px-4">
      <h1 class="mt-4 text-4xl md:text-6xl font-bold mb-4">Hello ADMIN-Welcome to MoviesDekho</h1>
      <p class="text-gray-400 mb-8">Add movies & theatres, manage users & categories and more.</p>
     <!-- Live Search Bar -->
<div class="max-w-6xl mx-auto py-14 px-6">
    <input type="text" id="liveSearch" placeholder="Search movies, category, or theatre..." 
           class="w-full p-2 rounded-lg border border-gray-300 text-black focus:outline-none focus:ring-2 focus:ring-yellow-400">
</div>
<h2 class="text-3xl font-bold text-white mb-3">List of Movies</h2>
<!-- Movie container -->
<div id="moviesContainer" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 max-w-6xl mx-auto py-4 px-6">
    <!-- Movies will load here via AJAX -->
</div>

    </div>
  </section>

  <!-- Features Section -->
   <div class="container overflow-hidden m-auto">
  <section id="movies" class="py-16">
    <div class="max-w-7xl mx-auto px-4">
      <h2 class="text-3xl font-bold mb-8 text-center">Quick Access</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        <a href="categories.php" class="bg-gray-800 hover:bg-gray-700 p-6 rounded-lg text-center transition">
          <h3 class="font-bold text-xl mb-2">Categories</h3>
          <p class="text-gray-400">Manage movie categories</p>
        </a>
        <a href="movies.php" class="bg-gray-800 hover:bg-gray-700 p-6 rounded-lg text-center transition">
          <h3 class="font-bold text-xl mb-2">Movies</h3>
          <p class="text-gray-400">Add or edit movies</p>
        </a>
        <a href="theatre.php" class="bg-gray-800 hover:bg-gray-700 p-6 rounded-lg text-center transition">
          <h3 class="font-bold text-xl mb-2">Theatres</h3>
          <p class="text-gray-400">Manage theatres</p>
        </a>
        <a href="viewbooking.php" class="bg-gray-800 hover:bg-gray-700 p-6 rounded-lg text-center transition">
          <h3 class="font-bold text-xl mb-2">Bookings</h3>
          <p class="text-gray-400">View booking history</p>
        </a>
        <a href="viewusers.php" class="bg-gray-800 hover:bg-gray-700 p-6 rounded-lg text-center transition">
          <h3 class="font-bold text-xl mb-2">Users</h3>
          <p class="text-gray-400">Manage users</p>
        </a>
      </div>
    </div>
  </section></div>

  <script>
    const btn = document.getElementById('mobile-menu-button');
    const menu = document.getElementById('mobile-menu');

    btn.addEventListener('click', () => {
      menu.classList.toggle('hidden');
    });
  </script>
  <script>
const searchInput = document.getElementById('liveSearch');
const moviesContainer = document.getElementById('moviesContainer');

searchInput.addEventListener('keyup', function() {
    const query = this.value;

    fetch('search_movies.php?q=' + encodeURIComponent(query))
        .then(response => response.text())
        .then(data => {
            moviesContainer.innerHTML = data;
        })
        .catch(error => {
            console.error('Error:', error);
        });
});

// Trigger initial load (all movies)
fetch('search_movies.php')
    .then(response => response.text())
    .then(data => {
        moviesContainer.innerHTML = data;
    });
</script>




</body>
</html>
