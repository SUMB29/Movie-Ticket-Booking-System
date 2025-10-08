
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Ticket Booking</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black">

  <!-- Hero Section with background -->
  <div class="relative w-full h-screen bg-cover bg-bottom" style="background-image: url('assets/img/background.jpg');">

    <!-- Dark overlay -->
    <div class="absolute inset-0 bg-black/50"></div>

    <!-- Navbar -->
    <nav class="flex justify-between items-center px-6 py-4 relative z-10">
        <span class="flex text-white font-bold font-stretch-95%">
            <img src="assets/img/logo1.png" alt="Logo" width="53">
            <p class="m-auto text-2xl">MoviesDekho</p>
        </span>
    </nav>

    <!-- Hero content -->
    <div class="flex flex-col items-center justify-center text-center text-white gap-4 px-6 h-full relative z-10">
        <h1 class="text-5xl font-bold md:text-4xl">Book Your Ticket Now</h1>
        <p class="text-xl md:text-lg">Enjoy the latest movies on the big screen</p>

        <a href="login_process.php" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded font-semibold mt-4">
            Get Started
        </a>
    </div>

  </div>

</body>
</html>
