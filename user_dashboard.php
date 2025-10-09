
<?php
include ('connect.php');
if(isset($_SESSION['uid'])){
     echo "<script>
                window.location.href = 'login_process.php';
              </script>";
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
  <nav class="bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        <div class="flex items-center">
          <img class="h-10 w-10" src="assets/img/logo1.png" alt="Logo">
          <span class="font-bold text-xl ml-2">MoviesDekho</span>
        </div>
        <div class="hidden md:block">
          <div class="ml-7 flex items-baseline space-x-1">
            <a href="user_dashboard.php" class="hover:text-red-500 px-3 py-2 rounded-md text-sm font-medium">Home</a>
            <a href="movies.php" class="hover:text-red-500 px-3 py-2 rounded-md text-sm font-medium">Movies</a>
            <a href="theatre.php" class="hover:text-red-500 px-3 py-2 rounded-md text-sm font-medium">Theatres</a>
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
        <a href="user_dashboard.php" class="block px-3 py-2 rounded-md hover:bg-gray-700">Home</a>
        <a href="movies.php" class="block px-3 py-2 rounded-md hover:bg-gray-700">Movies</a>
        <a href="theatre.php" class="block px-3 py-2 rounded-md hover:bg-gray-700">Theatres</a>
        <a href="viewbooking.php" class="block px-3 py-2 rounded-md hover:bg-gray-700">Bookings</a>
        <a href="profile.php" class="block px-3 py-2 rounded-md hover:bg-gray-700">Profile</a>
        <a href="logout.php" class="block px-3 py-2 rounded-md text-red-500 hover:bg-gray-700">Logout</a>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="bg-gray-900 py-20">
    <div class="max-w-7xl mx-auto text-center px-4">
      <h1 class="text-4xl md:text-6xl font-bold mb-4">Hello USER-Welcome to MoviesDekho</h1>
      <p class="text-gray-400 mb-8">Explore movies, book tickets and more.</p>
      <a href="movies.php" class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-semibold">Explore Now</a>
    </div>
  </section>


  <script>
    const btn = document.getElementById('mobile-menu-button');
    const menu = document.getElementById('mobile-menu');

    btn.addEventListener('click', () => {
      menu.classList.toggle('hidden');
    });
  </script>

<?php require_once 'footer.php'; ?>
</body>
</html>
