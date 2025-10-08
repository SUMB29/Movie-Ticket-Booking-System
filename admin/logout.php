<?php
session_start();
session_unset();
session_destroy();
?>

<script>
if (confirm("Are you sure you want to log out?")) {
    window.location.href = "../index.php";
} else {
    // If user clicks Cancel then stay on the same page
    window.location.href = document.referrer; 
}
</script>
