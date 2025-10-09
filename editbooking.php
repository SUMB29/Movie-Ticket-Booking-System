<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['userid'])) {
    echo "<script>window.location.href = 'index.php';</script>";
    exit;
}

try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

error_reporting(0);

if (isset($_GET['edit'])) {
    $editid = $_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM booking WHERE bookingid = :editid AND userid = :userid");
    $stmt->execute([':editid' => $editid, ':userid' => $_SESSION['userid']]);
    $editdata = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$editdata) {
        die("Invalid booking or access denied.");
    }
}

if (isset($_POST['update'])) {
    $status = $_POST['status'];

    $update = $pdo->prepare("UPDATE booking SET status = :status WHERE bookingid = :editid AND userid = :userid");
    $update->execute([
        ':status' => $status,
        ':editid' => $editid,
        ':userid' => $_SESSION['userid']
    ]);

    echo "<script>alert('Booking updated successfully!'); window.location.href='viewbooking.php';</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Booking</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen flex justify-center items-center">

<div class="bg-gray-800 p-6 rounded-xl shadow-lg w-96">
    <h2 class="text-2xl font-bold mb-4">Edit Booking</h2>
    
    <form method="POST">
        <label class="block mb-2">Booking ID: <?= htmlspecialchars($editdata['bookingid']) ?></label>
        
        <label for="status" class="block mb-2">Status</label>
        <select name="status" id="status" class="w-full p-2 rounded text-black">
            <option value="0" <?= $editdata['status'] == 0 ? 'selected' : '' ?>>Pending</option>
            <option value="1" <?= $editdata['status'] == 1 ? 'selected' : '' ?>>Approved</option>
        </select>

        <button type="submit" name="update" class="bg-blue-500 mt-4 px-4 py-2 rounded text-white hover:bg-blue-600 w-full">Update</button>
    </form>
</div>

</body>
</html>
